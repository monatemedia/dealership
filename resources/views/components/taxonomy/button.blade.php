@props([
    'text',
    'indexRouteName',          // Route name for index page
    'parentCategory' => null,  // Optional parent model
    'hideOnRoute' => null,
])

@php
use Illuminate\Support\Facades\Route;
use App\Services\TaxonomyRouteService;

$isHidden = $hideOnRoute && Route::currentRouteNamed($hideOnRoute);

$taxonomyService = app(TaxonomyRouteService::class);

try {
    $params = $taxonomyService->resolveIndexParams($indexRouteName, $parentCategory);

    // Safely generate URL; fallback to # if route params are missing
    $href = empty($params) && !empty($parentCategory) ? '#' : route($indexRouteName, $params);
} catch (\Throwable $e) {
    $href = '#';
}
@endphp

@if (! $isHidden)
    <div class="category-button">
        <a href="{{ $href }}" class="btn btn-hero-slider">{{ $text }}</a>
    </div>
@endif
