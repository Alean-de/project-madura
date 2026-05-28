<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'product_name' => 'required|unique:products,product_name|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'selling_price' => 'required|numeric|min:0'
        ]);

        Product::create([
            'user_id' => auth()->id(),
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'initial_stock' => $request->initial_stock,
            'minimum_stock' => $request->minimum_stock,
            'unit' => $request->unit,
        ]);
        return redirect()->back()->with('success', 'Produk Berhasil Ditambahkan');
    }

    public function delete(int $id)
    {
        $produk = Product::owned()->findOrFail($id);
        $produk->delete();

        return redirect()->back()->with('success', 'Produk Berhasil Dihapus');
    }

    public function update(Request $request, int $id)
    {
        $produk = Product::owned()->findOrFail($id); 
        
         $produk->update([
            'nama_produk'  => $request->nama_produk,
            'kategori'     => $request->kategori,
            'supplier'     => $request->supplier,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual,
            'stok_minimum' => $request->stok_minimum,
            'satuan'       => $request->satuan,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function index()
    {
        $product = Product::owned()
                    ->with([
                        'categories' => function($query) {
                            $query->where('status', true);
                        },
                        'suppliers' => function($query) {
                            $query->where('status', true);
                        }
                    ])
                    ->get();

        $category = Category::owned()
                    ->where('status', true)
                    ->get();

        $supplier = Supplier::owned()
                    ->where('status', true)
                    ->get();

        return view('product', compact('product', 'category', 'supplier'));
    }
    
}
