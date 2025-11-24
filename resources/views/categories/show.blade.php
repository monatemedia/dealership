{{-- resources/views/categories/show.blade.php --}}
@props([
    'category',
    'vehicles', // NOTE: This variable is now redundant for rendering, but kept for context.
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

    // Define the category ID for JS filtering
    $categoryId = $category->id;

    // Determine the name of the foreign key based on the category type
    if ($category instanceof \App\Models\MainCategory) {
        $foreignKeyName = 'main_category_id';
    } elseif ($category instanceof \App\Models\Subcategory) {
        $foreignKeyName = 'subcategory_id';
    } else {
        // Fallback if Category is a different type (e.g., VehicleType)
        $foreignKeyName = 'category_id';
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

        {{-- ----------------------------------------------------- --}}
        {{-- 🎯 INSTANT SEARCH SETUP & CATEGORY FILTERING (New Logic) --}}
        {{-- ----------------------------------------------------- --}}

        {{-- 1. MOCK FILTER FORM FOR JS INITIALIZATION --}}
        <form id="filter-form" style="display:none;">
            {{--
                CRITICAL FIX:
                The input name must match the database column name used for filtering.
            --}}
            <input type="hidden" name="{{ $foreignKeyName }}" value="{{ $category->id }}">
        </form>

        {{-- 2. SIMPLE SEARCH INPUT BAR --}}
        <section class="section-search">
            <div class="container">
                <div class="mb-medium">
                    <div class="find-a-vehicle-form card p-medium">
                        <div class="form-group">
                            <label for="instant-search-input" style="display:block; font-weight:600; margin-bottom:0.5rem;">
                                Search in {{ $category->name }}
                            </label>
                            <input
                                type="text"
                                id="instant-search-input"
                                placeholder="Search by make, model, location..."
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;"
                            />
                            <small class="text-muted" style="display:block; margin-top: 8px;">
                                Start typing to search instantly within this category
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. RESULTS CONTAINER --}}
        <section class="section-latest-vehicles py-large">
            <div class="container">
                <h2>
                    @if ($parentCategory)
                        {{ $category->name }} - {{ $parentCategory->name }}
                    @else
                        Latest {{ $category->name }}
                    @endif
                </h2>

                {{-- Vehicle grid where JS injects results (4 columns via CSS) --}}
                <div id="search-results" class="vehicle-grid">
                    {{-- JS will fetch and inject items here --}}
                </div>

                {{-- Status Indicators (Required by VehicleInstantSearch.js) --}}
                <div id="loading-indicator" class="loader-container hidden">
                    <div class="loader main"><div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div></div>
                </div>
                <div id="no-results" class="status-container hidden">
                    <p class="no-results-text">There are no published vehicles in this category.</p>
                </div>
                <div id="load-more-indicator" class="loader-container hidden">
                    <div class="loader main"><div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div></div>
                </div>
                <div id="end-of-results" class="end-message hidden">
                    You've reached the end of the list for {{ $category->name }}.
                </div>

            </div>
        </section>
    </main>
</x-app-layout>
