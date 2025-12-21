<?php // app/Http/Middleware/EnsureNotSelectingCategoryUnlessFromVehicleCreate.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotSelectingCategoryUnlessFromVehicleCreate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        // Pages involved in create flow
        $createFlowRoutes = [
            'vehicle.create',
            'sections.index',
            'section.categories.index',
        ];

        $inFlow = in_array($routeName, $createFlowRoutes);

        // Pull the one-time flag (true if coming from vehicle.create)
        $fromVehicleCreate = session('from_vehicle_create', false);

        if ($inFlow) {
            // keep flags while inside the flow
            // leave them as-is
        } else {
            // left the flow → clear session
            session()->forget(['selecting_category_for_create', 'from_vehicle_create']);
        }

        return $next($request);
    }

}
