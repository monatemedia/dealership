{{-- resources/views/components/search/search-range-slider.blade.php --}}
<?php
$cityIdKey = 'geo_filter_city_id';
$rangeKey = 'geo_filter_range_km';
?>
@props(['name' => 'range_km', 'maxRange' => 1000, 'cityEvent' => 'city-selected', 'provinceEvent' => 'province-selected'])

<div
    x-data="{
        range: parseInt(localStorage.getItem('{{ $rangeKey }}') || document.getElementById('range_km_filter')?.value || 5),
        maxRange: 1000,
        loadingMax: false,
        cityId: null,

        async fetchMaxRange(id) {
            const currentRangeBeforeFetch = this.range;
            const cityIdString = id !== null && id !== undefined ? String(id) : null;
            this.cityId = cityIdString;

            if (!this.cityId || this.loadingMax) {
                if (!this.cityId) {
                    this.maxRange = {{ $maxRange }};
                    this.range = 5;
                }
                return;
            }

            this.loadingMax = true;
            try {
                const response = await fetch(`/api/vehicles/max-range/${this.cityId}`);
                const data = await response.json();
                const newMax = data.max_range_km || 1500;
                this.maxRange = newMax;

                if (currentRangeBeforeFetch > newMax) {
                    this.range = newMax;
                }

            } catch (error) {
                this.maxRange = 1500;
            } finally {
                this.loadingMax = false;
            }
        },

        resetSliderState() {
            this.range = 5;
            this.maxRange = {{ $maxRange }};
            this.cityId = null;
        }
    }"
    x-on:{{ $cityEvent }}.window="
        fetchMaxRange($event.detail.id);
        $nextTick(() => {
            document.getElementById('range_km_filter').value;
        });
    "
    x-on:{{ $provinceEvent ?? 'geo-province-selected' }}.window="
        resetSliderState();
    "
    x-on:filters-reset.window="resetSliderState()"
    x-init="
        let cityIdValue = localStorage.getItem('{{ $cityIdKey }}');

        if (cityIdValue && cityIdValue !== 'null' && cityIdValue !== 'undefined') {
            this.cityId = cityIdValue;
            fetchMaxRange(cityIdValue);
        }

        $watch('range', (value) => {
            $dispatch('range-changed', { range: value });
        });
    "
    class="py-2">
    <input type="hidden" name="range_km" :value="range" id="range_km_filter">

    <div class="flex justify-between mb-2">
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700">
            Max Distance
            <span x-show="loadingMax" class="text-xs text-gray-500">(Calculating max...)</span>:
        </label>
        <span x-text="`${range} km`" class="font-bold text-primary"></span>
    </div>

    <input
        type="range"
        id="{{ $name }}"
        x-model.number="range"
        :min="5"
        :max="maxRange"
        step="1"
        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
        style="--tw-bg-opacity: 1; --tw-text-opacity: 1; accent-color: var(--primary-color);"
    >

    <div class="flex justify-between text-xs text-gray-500 mt-1">
        <span>5 km</span>
        <span x-text="`${Math.round(maxRange)} km`"></span>
    </div>
</div>
