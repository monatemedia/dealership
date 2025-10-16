@props([
    'categories',
    'type',
    'pluralType',
    'indexRouteName',
    'showRouteName',
    'createRouteName' => 'vehicle.create',
    'createRouteParam' => 'sub_category',
    'selectingForCreate' => false,
    'showButton' => true,
    'parentCategory' => null, // Required for nested index routes
])

@php
use Illuminate\Support\Facades\Route;

$isIndexPage = Route::currentRouteNamed($indexRouteName);
$tag = $isIndexPage ? 'h1' : 'h2';

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

// Compute index route parameters safely
$routeParams = [];
if ($indexRouteName === 'vehicle-types.index' && $parentCategory && $parentCategory->mainCategory) {
    $routeParams = [
        'mainCategory' => $parentCategory->mainCategory->slug,
        'subCategory' => $parentCategory->slug,
    ];
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

    <x-category.grid
        :categories="$categories"
        :selectingForCreate="$selectingForCreate"
        :showRouteName="$showRouteName"
        :createRouteName="$createRouteName"
        :createRouteParam="$createRouteParam"
        :typeLabel="Str::singular($type)"
    />

    @if ($showButton)
        <x-category.button
            :text="'All ' . $pluralType"
            :href="count($routeParams) ? route($indexRouteName, $routeParams) : '#'"
            :hideOnRoute="$indexRouteName"
        />
    @endif
</section>
