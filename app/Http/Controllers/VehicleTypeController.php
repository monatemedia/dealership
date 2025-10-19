<?php // app/Http/Controllers/VehicleTypeController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * Route: /{mainCategory:slug}/{subCategory:slug}/vehicle-types
     * Both parameters are route model binding by slug
     */
    public function index(MainCategory $mainCategory, SubCategory $subCategory)
    {
        // Verify relationship
        if ($subCategory->main_category_id !== $mainCategory->id) {
            abort(404);
        }

        $subCategory->load('mainCategory');

        $vehicleTypes = VehicleType::where('sub_category_id', $subCategory->id)
            ->with('subCategory.mainCategory')
            ->get();

        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $vehicleTypes,
            'type' => 'Vehicle Type',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $subCategory,
        ]);
    }

    /**
     * Route: /{mainCategory:slug}/{subCategory:slug}/{vehicleType:slug}
     */
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

        return view('categories.show', [
            'category' => $vehicleType,
            'vehicles' => $vehicles,
            'childCategories' => collect(), // No children for vehicle types
            'childCategoryType' => null,
            'parentCategory' => $subCategory,
        ]);
    }
}
