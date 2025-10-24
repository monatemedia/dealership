{{-- resources/views/components/taxonomy/section.blade.php --}}
@props([
    'categories',
    'type',           // Singular
    'pluralType',     // Plural
    'indexRouteName',
    'showRouteName',
    'createRouteName' => 'vehicle.create',
    'createRouteParam' => 'subcategory',
    'selectingForCreate' => false,
    'showButton' => true,
    'parentCategory' => null,
    'routeResolver' => null,
])

@php
use Illuminate\Support\Facades\Route;

$isIndexPage = Route::currentRouteNamed($indexRouteName);
$tag = $isIndexPage ? 'h1' : 'h2';

// Titles
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
@endphp

<section @class([
    'category-section',
    'category-section-no-padding' => !$isIndexPage && $showButton,
])>
    <x-title :tag="$tag" :title="$title" :paragraph="$paragraph" />

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

    @if($showButton)
        <x-taxonomy.button
            :text="'All ' . $pluralType"
            :indexRouteName="$indexRouteName"
            :parentCategory="$parentCategory"
            :hideOnRoute="$indexRouteName"
        />
    @endif

</section>
