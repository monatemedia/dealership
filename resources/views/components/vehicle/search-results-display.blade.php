{{-- resources/views/components/vehicle/search-results-display.blade.php --}}
@php
    // Define Local Storage Keys (MUST match geo-search-modal.blade.php)
    $cityIdKey = 'geo_filter_city_id';
    $cityNameKey = 'geo_filter_city_name'; // Holds 'City, Province'
    $rangeKey = 'geo_filter_range_km';
@endphp
<section
    x-data="{
        initDisplay() {
            // Load state from local storage on page load
            const cityId = localStorage.getItem('{{ $cityIdKey }}');
            const cityName = localStorage.getItem('{{ $cityNameKey }}'); // This is the full 'City, Province' string
            const range = localStorage.getItem('{{ $rangeKey }}');
            const wrapperEl = document.getElementById('geo-display-wrapper');
            const cityEl = document.getElementById('geo-city-display');
            const rangeEl = document.getElementById('geo-range-display');
            const cityIdInput = document.getElementById('origin_city_id_filter');
            const rangeKmInput = document.getElementById('range_km_filter');

            if (cityId && cityName) {
                // Set the hidden inputs for VehicleInstantSearch.js
                if (cityIdInput) cityIdInput.value = cityId;
                // Use saved range or default to 5
                if (rangeKmInput) rangeKmInput.value = range || '5';

                // Update the visible display elements
                if (wrapperEl) wrapperEl.classList.add('hidden');

                // FIX: Display the full cityName ('City, Province')
                if (cityEl) cityEl.textContent = cityName;

                if (rangeEl) rangeEl.textContent = ` - ${range || '5'} km`;
            } else {
                // If no saved data, ensure hidden inputs are clear for default search
                if (cityIdInput) cityIdInput.value = '';
                if (rangeKmInput) rangeKmInput.value = '5';

                // Show default display prompt
                if (wrapperEl) wrapperEl.classList.remove('hidden');
                if (cityEl) cityEl.textContent = '';
                if (rangeEl) rangeEl.textContent = '';
            }
        }
    }"
    x-init="initDisplay()"
    {{-- 🔑 NEW: Listen for the custom event dispatched by the modal after filters are applied --}}
    x-on:filters-applied.window="initDisplay()"
>
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
                    <span id="geo-range-display" class="text-sm text-gray-500"></span>
                </span>
            </a>
        </div>
        {{-- 🔑 CRITICAL: HIDDEN INPUTS for Location Filtering (TARGETS for the Geo Modal) --}}
        <div class="hidden">
            {{-- These values will be set by the x-init function above --}}
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
        <button type="button" id="apply-filters" class="hidden">Apply Filters</button>
    </div>
    {{-- 🆕 THE LOCATION SELECTION MODAL --}}
    <x-vehicle.geo-search-modal />
</section>
