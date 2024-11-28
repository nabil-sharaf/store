<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\homeController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Middleware\CheckMaintenanceMode;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [CheckMaintenanceMode::class] ,
], function () {

Route::get('/', [HomeController::class,'index'])->name('home.index');
Route::get('/contact', [HomeController::class,'contact'])->name('home.contact');
Route::get('/about', [HomeController::class,'aboutUs'])->name('home.about');
Route::get('product/details/{id}', [HomeController::class, 'productDetails'])->name('product.details');
Route::get('/variant/details/{id}', [HomeController::class, 'getVariantDetails'])->name('home.variant.details');


//--------------------------- Cart Shopping Routes -----------------------------


Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update'); // راوت تحديث الكمية في صفحة السلة
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/remove-item', [CartController::class, 'removeShopingItemCart'])->name('cart.remove-item');
Route::get('/cart/details', [CartController::class, 'getCartDetails'])->name('cart.details');  // تفاصيل السلة في السايد مينيو

Route::get('/cart/shop-cart/{order?}',[CartController::class,'shoppingCartDetails'])->name('home.shop-cart'); //جلب المحتويات والتفاصيل  في صفحة السلة

//---------------------------------------------------------------------------------


//-----------------------------------checkout Routes-----------------------------------------

Route::get('/shop/checkout',[OrderController::class,'index'])->name('checkout.index');
Route::get('/shop/checkout/{order}',[OrderController::class,'indexEdit'])->name('checkout.indexEdit');
Route::post('/shop/checkout',[OrderController::class,'store'])->name('checkout.store');
Route::put('/shop/checkout/{order}',[OrderController::class,'update'])->name('checkout.update');
Route::post('/checkout/coupon',[OrderController::class,'checkCoupon'])->name('checkout.promo');
Route::get('order/show/{order}',[OrderController::class,'show'])->name('order.show');
Route::get('order/edit/{order}',[OrderController::class,'edit'])->name('order.edit');
Route::get('/orders/clear-cart', [OrderController::class, 'clearCartSession'])->name('orders.clear-cart');
Route::get('/get-shipping-cost/{state}', [OrderController::class, 'getShippingCost'])->name('checkout.getShippingCost');

//-------------------------------------------------------------------------------------------

Route::get('/dashboard', function () {
    return view('front/dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.Update');
    Route::post('/profile', [ProfileController::class, 'updateAddress'])->name('profile.updateAddress');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//    ------------------------------------ Wishlist Routes ------------------------

    Route::post('/wishlist/{productId}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

//    ------------------------------------ Category & Products Routes ------------------------

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'categoryProducts'])->name('category.show');
    Route::get('/products-offers', [ProductController::class, 'index'])->name('products.offers');
    Route::get('/products/{product}', [ProductController::class, 'showProduct'])->name('product.show');
    Route::get('/search', [ProductController::class, 'search'])->name('product.search');
    Route::get('/search/filter', [ProductController::class, 'filterProducts'])->name('products.filter');
    Route::get('/category/{category_id}/filter', [ProductController::class, 'filterProducts'])->name('category.filter');



});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
