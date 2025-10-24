{{-- resources/views/components/fuel-type-selector.blade.php --}}
@props(['fuelTypes', 'defaultFuelType' => null, 'value' => null]) {{-- Removed hasNoneOption --}}

@php
    // Group fuel types by their group
    $groupedFuelTypes = [];

    // We infer the "None" scenario if the fuelTypes collection is empty
    $isNoneScenario = $fuelTypes->isEmpty();

    if ($isNoneScenario) {
        // Handle "None" option case
        $selectedId = $value ?? '';
        $selectedName = $defaultFuelType ?? 'None / Not Specified';
        $groupedFuelTypes['None'] = []; // This will render the "None / Not Specified" radio
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
    }
@endphp

<div
     x-data="fuelTypeSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}'
        {{-- hasNoneOption is gone --}}
    })"
    class="fuel-type-selector">

    {{-- ... rest of the component (no changes needed) ... --}}
    <input type="hidden" name="fuel_type_id" x-model="selectedId" />
    <div
         @click="openModal"
         class="fuel-type-input"
        :class="{ 'has-selection': selectedId !== null && selectedId !== '' }"
    >
        <span x-text="selectedName" class="fuel-type-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Fuel Type" max-width="600px">
        <p class="modal-subtitle">Choose the fuel type for your vehicle</p>

        <x-grouped-radio-list
             :groups="$groupedFuelTypes"
            name="fuel_type_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
