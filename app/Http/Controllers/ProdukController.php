<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga_jual' => 'required|numeric'
        ]);

        Produk::create($request->all());
        return redirect()->back()->with('success', 'Produk Berhasil Ditambahkan');
    }

    public function destroy(int $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->back()->with('success', 'Produk Berhasil Dihapus');
    }

    public function update(Request $request, int $id)
    {
        $produk = Produk::findOrFail($id);
        
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
        $produk = Produk::all();
        return view('produk', compact('produk'));
    }
    
}
