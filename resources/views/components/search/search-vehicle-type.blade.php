{{-- resources/views/components/search/search-vehicle-type.blade.php --}}
@php
// Fix: Fetch the data directly in the component since it's a simple, static list.
$types = \App\Models\VehicleType::all();
@endphp
<div x-data="{
    open: false,
    selected: @js($attributes->get('value', '')),
    selectedName: 'Select Type'
}" @click.away="open = false" class="select-container w-full">
    <input type="hidden" name="{{ $attributes->get('name', 'vehicle_type_id') }}" x-model="selected">
    <button
        type="button"
        @click="open = !open"
        :class="{ 'select-button-active': open }"
        class="select-input flex justify-between items-center"
    >
         <span x-text="selectedName">Select Type</span>
        {{-- 🔑 FIX: Using custom CSS class for small icon size --}}
        <svg class="select-icon-sm transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div x-show="open" x-transition class="select-dropdown">
        <div class="select-list">
            <button type="button" @click="selected = ''; selectedName = 'Select Type'; open = false;" class="select-item select-item-clear">
                Clear Selection
            </button>
            @foreach ($types as $type)
                <button
                    type="button"
                    @click="selected = {{ $type->id }}; selectedName = '{{ $type->name }}'; open = false;"
                           :class="{ 'select-item-active': selected == {{ $type->id }} }"
                    class="select-item"
                >
                    {{ $type->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>
