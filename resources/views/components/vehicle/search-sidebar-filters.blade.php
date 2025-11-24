{{-- resources/views/components/vehicle/search-sidebar-filters.blade.php --}}
@props(['fuelTypes' => collect(), 'mainCategories' => collect()])
@php
    // Define Local Storage Keys (MUST match geo-search-modal.blade.php)
    $cityIdKey = 'geo_filter_city_id';
    $cityNameKey = 'geo_filter_city_name'; // Added for full persistence
    $rangeKey = 'geo_filter_range_km';
@endphp

<div class="search-vehicles-sidebar">
    <div class="card card-found-vehicles">
        <p class="m-0">Found <strong id="total-results">0</strong> vehicles</p>
        <button class="close-filters-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px">
                   <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <section class="find-a-vehicle">
        <form id="filter-form" class="find-a-vehicle-form card flex p-medium"
            x-data="{
                selectedMainCategory: '{{ request('main_category_id', '') }}',

                // 🔑 NEW STATE for Geo Persistence 🔑
                currentCityId: null,
                currentCityName: '',
                currentRangeKm: 5,

                // 🔑 HANDLERS 🔑
                handleCitySelect(event) {
                    this.currentCityId = event.detail.id;
                    this.currentCityName = event.detail.fullName;
                    console.log('SIDEBAR: City Select captured. ID:', this.currentCityId);
                },
                handleRangeChange(event) {
                    this.currentRangeKm = event.detail.range;
                    console.log('SIDEBAR: Range Change captured. Range:', this.currentRangeKm);
                },

                // 🔑 PERSISTENCE & SEARCH 🔑
                applyFiltersAndPersist() {
                    console.log('SIDEBAR: Applying filters and persisting geo data...');

                    // 1. Update Local Storage
                    if (this.currentCityId) {
                        localStorage.setItem('{{ $cityIdKey }}', this.currentCityId);
                        localStorage.setItem('{{ $cityNameKey }}', this.currentCityName);
                        localStorage.setItem('{{ $rangeKey }}', this.currentRangeKm || '5');
                        console.log(`SIDEBAR PERSIST: Saved ID: ${this.currentCityId}, Range: ${this.currentRangeKm}`);
                    } else {
                        // Clear all geo keys if the location is empty
                        localStorage.removeItem('{{ $cityIdKey }}');
                        localStorage.removeItem('{{ $cityNameKey }}');
                        localStorage.removeItem('{{ $rangeKey }}');
                        console.log('SIDEBAR PERSIST: Geo data cleared.');
                    }

                    // 2. Update hidden filter inputs (These are the inputs VehicleInstantSearch.js reads)
                    let cityIdInput = document.getElementById('origin_city_id_filter');
                    let rangeKmInput = document.getElementById('range_km_filter');
                    let applyFiltersButton = document.getElementById('apply-filters');

                    if (cityIdInput) cityIdInput.value = this.currentCityId || '';
                    if (rangeKmInput) rangeKmInput.value = this.currentRangeKm || '5';

                    // 3. Trigger the search
                    this.$dispatch('filters-applied');
                    if (applyFiltersButton) applyFiltersButton.click();
                },

                // 🔑 INITIALIZE STATE 🔑
                initializeState() {
                    const cityId = localStorage.getItem('{{ $cityIdKey }}');
                    const cityName = localStorage.getItem('{{ $cityNameKey }}');
                    const range = localStorage.getItem('{{ $rangeKey }}');

                    if (cityId && cityId !== '' && cityId !== '0') {
                        this.currentCityId = cityId;
                        this.currentCityName = cityName || '';
                        this.currentRangeKm = Number(range) || 5;
                    } else {
                        this.currentCityId = null;
                        this.currentCityName = '';
                        this.currentRangeKm = 5;
                    }

                    // Set hidden inputs immediately upon initialization (required for the initial search state)
                    document.getElementById('origin_city_id_filter').value = this.currentCityId || '';
                    document.getElementById('range_km_filter').value = this.currentRangeKm || '5';
                    console.log(`SIDEBAR INIT: Loaded ID: ${this.currentCityId}, Range: ${this.currentRangeKm}`);
                },

                resetFilters() {
                    console.log('SIDEBAR RESET: Starting filter reset...');
                    // 1. Reset Alpine state
                    this.selectedMainCategory = '';
                    this.currentCityId = null;
                    this.currentCityName = '';
                    this.currentRangeKm = 5;
                    this.$dispatch('main-category-selected', { id: '' });

                    // 2. Reset standard form inputs
                    document.getElementById('filter-form').reset();

                    // 3. Clear Local Storage and hidden inputs for geo-filters
                    localStorage.removeItem('{{ $cityIdKey }}');
                    localStorage.removeItem('{{ $cityNameKey }}');
                    localStorage.removeItem('{{ $rangeKey }}');

                    document.getElementById('origin_city_id_filter').value = '';
                    document.getElementById('range_km_filter').value = '5';

                    console.log('SIDEBAR RESET: Local Storage and Inputs cleared.');

                    // 4. Dispatch the reset event to trigger component resets (including the slider)
                    this.$dispatch('filters-reset');
                }
            }"
            x-init="initializeState()"
            @city-selected.window="handleCitySelect"
            @range-changed.window="handleRangeChange"
        >
            {{-- 🆕 HIDDEN FIELDS FOR GEO-SEARCH --}}
            {{-- These are the inputs the main search script monitors --}}
            <input type="hidden" name="origin_city_id" id="origin_city_id_filter" value="">
            <input type="hidden" name="range_km" id="range_km_filter" value="">

            <div class="find-a-vehicle-inputs">
                {{-- 1. Main Category --}}
                <div class="form-group">
                    <label class="mb-medium">Main Category</label>
                    <x-search.search-main-category :main-categories="$mainCategories" x-model="selectedMainCategory" />
                </div>
                {{-- 2. Subcategory --}}
                <div class="form-group">
                    <label class="mb-medium">Subcategory</label>
                    <x-search.search-subcategory />
                </div>
                {{-- 3. Manufacturer --}}
                <div class="form-group">
                    <label class="mb-medium">Manufacturer</label>
                    <x-search.search-manufacturer name="manufacturer_id"/>
                </div>
                {{-- 4. Model --}}
                <div class="form-group">
                    <label class="mb-medium">Model</label>
                    <x-search.search-model name="model_id"/>
                </div>
                {{-- 5. Body Type --}}
                <div class="form-group">
                    <label class="mb-medium">Body Type</label>
                    <x-search.search-vehicle-type name="vehicle_type_id" />
                </div>
                {{-- 6. Year --}}
                <div class="form-group">
                    <label class="mb-medium">Year</label>
                    <div class="flex gap-1">
                        <input type="number" placeholder="Year From" name="year_from" class="select-input"/>
                        <input type="number" placeholder="Year To" name="year_to" class="select-input"/>
                    </div>
                </div>
                {{-- 7. Price --}}
                <div class="form-group">
                    <label class="mb-medium">Price</label>
                    <div class="flex gap-1">
                        <input type="number" placeholder="Price From" name="price_from" class="select-input"/>
                        <input type="number" placeholder="Price To" name="price_to" class="select-input"/>
                    </div>
                </div>
                {{-- 8. Mileage --}}
                <div class="form-group">
                    <label class="mb-medium">Mileage</label>
                    <div class="flex gap-1">
                        <x-search.search-mileage name="mileage"/>
                    </div>
                </div>

                {{-- 9. City (Geo Filter Location) --}}
                <div class="form-group">
                    <label class="mb-medium">City</label>
                    {{-- 🔑 IMPORTANT: Use origin_city_id name for consistency with geo filtering 🔑 --}}
                    {{-- The geo-search-modal uses 'origin_city_id' and the hidden input is 'origin_city_id_filter' --}}
                    <x-search.search-city name="origin_city_id"/>
                </div>

                {{-- 10. Search Distance (Geo Filter Range) --}}
                <div class="form-group">
                    <label class="mb-medium">Search Distance</label>
                    {{-- 🔑 IMPORTANT: The x-search-range-slider component handles its own state and reads from LS/hidden inputs --}}
                    <x-search.search-range-slider name="range_km" />
                </div>

                {{-- 11. Fuel Type --}}
                <div class="form-group">
                    <label class="mb-medium">Fuel Type</label>
                    <x-search.search-fuel-type :fuelTypes="$fuelTypes" />
                </div>
            </div>

            <div class="flex gap-1">
                <button
                    type="button"
                    class="btn btn-find-a-vehicle-reset"
                    id="reset-filters"
                    @click="resetFilters()"
                >
                    Reset
                </button>
                <button
                    type="button"
                    class="btn btn-primary btn-find-a-vehicle-submit"
                    id="apply-filters"
                    @click="applyFiltersAndPersist()"
                >
                    Search
                </button>
            </div>
        </form>
    </section>
</div>
