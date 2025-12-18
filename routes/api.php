<?php // routes/api.php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ModelController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\VehicleSearchController;
use App\Http\Controllers\AwsSnsWebhookController;
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
// ⚠️ MOVED TO web.php - InstantSearch needs session support for authenticated users to use watchlist info
// The /api/vehicles/search route is now in routes/web.php
// Route::get('/vehicles/search', [VehicleSearchController::class, 'instantSearch'])
//     ->name('api.vehicle.search');

Route::get('/vehicles/filter-options', [VehicleSearchController::class, 'getFilterOptions'])
    ->name('api.vehicles.filter-options');

Route::get('/vehicles/cities/{provinceId}', [VehicleSearchController::class, 'getCitiesByProvince'])
    ->name('api.vehicles.cities');

// -------------------------------
// API ROUTES FOR STATIC SEARCH
// -------------------------------
Route::get('/subcategories-by-section/{sectionId}', [VehicleSearchController::class, 'getSubcategoriesBySection']);
Route::get('/vehicle-types-by-sub/{subcategoryId}', [VehicleSearchController::class, 'getVehicleTypesBySubcategory']);
Route::get('/fuel-types-by-sub/{subcategoryId}', [VehicleSearchController::class, 'getFuelTypesBySubcategory']);

// -------------------------------
// MAXIMUM RANGE FOR SLIDER
// -------------------------------
Route::get('/vehicles/max-range/{cityId}', [VehicleSearchController::class, 'getMaxRange']);

// -------------------------------
// AWS SES WEBHOOK FOR EMAIL EVENTS
// -------------------------------
// Route::post('/aws/ses-events', [AwsSnsWebhookController::class, 'handle'])
//     ->name('aws.ses.webhook');
