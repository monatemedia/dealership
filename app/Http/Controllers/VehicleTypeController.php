<?php // app/Http/Controllers/VehicleTypeController.php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Category;
use App\Models\VehicleType;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    /**
     * Route: /{section:slug}/{category:slug}/vehicle-types
     * Both parameters are route model binding by slug
     */
    public function index(Section $section, Category $category)
    {
        // Verify relationship
        if ($category->section_id !== $section->id) {
            abort(404);
        }

        $category->load('section');
        $vehicleTypes = VehicleType::where('category_id', $category->id)
            ->with('category.section')
            ->get();

        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $vehicleTypes,
            'type' => 'Vehicle Type',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $category,
        ]);
    }

    /**
     * Route: /{section:slug}/{category:slug}/{vehicleType:slug}
     */
    public function show(Section $section, Category $category, VehicleType $vehicleType)
    {
        // Verify relationships
        if ($category->section_id !== $section->id ||
            $vehicleType->category_id !== $category->id) {
            abort(404);
        }

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('category_id', $category->id)
            ->where('vehicle_type_id', $vehicleType->id)
            ->latest()
            ->paginate(15);

        return view('categories.show', [
            'category' => $vehicleType,
            'vehicles' => $vehicles,
            'childCategories' => collect(), // No children for vehicle types
            'childCategoryType' => null,
            'parentCategory' => $category,
        ]);
    }
}
