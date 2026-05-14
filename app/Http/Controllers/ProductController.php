<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|unique:products,product_name',
            'selling_price' => 'required|numeric'
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

    public function destroy(int $id)
    {
        $produk = Product::where('user_id', auth()->id())->findOrFail($id);
        $produk->delete();

        return redirect()->back()->with('success', 'Produk Berhasil Dihapus');
    }

    public function update(Request $request, int $id)
    {
        $produk = Product::where('user_id', auth()->id())->findOrFail($id); 
        
         $produk->update([
            'nama_produk'  => $request->nama_produk,
            'kategori'     => $request->kategori,
            'supplier'     => $request->supplier,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual,
            'stok_minimum' => $request->stok_minimum,
            'satuan'       => $request->satuan,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function index()
    {
        $product = Product::where('user_id', auth()->id())->get();

        $category = Category::where('user_id', auth()->id())
                    ->where('status', true)
                    ->get();

        $supplier = Supplier::where('user_id', auth()->id())
                    ->where('status', true)
                    ->get();

        return view('produk', compact('product', 'category', 'supplier'));
    }
    
}
