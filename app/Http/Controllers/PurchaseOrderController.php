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

        $query = PurchaseOrder::with([
            'supplier',
            'detailPo.product'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'LIKE', "%{$search}%")
                ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('supplier_name', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $suppliers = Supplier::orderBy('supplier_name')->get();

        $purchase_orders = $query->latest()->get();

        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'html' => view(
                    'partials.po_table',
                    compact('purchase_orders')
                )->render()
            ]);
        }

        return view('pre_order', compact(
            'suppliers',
            'products',
            'purchase_orders'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:pending,selesai,dibatalkan',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        // Jalankan DB Transaction
        return DB::transaction(function () use ($request) {
            $userId = auth()->id();

            // 1. Ambil semua data produk yang dikirim sekaligus
            $productIds = collect($request->products)->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // 2. Buat PO Utama terlebih dahulu
            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . now()->format('YmdHis'),
                'supplier_id' => $request->supplier_id,
                'status' => $request->status,
                'total_price' => 0,
                'user_id' => $userId,
            ]);

            // 3. Transformasi data input menjadi format Detail PO menggunakan Collection Map
            $grandTotal = 0;
            
            // PASTIKAN instruksi "use" membawa $products, $userId, $request, dan &$grandTotal
            $details = collect($request->products)->map(function ($item) use ($products, $userId, $request, &$grandTotal) {
                $product = $products->get($item['product_id']);
                $subtotal = $item['qty'] * $product->purchase_price;
                $grandTotal += $subtotal;

                return [
                    'product_id'     => $product->id, 
                    'supplier_id'    => $request->supplier_id, // Mengambil supplier_id langsung dari $request
                    'quantity'       => $item['qty'],
                    'uom'            => $product->unit ?? 'PCS',
                    'uom_multiplier' => 1,
                    'unit_price'     => $product->purchase_price,
                    'subtotal'       => $subtotal,
                    'user_id'        => $userId,
                ];
            });

            // 4. Simpan semua detail lewat relasi Eloquent yang benar (detailPo) dan update grand total
            $po->detailPo()->createMany($details->toArray()); 
            $po->update(['total_price' => $grandTotal]);

            // Load relasi agar data yang dikembalikan lengkap
            $po->load('detailPo');

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order Berhasil Ditambahkan',
                'data'    => $po
            ]);
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,selesai,dibatalkan'
        ]);

        // Gunakan DB Transaction agar jika PO sukses terupdate tapi Adjustment gagal, data otomatis dibatalkan (aman)
        return DB::transaction(function () use ($request, $id) {
            $po = PurchaseOrder::with('detailPo.product')->findOrFail($id);

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
                    ]);

                    // 💡 TIPS TAMBAHAN: Di sini Kamu juga bisa langsung menambahkan query 
                    // untuk menambah stok utama di tabel 'products' Kamu jika diperlukan.
                    $detail->product->increment('initial_stock', $detail->quantity);
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