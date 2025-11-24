{{-- resources/views/components/search/search-range-slider.blade.php --}}
<?php
$cityIdKey = 'geo_filter_city_id';
$rangeKey = 'geo_filter_range_km';
?>

@props(['name' => 'range_km', 'maxRange' => 1000, 'cityEvent' => 'city-selected', 'provinceEvent' => 'province-selected'])

<div
    x-data="{
        // 1. STATE
        range: parseInt(document.getElementById('range_km_filter')?.value)
               || parseInt(localStorage.getItem('{{ $rangeKey }}'))
               || 5,
        maxRange: 1000,
        loadingMax: false,
        cityId: null,
        sliderInstance: null, // Holds the ionRangeSlider object (jQuery element)

        // 2. METHODS
        async fetchMaxRange(id) {
            const currentRangeBeforeFetch = this.range;
            const cityIdString = id !== null && id !== undefined ? String(id) : null;
            this.cityId = cityIdString;

            if (!this.cityId || this.loadingMax) {
                if (!this.cityId) {
                    this.maxRange = {{ $maxRange }};
                    this.range = 5;
                    // Update slider when max is reset to default (no city selected)
                    if (this.sliderInstance) {
                        this.sliderInstance.update({ max: this.maxRange, from: 5 });
                    }
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

                // CRITICAL: Update the slider UI when maxRange changes
                if (this.sliderInstance) {
                    this.sliderInstance.update({
                        max: this.maxRange,
                        from: this.range // Set the current value, adjusting if it was > newMax
                    });
                }
            } catch (error) {
                this.maxRange = 1500;
            } finally {
                this.loadingMax = false;
            }
        },

        // 🔑 ION.RANGESLIDER INITIALIZATION 🔑

        initializeSlider() {
            this.$nextTick(() => {
                const sliderElement = document.getElementById('ion-slider-{{ $name }}');

                // ⚠️ Ensure jQuery and the plugin are available
                if (!window.jQuery || typeof window.jQuery.fn.ionRangeSlider === 'undefined') {
                    setTimeout(() => this.initializeSlider(), 50);
                    return;
                }

                // Destroy any existing slider instance if it was already initialized
                const existingSlider = $(sliderElement).data('ionRangeSlider');
                if (existingSlider) {
                    existingSlider.destroy();
                }

                // Initialize ionRangeSlider
                $(sliderElement).ionRangeSlider({
                    type: 'single', // <-- CHANGED to single quotes for safety
                    min: 5,
                    max: this.maxRange,
                    from: this.range,
                    step: 1,
                    skin: 'flat', // <-- CHANGED to single quotes for safety

                    onFinish: (data) => {
                        this.range = Math.round(data.from);
                    },
                    onChange: (data) => {
                         this.range = Math.round(data.from);
                    }
                });

                // Store the IonRangeSlider instance reference
                this.sliderInstance = $(sliderElement).data('ionRangeSlider');
            });
        },

        resetSliderState() {
            console.log('SLIDER RESET: Executing resetSliderState (ionRangeSlider)...');
            this.range = 5;
            this.maxRange = {{ $maxRange }};
            this.cityId = null;

            // 🔑 CRITICAL: Use the ionRangeSlider API to force the visual change
            if (this.sliderInstance) {
                this.sliderInstance.update({
                    max: this.maxRange,
                    from: 5 // Set the handle position
                });
            }
        }
    }"
    x-init="
        initializeSlider();
        let cityIdValue = localStorage.getItem('{{ $cityIdKey }}');
        if (cityIdValue && cityIdValue !== 'null' && cityIdValue !== 'undefined') {
            this.cityId = cityIdValue;
            fetchMaxRange(cityIdValue);
        }

        $watch('range', (value) => {
            // Update hidden input when 'range' changes
            document.getElementById('range_km_filter').value = value;
            $dispatch('range-changed', { range: value });

            // Keep the ionRangeSlider handle visually synced if it was updated programmatically
            if (this.sliderInstance && this.sliderInstance.result.from != value) {
                 this.sliderInstance.update({ from: value });
            }
        });
    "
    x-on:{{ $cityEvent }}.window="
        fetchMaxRange($event.detail.id);
        $nextTick(() => {
            document.getElementById('range_km_filter').value = this.range;
        });
    "
    x-on:filters-reset.window="resetSliderState()">

    {{-- Hidden input keeps a stable ID for InstantSearch --}}
    <input type="hidden" name="range_km" :value="range" id="range_km_filter">

    <div class="flex justify-between mb-2">
        <label class="text-sm font-medium text-gray-700">
            Max Distance
            <span x-show="loadingMax" class="text-xs text-gray-500">(Calculating max...)</span>:
        </label>
        <span x-text="`${range} km`" class="font-bold text-primary"></span>
    </div>

    {{-- 🔑 The mounting point for Ion.RangeSlider. Changed ID to reflect the new library 🔑 --}}
    <input type="text" id="ion-slider-{{ $name }}" class="my-4" />

    <div class="flex justify-between text-xs text-gray-500 mt-1">
        <span>5 km</span>
        <span x-text="`${Math.round(maxRange)} km`"></span>
    </div>
</div>
