<?php

use App\Http\Controllers\Admin as Adm;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Middleware\Admin\CheckRole;

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    Route::middleware(['admin'])
        ->name('admin.')->prefix('admin')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

//--------------------- Categoris Routes --------------------------

            Route::resource('/categories', Adm\CategoryController::class)->middleware('checkRole:superAdmin&supervisor');

//--------------------- Products Routes -----------------------------

            Route::delete('/products/delete-all', [Adm\ProductController::class, 'deleteAll'])->name('products.deleteAll');
            Route::PUT('/products/trend-all', [Adm\ProductController::class, 'trendAll'])->name('products.trendAll');
            Route::PUT('/products/best-all', [Adm\ProductController::class, 'bestSellerAll'])->name('products.bestSellerAll');
            Route::resource('/products', Adm\ProductController::class);
            Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage'])->name('products.remove-image');

//------------------------ Orders Routes -----------------------------

            Route::get('/get-user-discount/{user}', [Adm\OrderController::class, 'getUserDiscount'])->name('get-user-discount');
            Route::PUT('orders/{order}/updatestatus', [Adm\OrderController::class, 'updateStatus'])->name('orders.updatestatus');
            Route::get('orders/product-field', [Adm\OrderController::class, 'getProductField'])->name('orders.product-field');
            Route::resource('/orders', Adm\OrderController::class);
            Route::post('/orders/check-coupon', [Adm\OrderController::class, 'checkCoupon'])->name('check-promo-code');
            Route::post('/orders/update-coupon', [Adm\OrderController::class, 'updateCopoun'])->name('update-promo-code');
            Route::get('/get-shipping-cost/{state}', [Adm\OrderController::class, 'getShippingCost'])->name('checkout.getShippingCost');
            Route::get('/get-free-quantity', [Adm\OrderController::class, 'getFreeQuantity'])->name('orders.free-quantity');


            Route::middleware("checkRole:superAdmin&supervisor")->group(function () {

                //----------------------------- PromoCodes Routes  ------------------------------

                Route::resource('/promo-codes', Adm\PromoCodeController::class);

                //----------------------------- Reports Routes  ------------------------------

                Route::get('/reports', [Adm\SalesReportController::class, 'salesReport'])->name('reports.index');

                //----------------------------- Customers Routes  ------------------------------
                Route::middleware([CheckRole::class . ":superAdmin"])->group(function () {

                    Route::get('/customers', [Adm\CustomerController::class, 'index'])->name('customers.index');

                    Route::get('/customers/{user}', [Adm\CustomerController::class, 'show'])->name('customers.show');

                    Route::get('/customers/{user}/edit', [Adm\CustomerController::class, 'edit'])->name('customers.edit');

                    Route::put('/customers/{user}', [Adm\CustomerController::class, 'update'])->name('customers.update');

                    Route::post('/customers/vip-customer/{user}', [Adm\CustomerController::class, 'vipCustomer'])->name('customers.vip-customer');

                    Route::post('/customers/vip-all', [Adm\CustomerController::class, 'vipAll'])->name('customers.vipAll');

                    Route::Put('/customers/change-status/{user}', [Adm\CustomerController::class, 'changeStatus'])->name('customers.changeStatus');

                    Route::post('/customers/vip-disable/{user}', [Adm\CustomerController::class, 'vipDisable'])->name('customer.vip.disable');
                });

                Route::get('/get-user-address/{id}', [Adm\CustomerController::class, 'getUserAddress'])->name('get-customer-address')->withoutMiddleware([CheckRole::class]);;


//----------------------------- Modrators Routes  ------------------------------

                Route::middleware([CheckRole::class . ":superAdmin"])->group(function () {
                    Route::get('/moderators', [Adm\ModeratorController::class, 'index'])->name('moderators.index');
                    Route::get('/moderators/create', [Adm\ModeratorController::class, 'create'])->name('moderators.create');
                    Route::get('/moderators/{moderator}', [Adm\ModeratorController::class, 'show'])->name('moderators.show');
                    Route::post('/moderators', [Adm\ModeratorController::class, 'store'])->name('moderators.store');
                    Route::get('/moderators/edit/{moderator}', [Adm\ModeratorController::class, 'edit'])->name('moderators.edit');
                    Route::put('/moderators/edit/{moderator}', [Adm\ModeratorController::class, 'update'])->name('moderators.update');
                    Route::delete('/moderators/delete/{moderator}', [Adm\ModeratorController::class, 'destroy'])->name('moderators.destroy');
                });

            });

            Route::get('/account/{moderator}', [Adm\ModeratorController::class, 'myAccount'])->name('account.show')->withoutMiddleware([CheckRole::class]);
            Route::get('/account/edit/{moderator}', [Adm\ModeratorController::class, 'editAccount'])->name('account.edit')->withoutMiddleware([CheckRole::class]);
            Route::put('/account/{moderator}', [Adm\ModeratorController::class, 'updateAccount'])->name('account.update')->withoutMiddleware([CheckRole::class]);


//----------------------------- Settings Routes  ------------------------------
            Route::middleware([CheckRole::class . ":superAdmin&supervisor"])->group(function () {
                Route::get('/settings', [Adm\SettingContoller::class, 'edit'])->name('settings.edit');
                Route::post('/settings', [Adm\SettingContoller::class, 'update'])->name('settings.update');

                Route::get('/settings/images', [Adm\SiteImagesController::class, 'images'])->name('settings.images');
                Route::PUT('/settings/images/update', [Adm\SiteImagesController::class, 'updateImages'])->name('settings.update-images');

                //----------------------------- ShippingRates Routes  ------------------------------
                Route::get('/settings/shipping-rates', [ShippingRateController::class, 'index'])->name('shipping-rates.index');
                Route::post('/settings/shipping-rates/{id}', [ShippingRateController::class, 'update'])->name('shipping-rates.update');
                Route::post('/settings/shipping-rates', [ShippingRateController::class, 'store'])->name('shipping-rates.store');
                Route::delete('/settings/shipping-rates/{id}', [ShippingRateController::class, 'destroy'])->name('shipping-rates.destroy');

                //----------------------------- Offers Routes  ------------------------------

                Route::get('offers/popup', [PopupController::class, 'index'])->name('popup.index');
                Route::put('offers/popup', [PopupController::class, 'update'])->name('popup.update');

                Route::resource('offers', OfferController::class);

                //----------------------------- Prefixes Routes  ------------------------------

                Route::resource('prefixes', Adm\PrefixController::class);

            });

        });

});
