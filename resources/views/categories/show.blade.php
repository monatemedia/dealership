{{-- resources/views/categories/show.blade.php --}}
@props([
    'category',
    'vehicles',
    'childCategories' => null,
    'childCategoryType' => null,
    'parentCategory' => null,
])

@php
use Illuminate\Support\Str;

$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = [];

if ($childCategoryType) {
    $config = $taxonomyService->getConfig($childCategoryType);
}
@endphp

<x-app-layout :title="$category->name">
    <x-hero.home-slider />

    <main>
        {{-- DEBUG: Remove later --}}
        {{-- @if(config('app.debug'))
            <div style="background:#f9f9f9;padding:10px;border:1px solid #ddd;font-family:monospace;font-size:12px;">
                <p>childCategoryType: {{ $childCategoryType }}</p>
                <p>config found: {{ empty($config) ? '❌' : '✅' }}</p>
                <p>childCategories: {{ $childCategories?->count() ?? 0 }}</p>
                <p>parentCategory: {{ $parentCategory?->name ?? 'NULL' }}</p>
            </div>
        @endif --}}

        {{-- Child taxonomy section --}}
        @if ($childCategories && $childCategories->isNotEmpty() && !empty($config))
            <x-taxonomy.section
                :categories="$childCategories"
                :type="$config['type']"
                :pluralType="$config['pluralType']"
                :indexRouteName="$config['indexRouteName']"
                :showRouteName="$config['showRouteName']"
                :parentCategory="$parentCategory"
            />
        @endif

        <x-search-form />

        <section>
            <div class="container">
                <h2>
                    @if ($parentCategory)
                        {{ $category->name }} - {{ $parentCategory->name }}
                    @else
                        Latest {{ $category->name }}
                    @endif
                </h2>

                @if ($vehicles->count() > 0)
                    <div class="vehicle-items-listing">
                        @foreach($vehicles as $vehicle)
                            <x-vehicle-item
                                :$vehicle
                                :is-in-watchlist="$vehicle->favouredUsers->contains(Auth::user())"
                            />
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
    </main>
</x-app-layout>
