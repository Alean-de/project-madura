<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;


Route::middleware('guest')->group(function () {
    
    Route::get('/login', [AuthController::class, 'login'])
    ->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);

    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'processRegister']);

});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/daftar_stok', function () {
        return view('daftar_stok');
    });

    Route::get('/kategori', [CategoriesController::class, 'index'])->name('kategori.index');

    Route::get('/pre_order', function () {
        return view('pre_order');
    });

    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');

    Route::get('/profile', function () {
        return view('profile');
    });

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');

    Route::post('/produk/simpan', [ProductController::class, 'store'])->name('produk.store');

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/kategori/simpan', [CategoriesController::class, 'newCategory'])->name('kategori.newKategori');

    Route::post('/supplier/simpan', [SupplierController::class, 'newSupplier'])->name('supplier.newSupplier');

    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');
    Route::delete('/category/{category_id}', [CategoriesController::class, 'deleteCategory'])->name('category.deleteCategory');
    Route::delete('/supplier/{supplier_id}', [SupplierController::class, 'deleteSupplier'])->name('category.deleteSupplier');
    
    Route::put('/product{id}', [ProductController::class, 'update'])->name('produk.update');
    Route::put('/supplier{id}', [SupplierController::class, 'updateData'])->name('supplier.updateData');

    Route::patch('/category/{id}/status', [CategoriesController::class, 'updateStatus'])->name('category.updateStatus');
    Route::patch('/supplier/{id}/status', [SupplierController::class, 'updateStatus'])->name('supplier.updateStatus');
});
   

