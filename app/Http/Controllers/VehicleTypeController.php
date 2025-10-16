<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * List all vehicle types for a given subcategory
     */
    public function index(SubCategory $subCategory)
    {
        // Eager load mainCategory for route generation in Blade
        $subCategory->load('mainCategory');

        $vehicleTypes = VehicleType::where('sub_category_id', $subCategory->id)
            ->with('subCategory')
            ->get();

        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $vehicleTypes,
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $subCategory,   // Required for route parameters
            'childCategoryType' => 'Vehicle Type',
            'indexRouteName' => 'vehicle-types.index', // Pass explicitly for Blade links
            'showRouteName' => 'vehicle-types.show',   // Pass explicitly
            'type' => 'Vehicle Type',
            'pluralType' => 'Vehicle Types',
        ]);
    }

    public function show(MainCategory $mainCategory, SubCategory $subCategory, VehicleType $vehicleType)
    {
        // Verify relationships
        if ($subCategory->main_category_id !== $mainCategory->id ||
            $vehicleType->sub_category_id !== $subCategory->id) {
            abort(404);
        }

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('sub_category_id', $subCategory->id)
            ->where('vehicle_type_id', $vehicleType->id)
            ->latest()
            ->paginate(15);

        // Child categories for section
        $childCategories = VehicleType::where('sub_category_id', $subCategory->id)
            ->with('subCategory')
            ->get();

        return view('categories.show', [
            'category' => $vehicleType,
            'vehicles' => $vehicles,
            'childCategories' => $childCategories,
            'childCategoryType' => 'Vehicle Type',
            'parentCategory' => $subCategory,
        ]);
    }
}
