<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function create(Request $request)
    {   
        $request->validate([
            'supplier_name' => [
                'required',
                'max:255',

                Rule::unique('suppliers', 'supplier_name')
                ->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
                
            ],

            'contacts' => [
                'required',
            
                Rule::unique('suppliers', 'contacts')
                ->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ]  
        ]);

        Supplier::create([
            'user_id'=> auth()->id(),
            'supplier_name' => $request->supplier_name,
            'contacts' => $request->contacts,
            'city' => $request->city,
        ]);
        return redirect()->back()->with('success', 'Supplier Berhasil Dibuat');
    }

    public function delete($supplier_id)
    {
        $supplier = Supplier::owned()->findOrFail($supplier_id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus');
    }

    public function status(Request $request, $supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);

        $supplier->update([
            'status' => $request->status
        ]);

        return back();
    }

    public function update(Request $request, int $supplier_id)
    {
        $produk = Supplier::owned()->findOrFail($supplier_id);
        
        $produk->update([
            'supplier_name'  => $request->supplier_name,
            'contacts'     => $request->contacts,
            'city'     => $request->city,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Produk berhasil diperbarui!');
    }

     public function index()
    {
        $supplier = Supplier::owned()->get();

        return view('supplier', compact('supplier'));
    }
}
