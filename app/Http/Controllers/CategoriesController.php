<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function create(Request $request)
    {   
        $request->validate([
            'category_name' => [
                'required',
                'max:255',
            
            Rule::unique('categories')
                ->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ]
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'category_name' => $request->category_name
        ]);
        return redirect()->back()->with('success', 'Kategori Berhasil Dibuat');
    }

    public function status(Request $request, $category_id)
    {
        $category = Category::owned()->findOrFail($category_id);

        $category->update([
            'status' => $request->status
        ]);

        return back();
    }

    public function index()
    {
        $category = Category::owned()
            ->withCount('products')
            ->get();

        // dd($category->pluck('products_count', 'category_name')->toArray());

        return view('category', compact('category'));
    }
    
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
        'category_name' => $request->category_name,
        'product_count' => $request->product_count,
        'status' => $request->status,]);

        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }
}
