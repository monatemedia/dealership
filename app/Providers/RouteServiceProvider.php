<?php // app/Providers/RouteServiceProvider.php
namespace App\Providers;

use App\Models\Section;
use App\Models\Subcategory;
use App\Models\VehicleType;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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

        /**
         * Section Binding
         * Resolve Section by slug first
         */
        Route::bind('section', function ($value) {
            return Section::where('slug', $value)->firstOrFail();
        });

        /**
         * Subcategory Binding
         * Bind Subcategory under Section
         * The Section parameter is now already resolved to a model
         */
        Route::bind('subcategory', function ($value, $route) {
            // Get the already-resolved Section model
            $section = $route->parameter('section');

            // If Section is still a string (shouldn't happen but safety check)
            if (is_string($section)) {
                $section = Section::where('slug', $section)->firstOrFail();
            }

            return Subcategory::where('slug', $value)
                ->where('section_id', $section->id)
                ->firstOrFail();
        });

        /**
         * VehicleType Binding
         * Bind VehicleType under Subcategory
         * The subcategory parameter is now already resolved to a model
         */
        Route::bind('vehicleType', function ($value, $route) {
            // Get the already-resolved Subcategory model
            $subcategory = $route->parameter('subcategory');

            // If subcategory is still a string (shouldn't happen but safety check)
            if (is_string($subcategory)) {
                $subcategory = Subcategory::where('slug', $subcategory)->firstOrFail();
            }

            return VehicleType::where('slug', $value)
                ->where('subcategory_id', $subcategory->id)
                ->firstOrFail();
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });
    }
}
