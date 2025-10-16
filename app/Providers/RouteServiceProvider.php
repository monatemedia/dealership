<?php

namespace App\Providers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\VehicleType;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        // -------------------------------
        // Scoped Route Bindings
        // -------------------------------

        // Bind SubCategory under MainCategory
        Route::bind('subCategory', function ($value, $route) {
            $mainCategorySlug = $route->parameter('mainCategory');
            $mainCategory = MainCategory::where('slug', $mainCategorySlug)->firstOrFail();
            return SubCategory::where('slug', $value)
                ->where('main_category_id', $mainCategory->id)
                ->firstOrFail();
        });

        // Bind VehicleType under SubCategory
        Route::bind('vehicleType', function ($value, $route) {
            $subCategorySlug = $route->parameter('subCategory');
            $subCategory = SubCategory::where('slug', $subCategorySlug)->firstOrFail();
            return VehicleType::where('slug', $value)
                ->where('sub_category_id', $subCategory->id)
                ->firstOrFail();
        });
    }
}
