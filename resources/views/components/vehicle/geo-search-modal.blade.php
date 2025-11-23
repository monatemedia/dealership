{{-- resources/views/components/vehicle/geo-search-modal.blade.php (Drop-in Replacement) --}}
@php
    // Define Local Storage Keys (MUST match search-results-display.blade.php)
    $cityIdKey = 'geo_filter_city_id';
    $cityNameKey = 'geo_filter_city_name';
    $provinceNameKey = 'geo_filter_province_name';
    $rangeKey = 'geo_filter_range_km';
@endphp
<div
    x-data="{
        cityIdKey: '{{ $cityIdKey }}',
        cityNameKey: '{{ $cityNameKey }}',
        provinceNameKey: '{{ $provinceNameKey }}',
        rangeKey: '{{ $rangeKey }}',
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
            this.originCityName = event.detail.fullName;
            this.originProvinceName = event.detail.provinceName;
            console.log('handleCitySelect: ID:', this.originCityId);
        },

        handleRangeChange(event) {
            this.rangeKm = event.detail.range;
            console.log('🎚️ Modal received range update:', this.rangeKm);
        },

        applyLocationFilter() {
            // ... (Filter application logic remains the same) ...
            if (this.originCityId) {
                localStorage.setItem(this.cityIdKey, this.originCityId);
                localStorage.setItem(this.cityNameKey, this.originCityName);
                localStorage.setItem(this.provinceNameKey, this.originProvinceName);
                localStorage.setItem(this.rangeKey, this.rangeKm);
            } else {
                localStorage.removeItem(this.cityIdKey);
                localStorage.removeItem(this.cityNameKey);
                localStorage.removeItem(this.provinceNameKey);
                localStorage.removeItem(this.rangeKey);
            }

            const wrapperEl = document.getElementById('geo-display-wrapper');
            const cityEl = document.getElementById('geo-city-display');
            const rangeEl = document.getElementById('geo-range-display');

            let cityIdInput = document.getElementById('origin_city_id_filter');
            let rangeKmInput = document.getElementById('range_km_filter');

            if (cityIdInput) cityIdInput.value = this.originCityId || '';
            if (rangeKmInput) rangeKmInput.value = this.rangeKm;

            if (this.originCityId && this.originCityName) {
                if (wrapperEl) wrapperEl.classList.add('hidden');
                if (cityEl) {
                    cityEl.textContent = this.originCityName;
                    cityEl.classList.remove('hidden');
                }
                if (rangeEl) {
                    rangeEl.textContent = ` - ${this.rangeKm} km`;
                    rangeEl.classList.remove('hidden');
                }
            } else {
                if (wrapperEl) {
                    wrapperEl.textContent = 'Choose Location';
                    wrapperEl.classList.remove('hidden');
                }
                if (cityEl) cityEl.classList.add('hidden');
                if (rangeEl) rangeEl.classList.add('hidden');
            }

            document.getElementById('apply-filters')?.click();
            this.isOpen = false;
        },

        initializeState() {
            const cityId = document.getElementById('origin_city_id_filter')?.value;
            const range = document.getElementById('range_km_filter')?.value;

            const cityName = localStorage.getItem(this.cityNameKey);
            const provinceName = localStorage.getItem(this.provinceNameKey);

            if (cityId && cityId !== '' && cityId !== '0') {
                this.originCityId = parseInt(cityId);
                this.originCityName = cityName || '';
                this.originProvinceName = provinceName || '';
            } else {
                this.originCityId = null;
                this.originCityName = '';
                this.originProvinceName = '';
            }
            if (range) this.rangeKm = Number(range);
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
                    {{-- 2. Range Slider (Component not provided, assuming x-bind:initial-range is correct) --}}
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
