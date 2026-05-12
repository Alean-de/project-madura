<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;


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

    Route::get('/supplier', function () {
        return view('supplier');
    });

    Route::post('/produk/simpan', [ProductController::class, 'store'])->name('produk.store');

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/kategori/simpan', [CategoriesController::class, 'newCategory'])->name('kategori.newKategori');

    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');
    
    Route::put('/product{id}', [ProductController::class, 'update'])->name('produk.update');

    Route::patch('/category/{id}/status', [CategoriesController::class, 'updateStatus'])->name('category.updateStatus');
});
   

