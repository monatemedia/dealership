{{-- resources/views/components/search/search-range-slider.blade.php --}}
@props(['name' => 'range_km', 'initialRange' => 5, 'maxRange' => 1000, 'cityEvent' => 'city-changed'])
<div
    x-data="{
        range: {{ $initialRange }},
        maxRange: {{ $maxRange }},
        loadingMax: false,
        cityId: null,

        async fetchMaxRange(id) {
            // Convert ID to string if null/undefined, otherwise keep as is
            const cityIdString = id !== null && id !== undefined ? String(id) : null;

            // 1. Update component state and check if a fetch is needed
            this.cityId = cityIdString;
            if (!this.cityId || this.loadingMax) {
                // If city is cleared or null, reset maxRange to default and update range
                if (!this.cityId) {
                    this.maxRange = {{ $maxRange }};
                    this.range = 5;
                }
                return;
            }

            this.loadingMax = true;
            try {
                // Call the new API endpoint to get the maximum distance
                const response = await fetch(`/api/vehicles/max-range/${this.cityId}`);
                const data = await response.json();

                // Use the returned max_range_km or a large, safe default if the API fails
                console.log('MAX RANGE API RESPONSE:', data.max_range_km);
                this.maxRange = data.max_range_km || 1500;

                // Ensure the current range doesn't exceed the new max
                this.range = Math.min(this.range, this.maxRange);

                // Manually trigger an input change to update the hidden field immediately
                this.$el.querySelector('input[type=range]').dispatchEvent(new Event('input'));
            } catch (error) {
                console.error('Error fetching max range:', error);
                this.maxRange = 1500;
            } finally {
                this.loadingMax = false;
            }
        },

        resetSliderState() {
            this.range = 5;
            this.maxRange = {{ $maxRange }};
            this.cityId = null;
            // Ensure the hidden field updates immediately when the parent form resets.
            this.$el.querySelector('input[type=range]').dispatchEvent(new Event('input'));
        }
    }"

    x-on:{{ $cityEvent }}.window="
        console.log('RANGE SLIDER RECEIVED CITY EVENT:', $event.detail.id);
        fetchMaxRange($event.detail.id);
    "

    x-on:filters-reset.window="resetSliderState()"

    x-init="
        // 🔑 CRITICAL FIX: Wrapping logic in an IIFE to provide a safe scope for 'const' declarations,
        // which resolves the 'expected expression, got keyword' syntax error in Alpine.
        (() => {
            const initialCityId = document.getElementById('origin_city_id_filter')?.value;
            const initialRange = document.getElementById('range_km_filter')?.value;

            if (initialCityId && initialCityId !== 'null' && initialCityId !== 'undefined') {
                // Ensure range is an integer
                this.range = parseInt(initialRange || this.range);
                // Fetch max range for the initial city, if set
                this.fetchMaxRange(initialCityId);
            } else {
                // If no city is pre-selected, set to the 5km default
                this.range = 5;
                // The maxRange will remain the initial prop default (1000)
            }
        })();
    "
    class="range-slider-container">
    <input type="hidden" name="{{ $name }}" x-model="range">
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
