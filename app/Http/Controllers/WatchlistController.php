<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $vehicles = Auth::user()
            ->favouriteVehicles()
            ->with(['primaryImage', 'city', 'vehicleType', 'fuelType', 'manufacturer', 'model'])
            ->paginate(15);
        return view('watchlist.index', ['vehicles' => $vehicles]);
    }
    public function storeDestroy(Vehicle $vehicle)
    {
        // Get the authenticated user
        $user = Auth::user();
        // Check if the current vehicle is already added into favourite vehicles
        $vehicleExists = $user->favouriteVehicles()->where('vehicle_id', $vehicle->id)->exists();
        // Remove if it exists
        if ($vehicleExists) {
            $user->favouriteVehicles()->detach($vehicle);
            return response()->json([
                'added' => false,
                'message' => 'Vehicle was removed from watchlist'
            ]);
        }
        // Add the vehicle into favourite vehicles of the user
        $user->favouriteVehicles()->attach($vehicle);
        return response()->json([
            'added' => true,
            'message' => 'Vehicle was added to watchlist'
        ]);
    }
}
