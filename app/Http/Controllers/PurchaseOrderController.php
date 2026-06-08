<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\DetailPo;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\InventoryAdjustment;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all(); 

        $query = PurchaseOrder::with(['supplier', 'detailPo.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan nomor PO
                $q->where('po_number', 'LIKE', "%{$search}%")
                // Atau cari berdasarkan nama supplier dari tabel relasinya
                ->orWhereHas('supplier', function ($sq) use ($search) {
                    $sq->where('supplier_name', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('purchase_orders.supplier_id', $request->supplier_id);
        }

        $suppliers = \App\Models\Supplier::orderBy('supplier_name', 'asc')->get();

        $purchase_orders = $query->latest()->get();

        return view('pre_order', compact('suppliers', 'products', 'purchase_orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'status' => 'required|in:pending,selesai,dibatalkan',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Membuat data PO utama dengan menyertakan user_id dari user yang login
            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . now()->format('YmdHis'),
                'supplier_id' => $request->supplier_id,
                'status' => $request->status,
                'total_price' => 0,
                'user_id' => auth()->id(), // <--- Solusi untuk error Field 'user_id'
            ]);

            $grandTotal = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);

                $subtotal = $item['qty'] * $product->purchase_price;

                DetailPo::create([
                    'purchase_order_id' => $po->id, 
                    'product_id'        => $product->product_id,
                    'quantity'          => $item['qty'],
                    'uom'               => $product->unit ?? 'PCS',
                    'uom_multiplier'    => 1,
                    'unit_price'        => $product->purchase_price,
                    'subtotal'          => $subtotal, 
                    'user_id'           => auth()->id(), 
                ]);

                $grandTotal += $subtotal;
            }

            // Update total harga setelah semua item detail selesai dihitung
            $po->update([
                'total_price' => $grandTotal
            ]);
        });

        return redirect()
            ->route('po.index')
            ->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,selesai,dibatalkan'
        ]);

        // Gunakan DB Transaction agar jika PO sukses terupdate tapi Adjustment gagal, data otomatis dibatalkan (aman)
        return DB::transaction(function () use ($request, $id) {
            $po = PurchaseOrder::with('detailPo')->findOrFail($id);

            // Jika status yang dikirim sama dengan status sekarang, tidak perlu diproses
            if ($po->status === $request->status) {
                return response()->json(['success' => true, 'message' => 'Status tidak berubah.']);
            }

            // Simpan status lama untuk pengecekan nanti
            $oldStatus = $po->status;

            // Update status PO utama
            $po->status = $request->status;
            $po->save();

            // 2. LOGIKA OTOMATIS: Jika PO berubah menjadi 'selesai'
            if ($request->status === 'selesai' && $oldStatus !== 'selesai') {
                
                // Loop semua produk yang ada di dalam detail PO tersebut
                foreach ($po->detailPo as $detail) {
                    InventoryAdjustment::create([
                        'product_id' => $detail->product_id,
                        'qty'        => $detail->quantity,
                        'status'     => 'barang_masuk', // Otomatis tercatat sebagai barang masuk
                        'exp_date'   => now()->addYears(1)->format('Y-m-d'), // Batas penanda EXP sementara jika vendor tidak mencatatnya
                        // 'notes'   => "Otomatis dari penyelesaian berkas " . $po->po_number (Opsional jika di DB kamu ada kolom catatan)
                    ]);

                    // 💡 TIPS TAMBAHAN: Di sini Kamu juga bisa langsung menambahkan query 
                    // untuk menambah stok utama di tabel 'products' Kamu jika diperlukan.
                    // $detail->product->increment('stock', $detail->quantity);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Status PO berhasil diperbarui dan mutasi stok telah dicatat!'
            ]);
        });
    }

    public function update()
    {
        // Tempatkan logika update di sini nanti
    }

    public function delete()
    {
        // Tempatkan logika delete di sini nanti
    }
}