{{-- resources/views/components/taxonomy/grid.blade.php --}}
@props([
    'categories',
    'selectingForCreate' => false,
    'showRouteName' => null,
    'createRouteName' => null,
    'createRouteParam' => null,
    'typeLabel',
    'parentCategory' => null,
    'routeResolver' => null, // Optional closure for custom route building
])

<div class="category-grid container">
    @foreach ($categories as $category)
        @php
            if ($selectingForCreate) {
                $href = route($createRouteName, [$createRouteParam => $category->slug]);
            } elseif ($showRouteName) {
                $href = $routeResolver && is_callable($routeResolver)
                    ? $routeResolver($category, $parentCategory)
                    : app('App\Services\TaxonomyRouteService')->resolveShowRoute($showRouteName, $category);
            } else {
                $href = '#';
            }
        @endphp

        <x-taxonomy.card
            :href="$href"
            :image="$category->image_path"
            :title="$category->long_name ?? $category->name"
            :description="$category->description"
            :selectingForCreate="$selectingForCreate"
            :typeLabel="$typeLabel"
        />
    @endforeach
</div>
