@props([
    'categories',
    'selectingForCreate' => false,
    'showRouteName',
    'createRouteName',
    'createRouteParam',
    'typeLabel',
    'parentCategory' => null, // optional for nested routes
])

<div class="category-grid container">
    @foreach ($categories as $category)
        @php
            if ($selectingForCreate) {
                $href = route($createRouteName, [$createRouteParam => $category->slug]);
            } elseif ($showRouteName) {
                // Nested route generation

                // VehicleType -> subCategory -> mainCategory
                if (isset($category->subCategory) && isset($category->subCategory->mainCategory)) {
                    $href = route($showRouteName, [
                        'mainCategory' => $category->subCategory->mainCategory->slug,
                        'subCategory' => $category->subCategory->slug,
                        'vehicleType' => $category->slug,
                    ]);

                // SubCategory -> mainCategory (index of vehicle types)
                } elseif (isset($category->mainCategory)) {
                    $href = route($showRouteName, [
                        'mainCategory' => $category->mainCategory->slug,
                        'subCategory' => $category->slug,
                    ]);

                // MainCategory or simple single-parameter route
                } else {
                    $href = route($showRouteName, $category->slug);
                }
            } else {
                $href = '#';
            }
        @endphp

        <x-category.card
            :href="$href"
            :image="$category->image_path"
            :title="$category->long_name ?? $category->name"
            :description="$category->description"
            :selectingForCreate="$selectingForCreate"
            :typeLabel="$typeLabel"
        />
    @endforeach
</div>
