<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/car/search', [CarController::class, 'search'])
    ->name('car.search');

Route::middleware(['guest'])->group(function () {
    Route::get('/signup', [SignupController::class, 'create'])->name('signup');
    Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['auth'])->group(function () {
    // Create nested middleware route group
    Route::middleware(['verified'])->group(function () {
        Route::get('/car/watchlist', [CarController::class, 'watchlist'])
            ->name('car.watchlist');
        Route::resource('car', CarController::class)->except(['show']);
        Route::get('/car/{car}/images', [CarController::class, 'carImages'])
            ->name('car.images');
        Route::put('/car/{car}/images', [CarController::class, 'updateImages'])
            ->name('car.updateImages');
        Route::post('/car/{car}/images', [CarController::class, 'addImages'])
            ->name('car.addImages');
    });
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});

Route::get('/car/{car}', [CarController::class, 'show'])->name('car.show');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPassword'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPassword'])
    ->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');

Route::get('/email/verify/{id}/{hash}', [EmailVerifyController::class, 'verify'])
    // Ensure the user is authenticated and the link is signed
    ->middleware(['auth', 'signed'])
    // This route is used when the user clicks on the verification link in the email
    // The 'signed' middleware ensures the link is valid and not tampered with
    ->name('verification.verify');
Route::get('/email/verify', [EmailVerifyController::class, 'notice'])
    // This route is used to show the verification notice page
    ->middleware('auth')
    // This route is used when we setup the verified middleware
    // so that only verified users are able to access certain routes
    ->name('verification.notice');
Route::post('/email/verification-notification', [EmailVerifyController::class, 'send'])
    // This route is used to resend the verification email
    // It is called when the user loses his/her verification link
    // or wants to resend the verification email
    // The 'throttle' middleware limits the number of requests to this route
    // to 6 requests per minute to prevent abuse
    ->middleware(['auth', 'throttle:6,1'])
    // This route is used to send the verification email
    // It is called when the user clicks on the "Resend Verification Email" button
    // in the verification notice page
    ->name('verification.send');

Route::get('/login/oauth/{provider}', [SocialiteController::class, 'redirectToProvider'])
    ->name('login.oauth');
Route::get('/callback/oauth/{provider}', [SocialiteController::class, 'handleCallback']);
