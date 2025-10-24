<?php // app/Http/Controllers/VehicleTypeController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * Route: /{mainCategory:slug}/{subCategory:slug}/vehicle-types
     * Both parameters are route model binding by slug
     */
    public function index(MainCategory $mainCategory, Subcategory $subCategory)
    {
        // Verify relationship
        if ($subCategory->main_category_id !== $mainCategory->id) {
            abort(404);
        }

        $subCategory->load('mainCategory');

        $vehicleTypes = VehicleType::where('subcategory_id', $subCategory->id)
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
    public function show(MainCategory $mainCategory, Subcategory $subCategory, VehicleType $vehicleType)
    {
        // Verify relationships
        if ($subCategory->main_category_id !== $mainCategory->id ||
            $vehicleType->subcategory_id !== $subCategory->id) {
            abort(404);
        }

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('subcategory_id', $subCategory->id)
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
