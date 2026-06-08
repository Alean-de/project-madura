<?php

namespace App\Http\Controllers;

use App\Models\InventoryAdjustment; 
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class InventoryAdjustmentController extends Controller
{

    public function index(Request $request): View|JsonResponse
    {
        // 1. Ambil data jika request datang dari AJAX (untuk datatable/render item)
        if ($request->ajax() || $request->wantsJson()) {
            
            // Eager load relasi 'product' agar data barang ikut terbawa dalam JSON
            $query = InventoryAdjustment::with('product');

            // FILTER SEARCH: Diperbaiki dari 'name' menjadi 'product_name' sesuai database Anda
            $query->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('product', function ($productQuery) use ($request) {
                    $productQuery->where('product_name', 'like', "%{$request->search}%");
                });
            });

            // FILTER DROPDOWN STATUS
            $query->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            });

            // Ambil data terbaru dengan paginasi
            $adjustments = $query->latest()->paginate(10);

            return response()->json([
                'success' => true,
                'data'    => $adjustments
            ]);
        }

        // 2. Jika diakses biasa, ambil master barang untuk dropdown modal Blade
        $products = Product::orderBy('product_name', 'asc')->get();

        return view('inventory_adjustment', compact('products'));
    }

    /**
     * Menyimpan data adjustment baru (POST).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id', 
            'exp_date'   => 'required|date',
            'qty'        => 'required|integer|min:1',
            'status'     => 'required|in:barang_masuk,rusak,exp,barang_keluar,pending',
        ]);

        // Gunakan Transaksi agar jika salah satu query gagal, database otomatis rollback
        DB::transaction(function () use ($validated) {
            // 1. Catat ke Log Perubahan
            InventoryAdjustment::create($validated);

            // 2. Update Stok Aktual di Tabel Product
            $product = Product::findOrFail($validated['product_id']);
            
            if (in_array($validated['status'], ['barang_masuk', 'pending'])) {
                // Tambah stok jika barang masuk
                $product->increment('initial_stock', $validated['qty']); // Asumsi nama kolom di tabel product kamu adalah 'stock'
            } else {
                // Kurangi stok jika barang keluar / rusak / exp
                $product->decrement('initial_stock', $validated['qty']);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Data adjustment berhasil disimpan dan stok produk telah diperbarui!'
        ]);
    }
}