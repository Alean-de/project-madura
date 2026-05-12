<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'selling_price' => 'required|numeric'
        ]);

        Product::create($request->all());
        return redirect()->back()->with('success', 'Produk Berhasil Ditambahkan');
    }

    public function destroy(int $id)
    {
        $produk = Product::findOrFail($id);
        $produk->delete();

        return redirect()->back()->with('success', 'Produk Berhasil Dihapus');
    }

    public function update(Request $request, int $id)
    {
        $produk = Product::findOrFail($id);
        
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
        $product = Product::all();
        $category = Category::all();

        return view('produk', compact('product', 'category'));
    }
    
}
