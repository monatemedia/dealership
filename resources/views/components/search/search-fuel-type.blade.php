{{-- resources/views/components/search/search-fuel-type.blade.php --}}
@props(['fuelTypes'])

<div class="select-container"> {{-- Changed from custom-select-wrapper to select-container --}}
    {{-- This component is now a standard HTML select element --}}
    <select name="fuel_type_id" class="select-input"> {{-- Added select-input for standard styling --}}
        <option value="">Fuel Type</option>
        @foreach ($fuelTypes as $fuelType)
            <option
                value="{{ $fuelType->id }}"
                @selected($attributes->get('value') == $fuelType->id)>
                {{ $fuelType->name}}
            </option>
        @endforeach
    </select>
</div>
