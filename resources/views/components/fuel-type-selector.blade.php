{{-- resources/views/components/fuel-type-selector.blade.php --}}
@props(['fuelTypes', 'defaultFuelType' => null, 'value' => null, 'hasNoneOption' => false])

@php
    // Group fuel types by their group
    $groupedFuelTypes = [];

    if ($hasNoneOption || $fuelTypes->isEmpty()) {
        // Handle "None" option case
        $selectedId = $value ?? '';
        $selectedName = $defaultFuelType ?? 'None / Not Specified';
        $groupedFuelTypes['None'] = [];
    } else {
        // Normal grouping
        $selectedId = $value ?? $fuelTypes->firstWhere('name', $defaultFuelType)?->id;
        $selectedName = $fuelTypes->firstWhere('id', $selectedId)?->name ?? 'Select Fuel Type';

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

        // Add "None" option if it exists in any fuel type's groups
        foreach($fuelTypes as $fuelType) {
            if ($fuelType->fuelTypeGroup->name === 'None') {
                $groupedFuelTypes['None'] = [];
                break;
            }
        }
    }
@endphp

<div
    x-data="fuelTypeSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}',
        hasNoneOption: {{ ($hasNoneOption || isset($groupedFuelTypes['None'])) ? 'true' : 'false' }}
    })"
    class="fuel-type-selector"
>
    {{-- Hidden input to store the actual value --}}
    <input type="hidden" name="fuel_type_id" x-model="selectedId" />

    {{-- Clickable display input --}}
    <div
        @click="openModal"
        class="fuel-type-input"
        :class="{ 'has-selection': selectedId !== null && selectedId !== '' }"
    >
        <span x-text="selectedName" class="fuel-type-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    {{-- Modal using the reusable component --}}
    <x-modal-overlay title="Select Fuel Type" max-width="600px">
        <p class="modal-subtitle">Choose the fuel type for your vehicle</p>

        <x-grouped-radio-list
            :groups="$groupedFuelTypes"
            name="fuel_type_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
