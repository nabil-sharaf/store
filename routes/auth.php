<?php

use App\Http\Controllers\Auth\Admin ;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


/*-------------------------------------------------------
 *                  Start Users Auth Routes
 *---------------------------------------------------------
 */
Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});


/*-------------------------------------------------------
 *                  Start Admin Auth Routes
 *---------------------------------------------------------
 */

Route::prefix('admin')->name('admin.')->
   namespace('App\Http\Controllers\Auth\Admin')->
   middleware('guest.admin')->group(function () {

    Route::get('register', [Admin\RegisteredAdminController::class, 'create'])
                ->name('register');
    Route::post('register', [Admin\RegisteredAdminController::class, 'store']);

    Route::get('/login', [Admin\AdminAuthController::class, 'showLoginForm'])->name('login');

    Route::post('/login', [Admin\AdminAuthController::class, 'login'])->name('login.submit');

    Route::get('forgot-password', [Admin\PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [Admin\PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [Admin\NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [Admin\NewPasswordController::class, 'store'])
                ->name('password.store');


});

Route::prefix('admin')->name('admin.')->
  middleware('admin')->group(function () {

    Route::post('logout', [Admin\AdminAuthController::class, 'logout'])
                ->name('logout');
});
});
