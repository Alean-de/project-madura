<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::owned();

        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('supplier_name', 'like', "%{$request->search}%")
                    ->orWhere('contacts', 'like', "%{$request->search}%")
                    ->orWhere('city', 'like', "%{$request->search}%");
            });
        });

        $suppliers = $query->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);
        }

        return view('supplier');
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|max:255',
            'contacts' => 'required|max:20',
            'city' => 'required|max:255',
        ]);

        $supplier = Supplier::create([
            ...$validated,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan',
            'data' => $supplier
        ]);
    }

    public function delete(int $supplier_id)
    {
        $supplier = Supplier::owned()->findOrFail($supplier_id);

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil dihapus'
        ]);
    }

    public function status(Request $request, int $supplier_id)
    {
        $supplier = Supplier::owned()->findOrFail($supplier_id);

        $supplier->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }

   public function update(Request $request, int $supplier_id)
    {
        $supplier = Supplier::owned()->findOrFail($supplier_id);

        $validated = $request->validate([
            'supplier_name' => 'required|max:255',
            'contacts' => 'required|max:20',
            'city' => 'required|max:255',
        ]);

        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil diperbarui',
            'data' => $supplier
        ]);
    }

}
