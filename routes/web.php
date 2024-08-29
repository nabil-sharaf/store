<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\homeController;
use App\Http\Controllers\Front\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/', [HomeController::class,'index'])->name('home.index');
Route::get('/product/details/{id}', [HomeController::class, 'productDetails'])->name('product.details');


//--------------------------- Cart Shopping Routes -----------------------------

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

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
