<?php
// app/Http/Controllers/VehicleTypeController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * Show vehicles filtered by sub-category and vehicle type
     * Route: /{subCategory:slug}/{vehicleType:slug}
     */
    public function show(SubCategory $subCategory, VehicleType $vehicleType)
    {
        // Verify the vehicle type belongs to this sub-category
        if ($vehicleType->sub_category_id !== $subCategory->id) {
            abort(404);
        }

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('sub_category_id', $subCategory->id)
            ->where('vehicle_type_id', $vehicleType->id)
            ->latest()
            ->paginate(15);

        $mainCategories = MainCategory::take(3)->get();

        return view('vehicle-types.show', [
            'vehicleType' => $vehicleType,
            'subCategory' => $subCategory,
            'mainCategory' => $subCategory->mainCategory,
            'vehicles' => $vehicles,
            'categories' => $mainCategories,
        ]);
    }
}
