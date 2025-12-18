<?php // app/Http/Controllers/VehicleTypeController.php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subcategory;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * Route: /{section:slug}/{subcategory:slug}/vehicle-types
     * Both parameters are route model binding by slug
     */
    public function index(Section $section, Subcategory $subcategory)
    {
        // Verify relationship
        if ($subcategory->section_id !== $section->id) {
            abort(404);
        }

        $subcategory->load('section');
        $vehicleTypes = VehicleType::where('subcategory_id', $subcategory->id)
            ->with('subcategory.section')
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
     * Route: /{section:slug}/{subcategory:slug}/{vehicleType:slug}
     */
    public function show(Section $section, Subcategory $subcategory, VehicleType $vehicleType)
    {
        // Verify relationships
        if ($subcategory->section_id !== $section->id ||
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
