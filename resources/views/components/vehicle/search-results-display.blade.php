{{-- resources/views/components/vehicle/search-results-display.blade.php --}}
{{--
    This component displays the search results and contains the trigger
    for the Geo-Search Modal, as well as the critical hidden inputs
    and the main search trigger button targeted by the modal's JS.
--}}
<section>
    <div class="container">
        {{-- Header --}}
        <div class="section-header mb-medium">
            <h2 id="search-results-count">Define your search criteria</h2>
            {{-- 🆕 ALPINE CONTEXT FOR LOCATION DISPLAY --}}
            <a href="#" x-data @click.prevent="$dispatch('open-geo-modal')" class="location-link">
                <span class="m-0">
                    <i class="fa-solid fa-compass"></i>
                    {{-- 🔑 Display wrapper for initial state (shown when no location selected) --}}
                    <span id="geo-display-wrapper" x-show="!document.getElementById('geo-city-display')?.textContent.trim()">Choose Location</span>
                    {{-- 🔑 Spans for city/province/range—initially empty/hidden, populated by modal JS --}}
                    <span id="geo-city-display" class="font-semibold text-indigo-600"></span>
                    <span id="geo-province-display" class=""></span>
                    <span id="geo-range-display" class="text-sm text-gray-500"></span>
                </span>
            </a>
        </div>

        {{-- 🔑 CRITICAL: HIDDEN INPUTS for Location Filtering (TARGETS for the Geo Modal) --}}
        <div class="hidden">
            <input type="hidden" id="origin_city_id_filter" name="origin_city_id_filter" value="">
            <input type="hidden" id="range_km_filter" name="range_km_filter" value="5">
        </div>

        {{-- Main Results Grid --}}
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

        {{-- 🔑 CRITICAL: APPLY FILTERS BUTTON (TARGET for the Geo Modal) --}}
        {{-- This button is usually hidden and only clicked programmatically by the modal to trigger a search. --}}
        <button type="button" id="apply-filters" class="hidden">Apply Filters</button>

    </div>
    {{-- 🆕 THE LOCATION SELECTION MODAL --}}
    <x-vehicle.geo-search-modal />
</section>
