<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $cacheKey = "home-cars-page-{$page}";

        $cars = Cache::remember($cacheKey, 60, function () use ($page) {
            return Car::where('published_at', '<', now())
                ->with(['primaryImage', 'city', 'carType', 'fuelType', 'manufacturer', 'model', 'favouredUsers'])
                ->orderBy('published_at', 'desc')
                ->paginate(30, ['*'], 'page', $page);
        });

        return view('home.index', ['cars' => $cars]);
    }
}
