@php
/**
 * resources/views/components/vehicle/geo-search-modal.blade.php
 * The Geo Search Modal allows users to select an origin city and a search range (in km).
 * It dispatches a global event to update the main VehicleInstantSearch class.
 */
@endphp
<div
    x-data="{
        originCityId: null,
        originCityName: '',
        originProvinceName: '',
        // Initialize rangeKm as a number
        rangeKm: 5,
        isOpen: false,
        closeModal() {
            this.isOpen = false;
        },
        handleCitySelect(event) {
            this.originCityId = event.detail.id;
            this.originCityName = event.detail.name;
            // ⭐️ DEBUG LOG: Confirm city name is received from the selection component
            console.log('handleCitySelect: ID:', this.originCityId, 'Name:', this.originCityName);
        },
        handleProvinceSelect(event) {
            this.originProvinceName = event.detail.name;
        },
        handleRangeChange(event) {
            this.rangeKm = event.detail.range;
            console.log('🎚️ Modal received range update:', this.rangeKm);
        },
        applyLocationFilter() {
            console.log('--- Applying Location Filter (START) ---');

            // 🔑 FIX: The rangeKm is already being updated via the range-changed event listener
            // No need to read from DOM - just use the current state value
            console.log('📏 Using modal state rangeKm value:', this.rangeKm);

            // ⭐️ DEBUG LOG: Final check on state before application
            console.log('Modal State: City ID:', this.originCityId, 'City Name:', this.originCityName, 'Province Name:', this.originProvinceName, 'Range:', this.rangeKm);

            // Display elements in search-results-display
            const wrapperEl = document.getElementById('geo-display-wrapper');
            const cityEl = document.getElementById('geo-city-display');
            const provinceEl = document.getElementById('geo-province-display');
            const rangeEl = document.getElementById('geo-range-display');

            // Hidden fields in the main filter form (used for InstantSearch/Server)
            let cityIdInput = document.getElementById('origin_city_id_filter');
            let rangeKmInput = document.getElementById('range_km_filter');

            // 1. Update hidden fields with current modal state
            if (cityIdInput) cityIdInput.value = this.originCityId || '';
            if (rangeKmInput) rangeKmInput.value = this.rangeKm;

            console.log('Hidden Inputs Updated: cityIdInput.value:', cityIdInput ? cityIdInput.value : 'N/A', 'rangeKmInput.value:', rangeKmInput ? rangeKmInput.value : 'N/A');

            // 2. Update the display text on the search results page
            if (this.originCityId && this.originCityName) { // CRITICAL: Must check for City Name
                // Hide the default prompt and show details
                if (wrapperEl) wrapperEl.classList.add('hidden');

                if (cityEl) {
                    cityEl.textContent = this.originCityName;
                    cityEl.classList.remove('hidden');
                }

                if (provinceEl) {
                    provinceEl.textContent = this.originProvinceName ? `, ${this.originProvinceName}` : '';
                    provinceEl.classList.remove('hidden');
                }

                if (rangeEl) {
                    // Use the current state value for display
                    rangeEl.textContent = ` - ${this.rangeKm} km`;
                    rangeEl.classList.remove('hidden');
                }
            } else {
                // If ID is set but name is missing, or no selection was made: show default prompt
                if (wrapperEl) {
                    wrapperEl.textContent = 'Choose Location';
                    wrapperEl.classList.remove('hidden');
                }
                if (cityEl) cityEl.classList.add('hidden');
                if (provinceEl) provinceEl.classList.add('hidden');
                if (rangeEl) rangeEl.classList.add('hidden');
            }

            // 3. Trigger the main search
            const applyBtn = document.getElementById('apply-filters');
            if (applyBtn) {
                console.log('Attempting to trigger search via apply-filters button click.', applyBtn);
                applyBtn.click();
            } else {
                console.warn('CRITICAL: Search trigger button (apply-filters) not found.');
            }

            this.isOpen = false;
        },
        // Initializes the modal state from the current search filters on page load
        initializeState() {
            const cityId = document.getElementById('origin_city_id_filter')?.value;
            const range = document.getElementById('range_km_filter')?.value;

            // FIX: Get current city/province names from the display elements if they are visible
            const cityEl = document.getElementById('geo-city-display');
            const provinceEl = document.getElementById('geo-province-display');

            if (cityId && cityId !== '') {
                this.originCityId = parseInt(cityId);
                // Try to initialize the name from the display text if available
                if (cityEl && !cityEl.classList.contains('hidden')) {
                    this.originCityName = cityEl.textContent.trim();
                }
                if (provinceEl && !provinceEl.classList.contains('hidden')) {
                    // Strip leading comma and space if present in the display
                    this.originProvinceName = provinceEl.textContent.trim().replace(/^, /, '');
                }
            } else {
                this.originCityId = null;
                this.originCityName = '';
                this.originProvinceName = '';
            }
            // Ensure rangeKm is set as a number
            if (range) this.rangeKm = Number(range);
        }
    }"
    x-init="initializeState()"
    @open-geo-modal.window="isOpen = true"
    @city-selected.window="handleCitySelect"
    @geo-province-selected.window="handleProvinceSelect"
    @range-changed.window="handleRangeChange">
    <x-modal-overlay title="Select Search Location" maxWidth="500px">
        <div class="space-y-6 p-4">
            {{-- 1. Province Selection (Triggers City Reset) --}}
            <div class="form-group">
                <label class="mb-medium block">Province</label>
                <x-search.search-province name="province_id_modal" dispatch-event="geo-province-selected" />
            </div>
            {{-- 2. City Selection (Sets Origin ID) --}}
            <div class="form-group">
                <label class="mb-medium block">City</label>
                <x-search.search-city name="origin_city_id" province-event="geo-province-selected" />
            </div>
            {{-- 3. Range Slider --}}
            <div class="form-group">
                <label class="mb-medium block">Search Distance: <span x-text="`${rangeKm} km`">5 km</span></label>
                <x-search.search-range-slider
                    name="range_km"
                    :initial-range="$rangeKm ?? 5"
                    city-event="city-selected"
                />
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
