<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryAdjustmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;




/*
|--------------------
|   Guest Routes 
|--------------------
*/

Route::middleware('guest')->group(function () {
    
    Route::get('/', [AuthController::class, 'login']);

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

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
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

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    /*
    |------------------------------
    |   Purchase Order Routes
    |------------------------------
    */

    Route::prefix('purchaseorder')->name('po.')->group(function (){

        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::post('/store', [PurchaseOrderController::class, 'store'])->name('store');
        Route::put('/store', [PurchaseOrderController::class, 'store'])->name('update');
        Route::delete('/store', [PurchaseOrderController::class, 'store'])->name('delete');
        Route::patch('/purchase-orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])
            ->name('po.updateStatus');

    });

    /*
    |
    |       Stock Routes
    |
    */

    Route::prefix('daftar_stok')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
    });


    /*
    |--------------------------------------------------------------------------
    | Inventory Adjustment Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('inventoryadjustment')->name('adjustment.')->group(function () {

        Route::get('/', [InventoryAdjustmentController::class, 'index'])->name('index');
        Route::post('/store', [InventoryAdjustmentController::class, 'store'])->name('store');
        Route::put('/update/{id}', [InventoryAdjustmentController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [InventoryAdjustmentController::class, 'destroy'])->name('destroy');
        Route::patch('/update-status/{id}', [InventoryAdjustmentController::class, 'updateStatus']);

    });

        /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/username', [ProfileController::class, 'updateUsername'])->name('update-username');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::put('/avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
    });
    

});
   

