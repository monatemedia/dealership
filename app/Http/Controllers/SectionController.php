<?php
// app/Http/Controllers/SectionController.php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Category;
use App\Models\Vehicle;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        $selectingForCreate = session('selecting_category_for_create', false);

        // **FIX:** When selecting, the "create" route is the *next step* // (the category list), not the *final* step (vehicle.create).

        $createRouteName = $selectingForCreate
            ? 'section.categories.index' // Next step in the flow
            : 'sections.show'; // Default "show" action

        $createRouteParam = $selectingForCreate
            ? 'section' // The param name for the category route
            : 'section'; // The param name for the show route

        // If not selecting, just use the standard show route
        if (!$selectingForCreate) {
             $createRouteName = 'sections.show';
        }

        return view('categories.index', [
            'categories' => $sections,
            'type' => 'Section',
            'pluralType' => 'Sections', // Add this for the component title
            'selectingForCreate' => $selectingForCreate,

            // **PASS THE CORRECT PROPS TO THE VIEW**
            'indexRouteName' => 'sections.index',
            'showRouteName' => 'sections.show',
            'createRouteName' => $createRouteName,
            'createRouteParam' => $createRouteParam,
        ]);
    }

    public function show(Section $section) // Changed $section to $section
    {
        // TEST 1: Is Laravel finding the section based on the slug?
        // dd($section);

        // Eager load categories using the relationship we renamed earlier
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('section_id', $section->id)
            ->latest()
            ->paginate(15);

        $categories = Category::where('section_id', $section->id)
            ->take(3)
            ->get();

        $data = [
            'category'          => $section,
            'vehicles'          => $vehicles,
            'childCategories'   => $categories,
            'childCategoryType' => 'Category',
            'parentCategory'    => $section,
        ];

        // TEST 2: What is actually being sent to the view?
        // dd($data);

        return view('categories.show', $data);
    }
}
