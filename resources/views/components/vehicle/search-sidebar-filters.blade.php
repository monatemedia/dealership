{{-- resources/views/components/vehicle/search-sidebar-filters.blade.php --}}
@props(['fuelTypes' => collect(), 'mainCategories' => collect()])

@php
    // Define Local Storage Keys (MUST match geo-search-modal.blade.php)
    $cityIdKey = 'geo_filter_city_id';
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
        <form id="filter-form" class="find-a-vehicle-form card flex p-medium" x-data="{
            selectedMainCategory: '{{ request('main_category_id', '') }}',
            init() {
                console.log('SIDEBAR INIT: Reading geo-filters...');
                const storedCityId = localStorage.getItem('{{ $cityIdKey }}');
                const storedRangeKm = localStorage.getItem('{{ $rangeKey }}');

                document.getElementById('origin_city_id_filter').value = storedCityId || '{{ request('origin_city_id', '') }}';

                // 🔑 FIX 1: Only set the range input if a city is explicitly set (either stored or in request). Otherwise, leave it empty.
                const initialRange = (storedCityId || '{{ request('origin_city_id', '') }}') ? (storedRangeKm || '{{ request('range_km', 5) }}') : '';

                document.getElementById('range_km_filter').value = initialRange;
                console.log(`SIDEBAR INIT: origin_city_id_filter: ${document.getElementById('origin_city_id_filter').value}, range_km_filter: ${document.getElementById('range_km_filter').value}`);
            },
            resetFilters() {
                console.log('SIDEBAR RESET: Starting filter reset...');
                // 1. Reset Alpine state
                this.selectedMainCategory = '';
                this.$dispatch('main-category-selected', { id: '' });

                // 2. Reset standard form inputs (e.g., year, price, etc.)
                document.getElementById('filter-form').reset();

                // 3. Clear Local Storage and hidden inputs for geo-filters
                localStorage.removeItem('{{ $cityIdKey }}');
                localStorage.removeItem('{{ $rangeKey }}');
                document.getElementById('origin_city_id_filter').value = '';
                // 🔑 CRITICAL FIX 2: Set range to EMPTY STRING on reset.
                document.getElementById('range_km_filter').value = '';
                console.log(`SIDEBAR RESET: Inputs cleared. range_km_filter: ${document.getElementById('range_km_filter').value}`);

                // 4. Dispatch the reset event to trigger component resets (including the slider)
                this.$dispatch('filters-reset');
            }
        }" x-init="init()">
            {{-- 🆕 HIDDEN FIELDS FOR GEO-SEARCH (These will now be set in x-init) --}}
            <input type="hidden" name="origin_city_id" id="origin_city_id_filter" value="">
            <input type="hidden" name="range_km" id="range_km_filter" value="">

            <div class="find-a-vehicle-inputs">
                <div class="form-group">
                    <label class="mb-medium">Main Category</label>
                    <x-search.search-main-category :main-categories="$mainCategories" x-model="selectedMainCategory" />
                </div>
                <div class="form-group">
                    <label class="mb-medium">Subcategory</label>
                    <x-search.search-subcategory />
                </div>
                <div class="form-group">
                    <label class="mb-medium">Manufacturer</label>
                    <x-search.search-manufacturer name="manufacturer_id"/>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Model</label>
                    <x-search.search-model name="model_id"/>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Body Type</label>
                    <x-search.search-vehicle-type name="vehicle_type_id" />
                </div>
                <div class="form-group">
                    <label class="mb-medium">Year</label>
                    <div class="flex gap-1">
                        <input type="number" placeholder="Year From" name="year_from" class="select-input"/>
                        <input type="number" placeholder="Year To" name="year_to" class="select-input"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Price</label>
                    <div class="flex gap-1">
                        <input type="number" placeholder="Price From" name="price_from" class="select-input"/>
                        <input type="number" placeholder="Price To" name="price_to" class="select-input"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Mileage</label>
                    <div class="flex gap-1">
                        <x-search.search-mileage name="mileage"/>
                    </div>
                </div>

                {{-- 🗑️ DELETED: The 'Province' filter block is removed --}}
                {{-- 🗑️ DELETED: The original 'City' filter block is replaced below --}}

                <div class="form-group">
                    <label class="mb-medium">City</label>
                    {{-- 🔑 UPDATED: Use the standard component which reads from Local Storage --}}
                    {{-- Note: We MUST use the default 'city-selected' event here --}}
                    <x-search.search-city name="city_id"/>
                </div>

                <div class="form-group">
                    <label class="mb-medium">Search Distance</label>
                    {{-- 🔑 UPDATED: Use the standard component which reads from Local Storage and uses 'city-selected' --}}
                    <x-search.search-range-slider name="range_km" />
                </div>

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
                <button type="button" class="btn btn-primary btn-find-a-vehicle-submit" id="apply-filters">
                    Search
                </button>
            </div>
        </form>
    </section>
</div>
