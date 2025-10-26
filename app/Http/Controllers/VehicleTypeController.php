<?php // app/Http/Controllers/VehicleTypeController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * Route: /{mainCategory:slug}/{subcategory:slug}/vehicle-types
     * Both parameters are route model binding by slug
     */
    public function index(MainCategory $mainCategory, Subcategory $subcategory)
    {
        // Verify relationship
        if ($subcategory->main_category_id !== $mainCategory->id) {
            abort(404);
        }

        $subcategory->load('mainCategory');

        $vehicleTypes = VehicleType::where('subcategory_id', $subcategory->id)
            ->with('subcategory.mainCategory')
            ->get();

        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $vehicleTypes,
            'type' => 'Vehicle Type',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $subcategory,
        ]);
    }

    /**
     * Route: /{mainCategory:slug}/{subcategory:slug}/{vehicleType:slug}
     */
    public function show(MainCategory $mainCategory, Subcategory $subcategory, VehicleType $vehicleType)
    {
        // Verify relationships
        if ($subcategory->main_category_id !== $mainCategory->id ||
            $vehicleType->subcategory_id !== $subcategory->id) {
            abort(404);
        }

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('subcategory_id', $subcategory->id)
            ->where('vehicle_type_id', $vehicleType->id)
            ->latest()
            ->paginate(15);

        return view('categories.show', [
            'category' => $vehicleType,
            'vehicles' => $vehicles,
            'childCategories' => collect(), // No children for vehicle types
            'childCategoryType' => null,
            'parentCategory' => $subcategory,
        ]);
    }
}
