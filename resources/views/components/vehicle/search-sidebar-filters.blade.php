{{-- resources/views/components/vehicle/search-sidebar-filters.blade.php --}}
@props(['fuelTypes' => collect(), 'mainCategories' => collect()]) {{-- ASSUMING $mainCategories IS NOW PASSED --}}
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
        {{-- FIX: Added Alpine Wrapper --}}
        <form id="filter-form" class="find-a-vehicle-form card flex p-medium" x-data="{
            selectedMainCategory: '{{ request('main_category_id', '') }}', // Track Main Category ID

            // 🔑 NEW: Reset Function
            resetFilters() {
                // 1. Reset Main Category Model (resets the main category dropdown)
                this.selectedMainCategory = '';

                // 2. Dispatch the event that Subcategory listens to, forcing it to reset
                this.$dispatch('main-category-selected', { id: '' });

                // 3. Dispatch a secondary event for any other components that need a full reset
                this.$dispatch('filters-reset');

                // 4. Reset other standard form inputs (e.g., year, price, etc.)
                document.getElementById('filter-form').reset();
            }
        }">
            {{-- 🆕 HIDDEN FIELDS FOR GEO-SEARCH (Updated by the modal) --}}
            {{-- These are critical for VehicleInstantSearch.js to read the state --}}
            <input type="hidden" name="origin_city_id" id="origin_city_id_filter" value="{{ request('origin_city_id', 3212) }}">
            <input type="hidden" name="range_km" id="range_km_filter" value="{{ request('range_km', 5) }}">
            <div class="find-a-vehicle-inputs">
                <div class="form-group">
                    <label class="mb-medium">Main Category</label>
                    {{-- Pass $mainCategories and use x-model to bind selection to the shared state --}}
                    <x-search.search-main-category :main-categories="$mainCategories" x-model="selectedMainCategory" />
                </div>
                <div class="form-group">
                    <label class="mb-medium">Subcategory</label>
                    {{-- 🔑 FIX: Removed x-bind. Component now listens to event. --}}
                    <x-search.search-subcategory />
                </div>
                <div class="form-group">
                    <label class="mb-medium">Manufacturer</label>
                    {{-- Component Renamed --}}
                    <x-search.search-manufacturer name="manufacturer_id"/>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Model</label>
                    {{-- Component Renamed --}}
                    <x-search.search-model name="model_id"/>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Body Type</label>
                    {{-- 🔑 FIX: Removed x-bind. Component now listens to event. --}}
                    <x-search.search-vehicle-type name="vehicle_type_id" />
                </div>
                <div class="form-group">
                    <label class="mb-medium">Year</label>
                    <div class="flex gap-1">
                        <input type="number" placeholder="Year From" name="year_from" class="select-input"/> {{-- 🔑 FIX: Added select-input --}}
                        <input type="number" placeholder="Year To" name="year_to" class="select-input"/> {{-- 🔑 FIX: Added select-input --}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Price</label>
                    <div class="flex gap-1">
                        <input type="number" placeholder="Price From" name="price_from" class="select-input"/> {{-- 🔑 FIX: Added select-input --}}
                        <input type="number" placeholder="Price To" name="price_to" class="select-input"/> {{--   FIX: Added select-input --}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Mileage</label>
                    <div class="flex gap-1">
                        {{-- Component Renamed --}}
                        <x-search.search-mileage name="mileage"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-medium">Province</label>
                    {{-- Component Renamed --}}
                    <x-search.search-province name="province_id"/>
                </div>
                <div class="form-group">
                    <label class="mb-medium">City</label>
                    {{-- Component Renamed --}}
                    <x-search.search-city name="city_id" province-event="province-selected" city-event="city-filter-selected"/>
                </div>

                {{-- 🔑 NEW: Range Slider is now here, listening to 'city-filter-selected' --}}
                <div class="form-group">
                    <label class="mb-medium">Search Distance</label>
                    <x-search.search-range-slider name="range_km" initial-range="{{ request('range_km', 5) }}" city-event="city-filter-selected" />
                </div>

                <div class="form-group">
                    <label class="mb-medium">Fuel Type</label>
                {{-- 🔑 FIX: Removed x-bind. Component now listens to event. --}}
                <x-search.search-fuel-type :fuelTypes="$fuelTypes" />
                </div>
            </div>
            <div class="flex gap-1">
                <button
                    type="button"
                    class="btn btn-find-a-vehicle-reset"
                    id="reset-filters"
                    @click="resetFilters()" {{-- 🔑 NEW: Call reset function --}}
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
