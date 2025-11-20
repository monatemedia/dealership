{{--
    The Geo Search Modal allows users to select an origin city and a search range (in km).
    It dispatches a global event to update the main VehicleInstantSearch class.
--}}
<div
    x-data="{
        // Initial state mirrors the default location
        originCityId: 3212,
        originCityName: 'Parow',
        originProvinceName: 'Western Cape',
        rangeKm: 5,
        isOpen: false,

        // Action: Closes the modal by setting the state
        closeModal() {
            this.isOpen = false;
        },

        // Handlers for selection components
        handleCitySelect(event) {
            this.originCityId = event.detail.id;
            this.originCityName = event.detail.name;
        },
        handleRangeChange(event) {
             this.rangeKm = event.target.value;
        },

        // Action: Apply the filters and close the modal
        applyLocationFilter() {
            // Find the elements that display the selected location
            const cityEl = document.getElementById('geo-city-display');
            const rangeEl = document.getElementById('geo-range-display');

            // Find the hidden fields in the main filter form (or create them if not present)
            let cityIdInput = document.getElementById('origin_city_id_filter');
            let rangeKmInput = document.getElementById('range_km_filter');

            // Ensure hidden fields exist in the main filter form
            if (!cityIdInput) {
                const form = document.getElementById('filter-form');
                if (form) {
                    cityIdInput = document.createElement('input');
                    cityIdInput.type = 'hidden';
                    cityIdInput.name = 'origin_city_id';
                    cityIdInput.id = 'origin_city_id_filter';
                    form.appendChild(cityIdInput);
                }
            }

            if (!rangeKmInput) {
                const form = document.getElementById('filter-form');
                 if (form) {
                    rangeKmInput = document.createElement('input');
                    rangeKmInput.type = 'hidden';
                    rangeKmInput.name = 'range_km';
                    rangeKmInput.id = 'range_km_filter';
                    form.appendChild(rangeKmInput);
                }
            }

            // Update hidden fields with current modal state
            if (cityIdInput) cityIdInput.value = this.originCityId;
            if (rangeKmInput) rangeKmInput.value = this.rangeKm;

            // Update the display text on the search results page
            if (cityEl) cityEl.textContent = this.originCityName;
            if (rangeEl) rangeEl.textContent = `${this.rangeKm} km`;

            // Call the global search function to re-run the query
            const applyBtn = document.getElementById('apply-filters');
            if (applyBtn) applyBtn.click();

            this.isOpen = false;
        },

        // Initializes the modal state from the current search filters on page load
        initializeState() {
            const cityId = document.getElementById('origin_city_id_filter')?.value;
            const range = document.getElementById('range_km_filter')?.value;

            if (cityId) this.originCityId = parseInt(cityId);
            if (range) this.rangeKm = parseFloat(range);
        }
    }"
    x-init="initializeState()"
    @open-geo-modal.window="isOpen = true"
    @city-selected.window="handleCitySelect"
    @range-changed.window="handleRangeChange"
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
                {{-- Component Renamed --}}
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
