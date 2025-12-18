<?php // app/Http/Controllers/SubcategoryController.php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subcategory;
use App\Models\Vehicle;
use App\Models\VehicleType;

class SubcategoryController extends Controller
{
    /**
     * Show all sub-categories for a section
     * Route: /{section}/sub-categories
     */
    public function index(Section $section)
    {
        $subcategories = Subcategory::with('section')
            ->where('section_id', $section->id)
            ->get();

        $selectingForCreate = session('selecting_category_for_create', false);

        return view('categories.index', [
            'categories' => $subcategories,
            'type' => 'Subcategory',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $section,
        ]);
    }

    public function show(Section $section, Subcategory $subcategory)
    {
        $subcategory->load('section');

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('subcategory_id', $subcategory->id)
            ->latest()
            ->paginate(15);

        $vehicleTypes = VehicleType::where('subcategory_id', $subcategory->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $subcategory,
            'vehicles' => $vehicles,
            'childCategories' => $vehicleTypes,
            'childCategoryType' => 'Vehicle Type',
            'parentCategory' => $subcategory, // Pass subcategory as parent for button
        ]);
    }
}
