{{-- resources/views/components/search/search-range-slider.blade.php --}}
<?php
/** @var string $name The name of the input field (e.g., 'range_km') */
/** @var int $initialRange The initial value for the range slider */
/** @var int $maxRange The default maximum value for the range slider */
/** @var string $cityEvent The event name to listen for city changes */
?>
@props(['name' => 'range_km', 'initialRange' => 5, 'maxRange' => 1000, 'cityEvent' => 'city-changed', 'provinceEvent' => 'province-selected'])

<div
    x-data="{
        range: {{ $initialRange }},
        maxRange: {{ $maxRange }},
        loadingMax: false,
        cityId: null,

        async fetchMaxRange(id) {
            // Store the current range before potential modification
            const currentRangeBeforeFetch = this.range;

            // Convert ID to string if null/undefined, otherwise keep as is
            const cityIdString = id !== null && id !== undefined ? String(id) : null;
            this.cityId = cityIdString;

            if (!this.cityId || this.loadingMax) {
                if (!this.cityId) {
                    this.maxRange = {{ $maxRange }};
                    // Reset range to default 5 if city is cleared
                    this.range = 5;
                }
                return;
            }
            this.loadingMax = true;
            try {
                const response = await fetch(`/api/vehicles/max-range/${this.cityId}`);
                const data = await response.json();
                console.log('MAX RANGE API RESPONSE:', data.max_range_km);

                const newMax = data.max_range_km || 1500;
                this.maxRange = newMax;

                // 💡 Improvement: Only adjust 'range' if it exceeds the new max,
                // otherwise keep the user's current slider setting (e.g., 354 km).
                if (currentRangeBeforeFetch > newMax) {
                    this.range = newMax;
                    console.log(`Range adjusted down to ${newMax} km.`);
                }

                // Log the final state value
                console.log('Range state after fetchMaxRange update:', this.range);

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
        }
    }"
    x-on:{{ $cityEvent }}.window="
        console.log('RANGE SLIDER RECEIVED CITY EVENT:', $event.detail.id);

        // 1. Start the Max Range calculation (which might update 'range' state)
        fetchMaxRange($event.detail.id);

        // 🟢 CRITICAL FIX: Use $nextTick to ensure the Alpine 'range' state
        // has finished propagating to the hidden input's DOM value before the
        // external filter application logic reads it.
        $nextTick(() => {
            const finalRange = document.getElementById('range_km_filter').value;
            console.log('Range Hidden Input value after $nextTick:', finalRange);
        });
    "
    x-on:{{ $provinceEvent ?? 'geo-province-selected' }}.window="
        console.log('🗺️ RANGE SLIDER: Province changed, resetting slider');
        resetSliderState();
    "
    x-on:filters-reset.window="resetSliderState()"
    x-init="
        // CRITICAL FIX: IIFE wrapper for safe scope.
        (() => {
            const rangeInput = document.getElementById('range_km_filter');
            const initialCityId = document.getElementById('origin_city_id_filter')?.value;
            const initialRange = rangeInput ? rangeInput.value : null;

            if (initialCityId && initialCityId !== 'null' && initialCityId !== 'undefined') {
                // Ensure range is an integer, falling back to initialRange prop if input is empty
                this.range = parseInt(initialRange || this.range);
                // Fetch max range for the initial city, if set
                fetchMaxRange(initialCityId);
            } else {
                this.range = 5;
            }
        })();

        // 🔑 NEW: Watch for range changes and dispatch event for parent components
        $watch('range', (value) => {
            console.log('📏 Slider range changed to:', value);
            // Dispatch a custom event that the modal can listen to
            $dispatch('range-changed', { range: value });
        });
    "
    class="py-2">

    {{-- The hidden input uses :value, binding directly to the 'range' state --}}
    <input type="hidden" name="{{ $name }}" :value="range" id="range_km_filter">

    <div class="flex justify-between mb-2">
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700">
            Max Distance
            <span x-show="loadingMax" class="text-xs text-gray-500">(Calculating max...)</span>:
        </label>
        {{-- Display the live slider value --}}
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
