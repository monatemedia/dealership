{{-- resources/views/categories/show.blade.php --}}
@props([
    'category',
    'vehicles',
    'childCategories' => null,
    'childCategoryType' => null, // e.g., 'Sub-Category' or 'Vehicle Type'
    'parentCategory' => null,
])

<x-app-layout :title="$category->name">

    <!-- Home Slider -->
    <x-hero.home-slider />
    <!--/ Home Slider -->

    <main>
        {{-- Conditionally display the child category section --}}
        @if ($childCategories && $childCategories->isNotEmpty())
            @php
                // Determine the plural slug for route names
                $pluralSlug = Str::of($childCategoryType)
                    ->replace('-', ' ')
                    ->plural()
                    ->kebab();

                // Determine which show route to use
                if ($childCategoryType === 'Sub-Category') {
                    $showRoute = 'sub-categories.show'; // Needs both mainCategory + subCategory
                } elseif ($childCategoryType === 'Vehicle Type') {
                    $showRoute = 'vehicle-types.show'; // Needs both subCategory + vehicleType
                } else {
                    $showRoute = $pluralSlug . '.show'; // fallback
                }
            @endphp

            <x-category.section
                :categories="$childCategories"
                :type="$childCategoryType"
                :pluralType="Str::plural($childCategoryType)"
                :indexRouteName="$pluralSlug . '.index'"
                :showRouteName="$showRoute"
                :selectingForCreate="false"
            />
        @endif

        <x-search-form />
        <section>
            <div class="container">
                {{-- Handle the special title for Vehicle Types --}}
                @if ($parentCategory)
                    <h2>{{ $category->name }} - {{ $parentCategory->name }}</h2>
                @else
                    <h2>Latest {{ $category->name }}</h2>
                @endif

                @if ($vehicles->count() > 0)
                    <div class="vehicle-items-listing">
                        @foreach($vehicles as $vehicle)
                            <x-vehicle-item :$vehicle
                                :is-in-watchlist="$vehicle->favouredUsers->contains(
                                    \Illuminate\Support\Facades\Auth::user()
                                )"/>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-large">
                        There are no published vehicles in this category.
                    </div>
                @endif
                {{ $vehicles->onEachSide(1)->links() }}
            </div>
        </section>
</x-app-layout>
