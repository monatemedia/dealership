<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotSelectingCategoryUnlessFromVehicleCreate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (in_array($routeName, ['main-categories.index', 'main-category.sub-categories.index'])) {

            // Only reset if selecting_category_for_create is set
            if (session('selecting_category_for_create', false)) {

                // Pull the one-time flag (true if coming from vehicle.create)
                $fromVehicleCreate = session()->pull('from_vehicle_create', false);

                // If not coming from vehicle.create, reset the session
                if (! $fromVehicleCreate) {
                    session(['selecting_category_for_create' => false]);
                }
            }
        }

        return $next($request);
    }
}
