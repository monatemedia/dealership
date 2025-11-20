{{-- resources/views/components/search/search-range-slider.blade.php --}}

@props(['name' => 'range_km', 'initialRange' => 5, 'maxRange' => 1000])

<div
    x-data="{
        range: {{ $initialRange }},
        maxRange: {{ $maxRange }},
        loadingMax: false,
        cityId: null,

        async fetchMaxRange() {
            if (!this.cityId || this.loadingMax) return;
            this.loadingMax = true;
            try {
                // Call the new API endpoint to get the maximum distance
                const response = await fetch(`/api/vehicles/max-range/${this.cityId}`);
                const data = await response.json();
                this.maxRange = data.max_range_km || 1000;
                // Ensure the current range doesn't exceed the new max
                this.range = Math.min(this.range, this.maxRange);
            } catch (error) {
                console.error('Error fetching max range:', error);
                // Fallback to a large, safe default
                this.maxRange = 1500;
            } finally {
                this.loadingMax = false;
            }
        },
        init() {
             // If a city is already selected on initialization, fetch the max range
             if (this.cityId) {
                this.fetchMaxRange();
             }
        }
    }"
    {{-- FIX: Moved event listener outside of the x-data object --}}
    @city-changed.window="cityId = $event.detail.id; fetchMaxRange()"
    {{-- FIX: Added parentheses to the init call --}}
    x-init="init()"
    class="range-slider-container"
>
    <input type="hidden" name="{{ $name }}" x-model="range">

    <div class="flex justify-between mb-2">
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700">Max Distance:</label>
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
