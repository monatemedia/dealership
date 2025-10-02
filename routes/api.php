<?php // routes/api.php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ModelController;
use App\Http\Controllers\Api\ProvinceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Manufacturer routes
Route::get('/manufacturers/search', [ManufacturerController::class, 'search'])->name('api.manufacturers.search');
Route::get('/manufacturers/{id}', [ManufacturerController::class, 'show'])->name('api.manufacturers.show');

// Model routes
Route::get('/models/search', [ModelController::class, 'search'])->name('api.models.search');
Route::get('/models/{id}', [ModelController::class, 'show'])->name('api.models.show');

// Province routes
Route::get('/provinces/search', [ProvinceController::class, 'search'])->name('api.provinces.search');
Route::get('/provinces/{id}', [ProvinceController::class, 'show'])->name('api.provinces.show');

// City routes
Route::get('/cities/search', [CityController::class, 'search'])->name('api.cities.search');
Route::get('/cities/{id}', [CityController::class, 'show'])->name('api.cities.show');
