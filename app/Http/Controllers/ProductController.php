<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::owned()->with([
            'categories' => function ($q) {
                $q->where('status', true);
            },
            'suppliers' => function ($q) {
                $q->where('status', true);
            }
        ]);

            // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('product_name', 'like', "%{$search}%");
        }

        // FILTER CATEGORY
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // FILTER SUPPLIER
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // FILTER STOCK
        if ($request->filled('stock_status')) {

            if ($request->stock_status === 'low') {

                $query->whereRaw('initial_stock <= minimum_stock');

            } elseif ($request->stock_status === 'safe') {

                $query->whereRaw('initial_stock > minimum_stock');

            }
        }

        $products = $query
            ->latest()
            ->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }

        $category = Category::owned()->where('status', true)->get();

        $supplier = Supplier::owned()->where('status', true)->get();

        return view('product', compact(
            'products', 'category', 'supplier'
        ));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'product_name'  => 'required|max:255|unique:products,product_name',
            'category_id'   => 'required|exists:categories,category_id',
            'supplier_id'   => 'required|exists:suppliers,supplier_id',
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
            'category_id'   => 'required|exists:categories,category_id',
            'supplier_id'   => 'required|exists:suppliers,supplier_id',
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