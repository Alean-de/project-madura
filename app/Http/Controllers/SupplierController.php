<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function newSupplier(Request $request)
    {   
        $request->validate([
            'supplier_name' => 'required',
            'contacts' =>'required'   
        ]);

        Supplier::create([
            'user_id'=> auth()->id(),
            'supplier_name' => $request->supplier_name,
            'contacts' => $request->contacts,
            'city' => $request->city,
        ]);
        return redirect()->back()->with('success', 'Supplier Berhasil Dibuat');
    }

    public function deleteSupplier($supplier_id)
    {
        $supplier = Supplier::where('user_id', auth()->id())->findOrFail($supplier_id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus');
    }

    public function updateStatus(Request $request, $supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);

        $supplier->update([
            'status' => $request->status
        ]);

        return back();
    }

    public function updateData(Request $request, int $supplier_id)
    {
        $produk = Supplier::where('user_id', auth()->id())->findOrFail($supplier_id);
        
        $produk->update([
            'supplier_name'  => $request->supplier_name,
            'contacts'     => $request->contacts,
            'city'     => $request->city,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Produk berhasil diperbarui!');
    }

     public function index()
    {
        $supplier = Supplier::where('user_id', auth()->id())->get();

        return view('supplier', compact('supplier'));
    }
}
