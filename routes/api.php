<?php // routes/api.php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ModelController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\VehicleSearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes are automatically prefixed with /api
| All routes automatically use the 'api' middleware group
*/

// Manufacturers
Route::prefix('manufacturers')->group(function () {
    Route::get('/search', [ManufacturerController::class, 'search'])
        ->name('api.manufacturers.search');
    Route::get('/{id}', [ManufacturerController::class, 'show'])
        ->name('api.manufacturers.show');
});

// Models
Route::prefix('models')->group(function () {
    Route::get('/search', [ModelController::class, 'search'])
        ->name('api.models.search');
    Route::get('/{id}', [ModelController::class, 'show'])
        ->name('api.models.show');
});

// Provinces
Route::prefix('provinces')->group(function () {
    Route::get('/search', [ProvinceController::class, 'search'])
        ->name('api.provinces.search');
    Route::get('/{id}', [ProvinceController::class, 'show'])
        ->name('api.provinces.show');
});

// Cities
Route::prefix('cities')->group(function () {
    Route::get('/search', [CityController::class, 'search'])
        ->name('api.cities.search');
    Route::get('/{id}', [CityController::class, 'show'])
        ->name('api.cities.show');
});

// -------------------------------
// INSTANT SEARCH
// -------------------------------
// Fix: Remove duplicate /api prefix (already added automatically)
Route::get('/vehicles/search', [VehicleSearchController::class, 'instantSearch'])
    ->name('api.vehicle.search');

Route::get('/vehicles/filter-options', [VehicleSearchController::class, 'getFilterOptions'])
    ->name('api.vehicles.filter-options');

Route::get('/vehicles/cities/{provinceId}', [VehicleSearchController::class, 'getCitiesByProvince'])
    ->name('api.vehicles.cities');
