<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index()
    {
        $category = Category::owned()
            ->withCount('products')
            ->get();

        return view('kategori', compact('category'));
    }

    public function create(Request $request)
    {   
        $request->validate([
            'category_name' => [
                'required',
                'max:255',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ]
        ]);

        $category = Category::create([
            'user_id' => auth()->id(),
            'category_name' => $request->category_name
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $category]);
        }

        return redirect()->back()->with('success', 'Kategori Berhasil Dibuat');
    }

    public function status(Request $request, $category_id)
    {
        $category = Category::owned()->findOrFail($category_id);

        $category->update([
            'status' => $request->status
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
    
    // Parameter diganti ke $category_id agar match sempurna dengan nama di Route induk
    public function update(Request $request, $category_id)
    {
        $category = Category::owned()->findOrFail($category_id);

        $category->update([
            'category_name' => $request->category_name,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }
}