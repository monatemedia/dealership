{{-- resources/views/components/search/search-mileage.blade.php --}}
<?php
// Define common mileage tiers as raw numeric values
// We will format them into display strings inside the HTML
$mileageTiers = [
    10000,
    50000,
    100000,
    200000,
    300000,
];
?>
<div x-data="{
    open: false,
    selected: @js($attributes->get('value', '')),
    selectedName: 'Max Mileage',
    init() {
        // If a value is already set on load, format it for display
        if (this.selected) {
            @foreach ($mileageTiers as $value)
                if (this.selected == {{ $value }}) {
                    // We use number_format here to set the initial name correctly
                    this.selectedName = 'Under {{ number_format($value) }} km';
                }
            @endforeach
        }
    }
}" @click.away="open = false" class="select-container w-full">
    <input type="hidden" name="{{ $attributes->get('name', 'mileage') }}" x-model="selected">
    <button
        type="button"
        @click="open = !open"
        :class="{ 'select-button-active': open }"
        class="select-input flex justify-between items-center"
    >
         <span x-text="selectedName">Max Mileage</span>
        {{-- 🔑 FIX: Using custom CSS class for small icon size --}}
        <svg class="select-icon-sm transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div x-show="open" x-transition class="select-dropdown">
        <div class="select-list">
            <button type="button" @click="selected = ''; selectedName = 'Max Mileage'; open = false;" class="select-item select-item-clear">
                Clear Selection
            </button>
            @foreach ($mileageTiers as $value)
                <button
                    type="button"
                    {{-- Alpine is passed the raw number (e.g., 10000) --}}
                    {{-- We format the name string inside the @click handler too --}}
                    @click="selected = {{ $value }}; selectedName = 'Under {{ number_format($value) }} km'; open = false;"
                    :class="{ 'select-item-active': selected == {{ $value }} }"
                    class="select-item"
                >
                    {{-- Blade outputs the formatted number (e.g., 10,000) --}}
                    Under {{ number_format($value) }} km
                </button>
            @endforeach
        </div>
    </div>
</div>
