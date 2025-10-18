<?php // app/Http/Controllers/HomeController

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $cacheKey = "home-vehicles-page-{$page}";

        $vehicles = Cache::remember($cacheKey, 60, function () use ($page) {
            return Vehicle::where('published_at', '<', now())
                ->with(['primaryImage', 'city', 'vehicleCategory', 'vehicleType', 'fuelType', 'manufacturer', 'model', 'favouredUsers'])
                ->orderBy('published_at', 'desc')
                ->paginate(30, ['*'], 'page', $page);
        });

        // dump($vehicles->toArray());

        // Get three vehicle categories for display
        $categories = MainCategory::take(3)->get();
        // dd($categories);

        return view('home.index', [
            'vehicles' => $vehicles,
            'categories' => $categories,
        ]);
    }
}
