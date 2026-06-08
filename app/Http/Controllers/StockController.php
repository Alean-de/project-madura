<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        // 1. Ambil semua data produk untuk tabel
        // Sesuaikan nama variabel jamak 'product' agar pas dengan loop @foreach ($product as $p) di Blade kamu
        $product = Product::all(); 

        // 2. Hitung total unit stok dari seluruh jenis barang yang ada di gudang
        // Gunakan nama kolom stok berjalan di databasemu (asumsi: 'stok')
        $totalUnitStock = Product::sum('initial_stock'); 

        // 3. Hitung produk yang masuk kategori low stock (stok menipis / di bawah minimum stok)
        $lowStockCount = Product::whereRaw('initial_stock <= minimum_stock')->count();

        // 4. Kirim data ke view (sesuaikan dengan lokasi file blademu, misal: resources/views/stock/index.blade.php)
        return view('daftar_stok', compact('product', 'totalUnitStock', 'lowStockCount'));
    }
}