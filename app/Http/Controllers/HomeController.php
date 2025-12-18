<?php // app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
// You no longer need to import Cache or LengthAwarePaginator
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // NO DATABASE QUERY FOR VEHICLES IS NEEDED HERE.
        // The front-end (VehicleInstantSearch.js) will handle the initial load
        // by talking directly to Typesense (or your proxy API).

        // Get three vehicle categories for display (This is auxiliary data, caching this small list is optional)
        $categories = Section::take(3)->get();

        // Pass a null or empty array for the vehicles placeholder data
        // since the frontend script will immediately replace it.
        return view('home.index', [
            'vehicles' => null, // or empty array
            'categories' => $categories,
        ]);
    }
}
