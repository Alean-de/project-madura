<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function newCategory(Request $request)
    {   
        $request->validate([
            'category_name' => 'required|unique:categories,category_name']);

        Category::create([
            'category_name' => $request->category_name
        ]);
        return redirect()->back()->with('success', 'Kategori Berhasil Dibuat');
    }

    public function updateStatus(Request $request, $category_id)
    {
        $category = Category::findOrFail($category_id);

        $category->update([
            'status' => $request->status
        ]);

        return back();
    }

    public function deleteCategory($category_id)
    {
        $category = Category::findOrFail($category_id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus');
    }

    public function index()
    {
        $category = Category::withCount('products')->get();

        return view('kategori', compact('category'));
    }
}
