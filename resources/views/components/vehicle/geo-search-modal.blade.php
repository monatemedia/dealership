{{--    The Geo Search Modal allows users to select an origin city and a search range (in km).    It dispatches a global event to update the main VehicleInstantSearch class.--}}
<div    x-data="{
        originCityId: null,
        originCityName: '',
        originProvinceName: '',
        rangeKm: 5,
        isOpen: false,

        closeModal() {
            this.isOpen = false;
        },

        handleCitySelect(event) {
            this.originCityId = event.detail.id;
            this.originCityName = event.detail.name;
        },

        handleProvinceSelect(event) {
            this.originProvinceName = event.detail.name;
        },

        applyLocationFilter() {
            // Display elements in search-results-display
            const wrapperEl = document.getElementById('geo-display-wrapper'); // 'Choose Location'
            const cityEl = document.getElementById('geo-city-display');
            const provinceEl = document.getElementById('geo-province-display');
            const rangeEl = document.getElementById('geo-range-display');

            // Hidden fields in the main filter form
            let cityIdInput = document.getElementById('origin_city_id_filter');
            let rangeKmInput = document.getElementById('range_km_filter');

            // Ensure hidden fields exist (original logic preserved)
            if (!cityIdInput || !rangeKmInput) {
                // If this is called before the main search form is present, handle gracefully.
                // 🔑 REMOVED: console.warn('Filter form elements not found.');
            }

            // Update hidden fields with current modal state
            if (cityIdInput) cityIdInput.value = this.originCityId || '';
            if (rangeKmInput) rangeKmInput.value = this.rangeKm;

            // Update the display text on the search results page
            if (this.originCityId) {
                // 1. Hide the default prompt
                if (wrapperEl) wrapperEl.classList.add('hidden');

                // 2. Set content and show city/range details
                if (cityEl) {
                    cityEl.textContent = this.originCityName;
                    cityEl.classList.remove('hidden');
                }

                // 3. Province Name (with leading comma and space)
                // We rely on 'geo-province-selected' to set this.originProvinceName, but
                // if it hasn't fired, it will remain empty, which is acceptable.
                if (provinceEl) {
                    // 🔑 CRITICAL FIX: Ensure the display text is correct
                    provinceEl.textContent = this.originProvinceName ? `, ${this.originProvinceName}` : '';
                    provinceEl.classList.remove('hidden');
                }

                // 4. Range (with leading dash and space, and using the live this.rangeKm value)
                if (rangeEl) {
                    // 🔑 CRITICAL FIX: Use the live state rangeKm
                    rangeEl.textContent = ` - ${this.rangeKm} km`;
                    rangeEl.classList.remove('hidden');
                }
            } else {
                // No city selected, show default prompt and hide detailed parts
                if (wrapperEl) {
                    wrapperEl.textContent = 'Choose Location';
                    wrapperEl.classList.remove('hidden');
                }
                if (cityEl) cityEl.classList.add('hidden');
                if (provinceEl) provinceEl.classList.add('hidden');
                if (rangeEl) rangeEl.classList.add('hidden');
            }

            // Call the global search function to re-run the query
            const applyBtn = document.getElementById('apply-filters');
            if (applyBtn) applyBtn.click();
            this.isOpen = false;
        },

        // Initializes the modal state from the current search filters on page load
        initializeState() {
            const cityId = document.getElementById('origin_city_id_filter')?.value;
            const range = document.getElementById('range_km_filter')?.value;

            const wrapperEl = document.getElementById('geo-display-wrapper');
            const cityEl = document.getElementById('geo-city-display');
            const provinceEl = document.getElementById('geo-province-display');
            const rangeEl = document.getElementById('geo-range-display');

            if (cityId && cityId !== '') {
                // Filter is active, set state based on filter value
                this.originCityId = parseInt(cityId);

                // 🔑 FIX: If a City ID is present on load, we assume the server-side
                // rendering or the InstantSearch initialization has correctly populated
                // the display elements (city, province, range), so we just manage visibility.
                if (wrapperEl) wrapperEl.classList.add('hidden');
                if (cityEl) cityEl.classList.remove('hidden');
                if (provinceEl) provinceEl.classList.remove('hidden');
                if (rangeEl) rangeEl.classList.remove('hidden');

            } else {
                // Filter is NOT active (city ID is null or empty)
                this.originCityId = null;
                this.originCityName = '';

                // 🔑 FIX: Ensure the 'Choose Location' wrapper is visible and details are hidden.
                if (wrapperEl) wrapperEl.classList.remove('hidden');
                if (cityEl) cityEl.classList.add('hidden');
                if (provinceEl) provinceEl.classList.add('hidden');
                if (rangeEl) rangeEl.classList.add('hidden');
            }

            if (range) this.rangeKm = parseFloat(range);
        }
    }"
    x-init="initializeState()"
    @open-geo-modal.window="isOpen = true"
    @city-selected.window="handleCitySelect"
    @geo-province-selected.window="handleProvinceSelect"
>
    <x-modal-overlay title="Select Search Location" maxWidth="500px">
        <div class="space-y-6 p-4">
            {{-- 1. Province Selection (Triggers City Reset) --}}
            <div class="form-group">
                <label class="mb-medium block">Origin Province</label>
                {{-- Component Renamed --}}
                <x-search.search-province name="province_id_modal" dispatch-event="geo-province-selected" />
            </div>
            {{-- 2. City Selection (Sets Origin ID) --}}
            <div class="form-group">
                <label class="mb-medium block">Origin City</label>
                {{-- Component Renamed --}}
                <x-search.search-city name="origin_city_id" province-event="geo-province-selected" />
            </div>
            {{-- 3. Range Slider --}}
            <div class="form-group">
                {{-- 🔑 FIX: Added range display here to show live slider value --}}
                <label class="mb-medium block">Search Distance: <span x-text="`${rangeKm} km`">5 km</span></label>
                {{-- Component Renamed. We rely on x-on:input to update rangeKm state --}}
                <x-search.search-range-slider x-ref="slider" x-bind:initial-range="rangeKm" x-on:input="rangeKm = $event.target.value" />
            </div>
            {{-- 4. Apply Button --}}
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
    </x-modal-overlay>
</div>
