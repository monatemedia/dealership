{{-- resources/views/components/fuel-type-selector.blade.php --}}
@props(['fuelTypes', 'defaultFuelType' => null, 'value' => null])

@php
    // Group fuel types by their group
    $groupedFuelTypes = [];

    // The if ($isNoneScenario) check is GONE.
    // We just run the normal grouping logic for all cases.
    $selectedId = $value ?? $fuelTypes->firstWhere('name', $defaultFuelType)?->id;
    $selectedName = $fuelTypes->firstWhere('id', $selectedId)?->name ?? 'Select Fuel Type';

    foreach($fuelTypes as $fuelType) {
        // "None / Not Specified" will be put in its "None" group automatically
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
     x-data="itemSelector({ {{-- Renamed to generic 'itemSelector' --}}
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}',
        modalRadioName: 'fuel_type_modal'
    })"
    class="fuel-type-selector"> {{-- You can keep this class for styling --}}

    <input type="hidden" name="fuel_type_id" x-model="selectedId" />
    <div
         @click="openModal"
         class="fuel-type-input"
        :class="{
            'has-selection': selectedId !== null && selectedId !== '',
        }"
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
