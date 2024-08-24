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

        Route::delete('/products/delete-all', [Adm\ProductController::class, 'deleteAll'])->name('products.deleteAll');
         Route::resource('/products', Adm\ProductController::class);
        Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage'])->name('products.remove-image');

//------------------------ Orders Routes -----------------------------

        Route::get('/get-user-discount/{user}', [Adm\OrderController::class, 'getUserDiscount'])->name('get-user-discount');
        Route::PUT('orders/{order}/updatestatus', [Adm\OrderController::class,'updateStatus'])->name('orders.updatestatus');
        Route::get('orders/product-field', [Adm\OrderController::class, 'getProductField'])->name('orders.product-field');
        Route::resource('/orders', Adm\OrderController::class);


//----------------------------- PromoCodes Routes  ------------------------------

         Route::resource('/promo-codes', Adm\PromoCodeController::class);

        //----------------------------- Reports Routes  ------------------------------

        Route::get('/reports', [Adm\SalesReportController::class,'salesReport'])->name('reports.index');

        //----------------------------- Reports Routes  ------------------------------

        Route::get('/customers', [Adm\CustomerController::class,'index'])->name('customers.index');
        Route::get('/customers/{customer}', [Adm\CustomerController::class,'show'])->name('customers.show');
        Route::Put('/customers/vip-customer/{user}', [Adm\CustomerController::class, 'vipCustomer'])->name('customers.vip-customer');
        Route::Put('/customers/vip-all', [Adm\CustomerController::class, 'vipAll'])->name('customers.vipAll');
        Route::Put('/customers/change-status', [Adm\CustomerController::class, 'changeStatus'])->name('customers.changeStatus');



            });


