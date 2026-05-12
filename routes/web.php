<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;

Route::middleware('guest')->group(function () {
    
    Route::get('/login', [AuthController::class, 'login'])
    ->name('login');
    Route::post('/login', [AuthController::class, 'processlogin']);

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

    Route::get('/kategori', function () {
        return view('kategori');
    });

    Route::get('/pre_order', function () {
        return view('pre_order');
    });

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

    Route::get('/profile', function () {
        return view('profile');
    });

    Route::get('/supplier', function () {
        return view('supplier');
    });

    Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.store');

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    
    Route::put('/product{id}', [ProdukController::class, 'update'])->name('produk.update');
});
   

