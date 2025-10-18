{{-- resources/views/components/taxonomy/section.blade.php --}}
@props([
    'categories',
    'type',              // e.g., 'Main Category', 'Vehicle Type', 'Fuel Type'
    'pluralType',        // e.g., 'Main Categories', 'Vehicle Types'
    'indexRouteName',    // e.g., 'vehicle-types.index'
    'showRouteName',     // e.g., 'vehicle-types.show'
    'createRouteName' => 'vehicle.create',
    'createRouteParam' => 'sub_category',
    'selectingForCreate' => false,
    'showButton' => true,
    'parentCategory' => null,
    'routeResolver' => null, // Optional closure for custom route building
])

@php
use Illuminate\Support\Facades\Route;

$isIndexPage = Route::currentRouteNamed($indexRouteName);
$tag = $isIndexPage ? 'h1' : 'h2';

// Title logic
if ($selectingForCreate) {
    $title = "Select a <strong>{$type}</strong>";
    $paragraph = "Choose the {$type} that best matches your vehicle <br> to create your listing";
} elseif ($isIndexPage) {
    $title = "All <strong>{$pluralType}</strong>";
    $paragraph = "Experience the pinnacle of quality <br> with our carefully curated vehicle categories.";
} else {
    $title = "Popular <strong>{$pluralType}</strong>";
    $paragraph = "See the most popular {$pluralType}";
}

// Compute index route parameters
$routeParams = [];
if ($routeResolver && is_callable($routeResolver)) {
    $routeParams = $routeResolver($parentCategory);
} elseif ($parentCategory) {
    // Auto-detect route parameters based on parent relationships
    if ($indexRouteName === 'vehicle-types.index' && $parentCategory->mainCategory) {
        $routeParams = [
            'mainCategory' => $parentCategory->mainCategory->slug,
            'subCategory' => $parentCategory->slug,
        ];
    }
}
@endphp

<section @class([
    'category-section',
    'category-section-no-padding' => !$isIndexPage && $showButton,
])>
    <x-title
        :tag="$tag"
        :title="$title"
        :paragraph="$paragraph"
    />

    <x-taxonomy.grid
        :categories="$categories"
        :selectingForCreate="$selectingForCreate"
        :showRouteName="$showRouteName"
        :createRouteName="$createRouteName"
        :createRouteParam="$createRouteParam"
        :typeLabel="Str::singular($type)"
        :parentCategory="$parentCategory"
        :routeResolver="$routeResolver"
    />

    @if ($showButton)
        <x-taxonomy.button
            :text="'All ' . $pluralType"
            :href="count($routeParams) ? route($indexRouteName, $routeParams) : '#'"
            :hideOnRoute="$indexRouteName"
        />
    @endif
</section>
