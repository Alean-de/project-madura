<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Model\InventoryAdjustment;

class ProductController extends Controller
{
   public function index(Request $request)
    {
        $query = Product::owned()->with([
            'categories' => fn($q) => $q->where('status', true),
            'suppliers' => fn($q) => $q->where('status', true)
        ]);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('product_name', 'like', "%{$request->search}%");
        });

        $query->when($request->filled('category_id'), function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        $query->when($request->filled('supplier_id'), function ($q) use ($request) {
            $q->where('supplier_id', $request->supplier_id);
        });

        $query->when($request->filled('stock_status'), function ($q) use ($request) {
            if ($request->stock_status === 'low') {
                $q->whereColumn('initial_stock', '<=', 'minimum_stock');
            } elseif ($request->stock_status === 'safe') {
                $q->whereColumn('initial_stock', '>', 'minimum_stock');
            }
        });

        $products = $query->latest()->paginate(10);

        // 3. JIKA AJAX / JSON: Langsung return di sini (Hemat Query Kategori & Supplier!)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }

        // 4. JIKA BUKAN AJAX: Baru ambil data untuk dropdown view
        $category = Category::owned()->where('status', true)->get();
        $supplier = Supplier::owned()->where('status', true)->get();

        return view('product', compact('products', 'category', 'supplier'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'product_name'  => 'required|max:255|unique:products,product_name',
            'category_id'   => 'required|exists:categories,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'purchase_price'=> 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'initial_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit'          => 'required|string|max:50',
        ]);

        $newProduct = Product::create(array_merge($validated, [
            'user_id' => auth()->id()
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Ditambahkan',
            'data'    => $newProduct
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $produk = Product::owned()->findOrFail($id); 
        
        $validated = $request->validate([
            'product_name'  => 'required|max:255|unique:products,product_name,' . $id . ',product_id', 
            'category_id'   => 'required|exists:categories,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'purchase_price'=> 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit'          => 'required|string|max:50',
        ]);

        $produk->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui!',
            'data'    => $produk
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:barang_masuk,rusak,exp,barang_keluar,pending',
        ]);

        try {
            $adjustment = InventoryAdjustment::findOrFail($id);
            $adjustment->status = $request->status;
            $adjustment->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah secara real-time.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database.'
            ], 500);
        }
    }

    public function delete(int $id)
    {
        $produk = Product::owned()->findOrFail($id);
        $produk->delete();

       return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Dihapus'
        ], 200);
    }
}