<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{   
    public function index(Request $request)
    {
        // 1. Inisialisasi query utama agar filter bisa dipakai bareng-bareng
        $query = Product::with('categories'); 
        
        // Filter Pencarian Teks
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter Kondisi Tingkat Stok Aktual
        if ($request->filled('stock_status')) {
            $status = $request->stock_status;
            if ($status === 'rendah') {
                $query->whereRaw('initial_stock <= minimum_stock');
            } elseif ($status === 'tipis') {
                $query->whereRaw('initial_stock > minimum_stock')
                    ->whereRaw('initial_stock <= (minimum_stock + 10)');
            } elseif ($status === 'cukup') {
                $query->whereRaw('initial_stock > (minimum_stock + 10)');
            }
        }

        // 2. JIKA REQUEST ADALAH AJAX
        if ($request->ajax() || $request->wantsJson()) {
        
            // FIX: Langsung hitung dari model Product utama agar angkanya mengunci/absolut
            $totalUnitStock = (int) Product::sum('initial_stock');
            $lowStockCount  = (int) Product::whereRaw('initial_stock <= minimum_stock')->count();

            // Eksekusi paginasi tabel (ini yang tetap boleh difilter)
            $products = $query->latest()->paginate(10);

            return response()->json([
                'success'         => true,
                'data'            => $products,
                'totalUnitStock'  => $totalUnitStock, // Kirim nilai absolut global
                'lowStockCount'   => $lowStockCount   // Kirim nilai absolut global
            ]);
        }

        // 3. JIKA AKSES PERTAMA KALI (Non-AJAX)
        $totalUnitStock = Product::sum('initial_stock'); 
        $lowStockCount = Product::whereRaw('initial_stock <= minimum_stock')->count();
        $categories = \App\Models\Category::orderBy('category_name', 'asc')->get(); 

        return view('daftar_stok', compact('totalUnitStock', 'lowStockCount', 'categories'));
    }
}