{{-- resources/views/components/fuel-type-selector.blade.php --}}
@props(['fuelTypes', 'defaultFuelType' => null, 'value' => null])

@php
    // Determine selected fuel type
    $selectedId = $value ?? $fuelTypes->firstWhere('name', $defaultFuelType)?->id;
    $selectedName = $fuelTypes->firstWhere('id', $selectedId)?->name ?? 'Select Fuel Type';

    // Group fuel types by their group
    $groupedFuelTypes = [];
    foreach($fuelTypes as $fuelType) {
        $groupName = $fuelType->fuelTypeGroup->name ?? 'Other';
        if (!isset($groupedFuelTypes[$groupName])) {
            $groupedFuelTypes[$groupName] = [];
        }
        $groupedFuelTypes[$groupName][] = [
            'value' => $fuelType->id,
            'label' => $fuelType->name
        ];
    }
@endphp

<div
    x-data="fuelTypeSelector({
        selectedId: {{ $selectedId ? "'{$selectedId}'" : 'null' }},
        selectedName: '{{ $selectedName }}'
    })"
    class="fuel-type-selector"
>
    {{-- Hidden input to store the actual value --}}
    <input type="hidden" name="fuel_type_id" x-model="selectedId" />

    {{-- Clickable display input --}}
    <div
        @click="openModal"
        class="fuel-type-input"
        :class="{ 'has-selection': selectedId }"
    >
        <span x-text="selectedName" class="fuel-type-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    {{-- Modal using the reusable component --}}
    <x-modal-overlay title="Select Fuel Type" max-width="600px">
        <x-grouped-radio-list
            :groups="$groupedFuelTypes"
            name="fuel_type_modal"
            :selected="$selectedId"
        />

        <div class="modal-footer">
            <button type="button" @click="confirmSelection" class="btn btn-primary">
                <i class="fa-solid fa-check"></i> Submit
            </button>
        </div>
    </x-modal-overlay>
</div>
