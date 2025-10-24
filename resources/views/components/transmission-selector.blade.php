{{-- resources/views/components/transmission-selector.blade.php --}}
@props(['transmissions', 'defaultTransmission' => null, 'value' => null])

@php
    $groupedTransmissions = [];

    $selectedId = $value ?? $transmissions->firstWhere('name', $defaultTransmission)?->id;
    $selectedName = $transmissions->firstWhere('id', $selectedId)?->name ?? 'Select Transmission';

    foreach($transmissions as $item) {
        $groupName = $item->transmissionGroup->name ?? 'Other';
        if (!isset($groupedTransmissions[$groupName])) {
            $groupedTransmissions[$groupName] = [];
        }
        $groupedTransmissions[$groupName][] = [
            'value' => $item->id,
            'label' => $item->name
        ];
    }
@endphp

<div
     x-data="itemSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}'
    })"
    class="fuel-type-selector"> {{-- Re-use same class for styling --}}

    <input type="hidden" name="transmission_id" x-model="selectedId" />
    <div
         @click="openModal"
         class="fuel-type-input"
        :class="{ 'has-selection': selectedId !== null && selectedId !== '' }"
    >
        <span x-text="selectedName" class="fuel-type-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Transmission" max-width="600px">
        <p class="modal-subtitle">Choose the transmission for your vehicle</p>

        <x-grouped-radio-list
             :groups="$groupedTransmissions"
            name="transmission_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
