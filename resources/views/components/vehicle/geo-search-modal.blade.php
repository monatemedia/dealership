{{-- resources/views/components/vehicle/geo-search-modal.blade.php --}}
@php
    // Define only the keys we are actively using for persistence
    $cityIdKey = 'geo_filter_city_id';
    $cityNameKey = 'geo_filter_city_name';
    $rangeKey = 'geo_filter_range_km';
@endphp

<div
    x-data="{
        // --- Keys ---
        cityIdKey: '{{ $cityIdKey }}',
        cityNameKey: '{{ $cityNameKey }}',
        rangeKey: '{{ $rangeKey }}',

        // --- State ---
        originCityId: null,
        originCityName: '',
        rangeKm: 5,
        isOpen: false,

        handleCitySelect(event) {
            this.originCityId = event.detail.id;
            this.originCityName = event.detail.fullName;
            console.log('handleCitySelect: ID:', this.originCityId, 'Full Name:', this.originCityName);
        },

        handleRangeChange(event) {
            this.rangeKm = event.detail.range;
        },

        closeModal() {
            this.isOpen = false;
        },

        applyLocationFilter() {
            // ⭐️ STEP 1: Update Local Storage ⭐️
            if (this.originCityId) {
                localStorage.setItem(this.cityIdKey, this.originCityId);
                localStorage.setItem(this.cityNameKey, this.originCityName);
                localStorage.setItem(this.rangeKey, this.rangeKm);
            } else {
                // If the location is cleared, clear all storage keys
                localStorage.removeItem(this.cityIdKey);
                localStorage.removeItem(this.cityNameKey);
                localStorage.removeItem(this.rangeKey);
            }

            // ⭐️ STEP 2: Update hidden filter inputs ⭐️
            let cityIdInput = document.getElementById('origin_city_id_filter');
            let rangeKmInput = document.getElementById('range_km_filter');
            let applyFiltersButton = document.getElementById('apply-filters');

            if (cityIdInput) cityIdInput.value = this.originCityId || '';
            if (rangeKmInput) rangeKmInput.value = this.rangeKm || '5';

            // ⭐️ STEP 3: Close modal and trigger search ⭐️
            this.closeModal();

            // Trigger the main search script (VehicleInstantSearch.js)
            if (applyFiltersButton) applyFiltersButton.click();
        },

        initializeState() {
            // Read current filter state from hidden inputs (set by search-results-display on load)
            const cityIdInput = document.getElementById('origin_city_id_filter');
            const rangeKmInput = document.getElementById('range_km_filter');

            // Read from Local Storage as a primary source of truth
            const cityId = localStorage.getItem(this.cityIdKey);
            const cityName = localStorage.getItem(this.cityNameKey);
            const range = localStorage.getItem(this.rangeKey);

            if (cityId && cityId !== '' && cityId !== '0') {
                this.originCityId = parseInt(cityId);
                this.originCityName = cityName || '';

                // Ensure hidden inputs are populated for server/InstantSearch on subsequent calls
                if (cityIdInput) cityIdInput.value = cityId;
            } else {
                this.originCityId = null;
                this.originCityName = '';
                if (cityIdInput) cityIdInput.value = '';
            }

            if (range) {
                 this.rangeKm = Number(range);
                 if (rangeKmInput) rangeKmInput.value = range;
            } else {
                 this.rangeKm = 5;
                 if (rangeKmInput) rangeKmInput.value = '5';
            }
        }
    }"
    x-init="initializeState()"
    @open-geo-modal.window="
        // 1. Update internal state from hidden inputs / local storage
        initializeState();
        // 2. CRITICAL: Use $nextTick to ensure x-bind:value has propagated to the child component
        $nextTick(() => {
            isOpen = true;
        });
    "
    @city-selected.window="handleCitySelect"
    @range-changed.window="handleRangeChange"
>
    <div
        x-show="isOpen"
        x-cloak
        class="modal-backdrop"
        @click.self="closeModal"
        style="display: none;"
    >
        <div
            class="modal-content"
            style="max-width: 500px;"
            @click.stop
        >
            <div class="modal-header">
                <h2>Search Location</h2>
            </div>
            <div class="modal-body">
                <div class="space-y-6 p-4">
                    {{-- 1. Location Selection --}}
                    <div class="form-group">
                        <label class="mb-medium block">Location</label>
                        <x-search.search-city
                            name="origin_city_id"
                            city-selected-event="city-selected"
                        />
                    </div>
                    {{-- 2. Range Slider (Using x-bind:initial-range="rangeKm") --}}
                     <div class="form-group">
                        <label class="mb-medium block">Search Distance:</label>
                        <x-search.search-range-slider
                            name="range_km"
                            x-bind:initial-range="rangeKm"
                            city-event="city-selected"
                            province-event="filters-reset"
                        />
                    </div>
                    {{-- 3. Apply Button --}}
                    <div class="flex justify-end pt-4">
                        <button
                            type="button"
                            @click="applyLocationFilter"
                            class="btn btn-primary"
                        >
                            Apply Location Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
