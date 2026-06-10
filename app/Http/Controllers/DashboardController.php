<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseOrder; 
use App\Models\Supplier; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();

        $totalUnitStock = Product::sum('initial_stock');

        $lowStock = Product::whereColumn('initial_stock', '<=', 'minimum_stock')->count();

        $poPending = PurchaseOrder::where('status', 'pending')->count(); // Sesuaikan kolom status

        $totalSupplier = Supplier::count();

            $categoryData = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.category_name as cn', DB::raw('count(products.id) as total_products'))
            ->groupBy('categories.id', 'categories.category_name')
            ->get();

        // Pisahkan menjadi array Label dan array Data untuk Chart.js
        $donutLabels = $categoryData->pluck('cn')->toArray();
        $donutData = $categoryData->pluck('total_products')->toArray();

        $months = [];
        $stockInRaw = [];
        $stockOutRaw = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('F'); // Mengambil nama bulan (Januari, Februari, dst)
            
            // Inisialisasi data default 0 jika bulan tersebut tidak ada transaksi
            $stockInRaw[$date->format('Y-m')] = 0;
            $stockOutRaw[$date->format('Y-m')] = 0;
        }

        // 2. Query data history masuk & keluar dari database dikelompokkan per bulan
        // Sesuaikan kolom 'type' ('in'/'out'), 'quantity', dan 'created_at' dengan database Anda
        $adjustments = DB::table('inventory_adjustments') // ganti dengan nama tabel histori Anda
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(CASE WHEN status = 'barang_masuk' THEN qty ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN status IN ('barang_keluar', 'rusak', 'exp') THEN qty ELSE 0 END) as total_out"))
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->get();

        // 3. Masukkan hasil query ke dalam array yang sudah kita siapkan
        foreach ($adjustments as $data) {
            if (isset($stockInRaw[$data->month])) {
                $stockInRaw[$data->month] = (int)$data->total_in;
                $stockOutRaw[$data->month] = (int)$data->total_out;
            }
        }

        // 4. Ubah menjadi array murni (tanpa key bulan) agar bisa dibaca Chart.js
        $chartLabels = $months;
        $chartDataIn = array_values($stockInRaw);
        $chartDataOut = array_values($stockOutRaw);

        return view('dashboard', compact(
            'totalProduk', 
            'totalUnitStock', 
            'lowStock', 
            'poPending', 
            'totalSupplier',
            'donutLabels',
            'donutData',
            'chartLabels',
            'chartDataIn',
            'chartDataOut'
        ));
    }
}
