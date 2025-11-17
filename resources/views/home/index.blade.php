@php // resources/views/home/index.blade.php
$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = $taxonomyService->getConfig('main-category');
@endphp

<x-app-layout title="Home Page">
    <x-hero.home-slider />

    <main>
        <x-taxonomy.section
            :categories="$categories"
            :type="$config['type']"
            :pluralType="$config['pluralType']"
            :indexRouteName="$config['indexRouteName']"
            :showRouteName="$config['showRouteName']"
        />

        <x-search-form />

        {{-- Latest Vehicles Section --}}
        <section>
            <div class="container">
                {{-- Header --}}
                <div class="section-header mb-medium">
                    <h2 class="section-title">Latest Vehicles Added</h2>
                    <span id="total-results" class="results-count"></span>
                </div>

                {{-- Main Results Grid (CSS handles the 4 columns now) --}}
                <div id="search-results" class="vehicle-grid">
                    {{-- JS will inject items here --}}
                </div>

                {{-- Main Loading Indicator (Initial Load) --}}
                <div id="loading-indicator" class="loader-container hidden">
                    <div class="loader main">
                        <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>
                    </div>
                </div>

                {{-- No Results Message --}}
                <div id="no-results" class="status-container hidden">
                    <p class="no-results-text">No vehicles found.</p>
                </div>

                {{-- Load More Indicator (Infinite Scroll) --}}
                <div id="load-more-indicator" class="loader-container hidden" style="height: 80px;">
                    <div class="loader main">
                        <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>
                    </div>
                </div>

                {{-- End of Results --}}
                <div id="end-of-results" class="end-message hidden">
                    You've reached the end of the list.
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
