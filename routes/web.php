<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\homeController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\WishlistController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

Route::get('/', [HomeController::class,'index'])->name('home.index');
Route::get('/contact', [HomeController::class,'contact'])->name('home.contact');
Route::get('/about', [HomeController::class,'aboutUs'])->name('home.about');
Route::get('/product/details/{id}', [HomeController::class, 'productDetails'])->name('product.details');



//--------------------------- Cart Shopping Routes -----------------------------


Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/details', [CartController::class, 'getCartDetails'])->name('cart.details');

Route::get('/cart/shop-cart',[CartController::class,'shoppingCartDetails'])->name('home.shop-cart');
//---------------------------------------------------------------------------------

//-----------------------------------checkout Routes-----------------------------------------

Route::get('/shop/checkout',[CheckoutController::class,'index'])->name('checkout.index');

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
    Route::get('/products', [ProductController::class, 'index'])->name('products.all');
    Route::get('/products/{product}', [ProductController::class, 'showProduct'])->name('product.show');
    Route::get('/search', [ProductController::class, 'search'])->name('product.search');




});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
