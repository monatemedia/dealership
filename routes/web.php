<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/car/search', [CarController::class, 'search'])
    ->name('car.search');
Route::get('/car/watchlist', [CarController::class, 'watchlist'])
    ->name('car.watchlist');
Route::resource('car', CarController::class);
Route::get('/car/{car}/images', [CarController::class, 'carImages'])
    ->name('car.images');
Route::put('/car/{car}/images', [CarController::class, 'updateImages'])
    ->name('car.updateImages');

Route::get('/signup', [SignupController::class, 'create'])->name('signup');
Route::get('/login', [LoginController::class, 'create'])->name('login');
