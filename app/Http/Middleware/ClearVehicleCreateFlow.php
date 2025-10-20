<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearVehicleCreateFlow
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('EnsureNotSelectingCategoryUnlessFromVehicleCreate triggered', [
            'route' => $request->route()?->getName(),
            'referer' => $request->headers->get('referer'),
            'session' => session()->all(),
        ]);

        return $next($request);
}
}
