<?php

use App\Http\Controllers\Admin as Adm;
use App\Http\Controllers\Admin\ProductController;

    Route::middleware(['admin'])
            ->name('admin.')->prefix('admin')
            ->group(function () {

        Route::get('/dashboard',function(){
            return view('admin.dashboard');
        })->name('dashboard');

//--------------------- Categoris Routes --------------------------

        Route::resource('/categories',Adm\CategoryController::class);

//--------------------- Products Routes -----------------------------

        Route::resource('/products', Adm\ProductController::class);
        Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage'])->name('products.remove-image');

//------------------------ Orders Routes -----------------------------
        Route::PUT('orders/{order}/updatestatus', [Adm\OrderController::class,'updateStatus'])->name('orders.updatestatus');
        Route::get('orders/product-field', [Adm\OrderController::class, 'getProductField'])->name('orders.product-field');
        Route::resource('/orders', Adm\OrderController::class);


//----------------------------- PromoCodes Routes  ------------------------------
        Route::resource('/promo-codes', Adm\PromoCodeController::class);


   });


