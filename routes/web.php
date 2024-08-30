<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\homeController;
use App\Http\Controllers\Front\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/', [HomeController::class,'index'])->name('home.index');
Route::get('/contact', [HomeController::class,'contact'])->name('home.contact');
Route::get('/product/details/{id}', [HomeController::class, 'productDetails'])->name('product.details');



//--------------------------- Cart Shopping Routes -----------------------------


Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/details', [CartController::class, 'getCartDetails'])->name('cart.details');
//---------------------------------------------------------------------------------
Route::get('/dashboard', function () {
    return view('front/dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//    ------------------------------------ Wishlist Routes ------------------------

    Route::post('/wishlist/{productId}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});


require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
