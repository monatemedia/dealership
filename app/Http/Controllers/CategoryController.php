<?php // app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Category;
use App\Models\Vehicle;
use App\Models\VehicleType;

class CategoryController extends Controller
{
    /**
     * Show all categories for a section
     * Route: /{section}/categories
     */
    public function index(Section $section)
    {
        $categories = Category::with('section')
            ->where('section_id', $section->id)
            ->get();

        $selectingForCreate = session('selecting_category_for_create', false);

        return view('categories.index', [
            'categories' => $categories,
            'type' => 'Category',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $section,
        ]);
    }

    public function show(Section $section, Category $category)
    {
        $category->load('section');

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(15);

        $vehicleTypes = VehicleType::where('category_id', $category->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $category,
            'vehicles' => $vehicles,
            'childCategories' => $vehicleTypes,
            'childCategoryType' => 'Vehicle Type',
            'parentCategory' => $category, // Pass category as parent for button
        ]);
    }
}
