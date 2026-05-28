<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;


/*
|--------------------
|   Guest Routes 
|--------------------
*/

Route::middleware('guest')->group(function () {
    
    Route::get('/login', [AuthController::class, 'login']);

    Route::post('/login', [AuthController::class, 'processLogin']);

    Route::get('/register', [AuthController::class, 'register']);

    Route::post('/register', [AuthController::class, 'processRegister']);

});

/*
|----------------------------
|   Authenticated Routes
|---------------------------- 
*/

Route::middleware('auth')->group(function () {

    /*
    |-------------------- 
    |   Static Views
    |-------------------- 
    */ 

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::view('/daftar_stok', 'daftar_stok')
        ->name('stok.index');

    Route::view('/pre_order', 'pre_order')
        ->name('preorder.index');

    Route::view('/profile', 'profile')
        ->name('profile');

    /*
    |--------------
    |   Logout 
    |--------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |----------------------
    |   Product Routes
    |----------------------
    */

    Route::prefix('product')->name('product.')->group(function (){

        Route::get('/', [ProductController::class, 'index'])
            ->name('index');

        Route::post('/create', [ProductController::class, 'create'])
            ->name('create');
            
        Route::put('/{product_id}', [ProductController::class, 'update'])
            ->name('update');

        Route::delete('/{product_id}', [ProductController::class, 'delete'])
            ->name('delete');

    });

    /*
    |------------------------
    |   Category Routes
    |------------------------
    */
    
    Route::prefix('category')->name('category.')->group(function (){

        Route::get('/', [CategoriesController::class, 'index'])
            ->name('index');

        Route::post('/create', [CategoriesController::class, 'create'])
            ->name('create');
            
        Route::patch('/{category_id}/status', [CategoriesController::class, 'status'])
            ->name('status');
        
    });

    /*
    |------------------------
    |   Supplier Routes
    |------------------------
    */

    Route::prefix('supplier')->name('supplier.')->group(function (){

        Route::get('/', [SupplierController::class, 'index'])
            ->name('index');

        Route::post('/create', [SupplierController::class, 'create'])
            ->name('create');
            
        Route::put('/{supplier_id}', [SupplierController::class, 'update'])
            ->name('update');
            
        Route::patch('/{supplier_id}/status', [SupplierController::class, 'status'])
            ->name('status');
            
        Route::delete('/{supplier_id}', [SupplierController::class, 'delete'])
            ->name('delete');
            
    });
});
   

