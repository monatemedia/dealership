{{-- resources/views/components/vehicle/search-results-display.blade.php --}}
{{--    This component displays the search results and contains the trigger    for the Geo-Search Modal.--}}
<section>
    <div class="container">
        {{-- Header --}}
        <div class="section-header mb-medium">
            <h2 id="search-results-count">Define your search criteria</h2>
            {{-- 🆕 ALPINE CONTEXT FOR LOCATION DISPLAY --}}
            <a href="#" x-data @click.prevent="$dispatch('open-geo-modal')" class="location-link">
                <span class="m-0">
                    <i class="fa-solid fa-compass"></i>

                    {{-- 🔑 FIX 1: Display wrapper for initial state --}}
                    <span id="geo-display-wrapper">Choose Location</span>

                    {{-- 🔑 FIX 2: Spans for city/province/range—initially empty/hidden, populated by modal JS --}}
                    <span id="geo-city-display" class="hidden"></span>
                    <span id="geo-province-display" class="hidden"></span>
                    <span id="geo-range-display" class="hidden"></span>
                </span>
            </a>
        </div>
        {{-- Main Results Grid --}}
        <div id="search-results" class="vehicle-grid">
            {{-- JS will inject items here --}}
        </div>
        {{-- Main Loading Indicator (Initial Load) --}}
        <div id="loading-indicator" class="loader-container hidden">
            <div class="loader main">
                <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>             </div>
        </div>
        {{-- No Results Message --}}
        <div id="no-results" class="status-container hidden">
            <p class="no-results-text">No vehicles found.</p>
        </div>
        {{-- Load More Indicator (Infinite Scroll) --}}
        <div id="load-more-indicator" class="loader-container hidden" style="height: 80px;">
            <div class="loader main">
                <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>             </div>
        </div>
        {{-- End of Results --}}
        <div id="end-of-results" class="end-message hidden">
            You've reached the end of the list.
        </div>
    </div>
    {{-- 🆕 THE LOCATION SELECTION MODAL --}}
    <x-vehicle.geo-search-modal />
</section>
