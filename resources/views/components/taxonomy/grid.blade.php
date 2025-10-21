{{-- resources/views/components/taxonomy/grid.blade.php --}}
@props([
    'categories',
    'selectingForCreate' => false,
    'showRouteName',
    'createRouteName',
    'createRouteParam',
    'typeLabel',
    'parentCategory' => null,
    'routeResolver' => null, // Optional closure for custom route building
])

<div class="category-grid container">
    @foreach ($categories as $category)


        @php
            $href = '#';

            if ($selectingForCreate) {
                // Creating mode: link to create form with pre-selected category
                $href = route($createRouteName, [$createRouteParam => $category->slug]);
            } elseif ($showRouteName) {
                // Use custom route resolver if provided
                if ($routeResolver && is_callable($routeResolver)) {
                    $href = $routeResolver($category, $parentCategory);
                } else {
                    // Auto-detect route parameters based on relationships
                    $href = app('App\Services\TaxonomyRouteService')
                        ->resolveShowRoute($showRouteName, $category);
                }
            }
        @endphp

        {{-- @if(config('app.debug') && $selectingForCreate)
            <div style="background: lightblue; padding: 10px; margin: 10px;">
                Category: {{ $category->name }}<br>
                Class: {{ $categoryClass ?? 'not set' }}<br>
                Href: {{ $href }}
            </div>
        @endif --}}


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
