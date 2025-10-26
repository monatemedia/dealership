{{-- resources/views/components/interior-selector.blade.php --}}
@props(['interiors', 'defaultInterior' => null, 'value' => null])

@php
    $groupedInteriors = [];
    $selectedId = $value ?? $interiors->firstWhere('name', $defaultInterior)?->id;
    $selectedName = $interiors->firstWhere('id', $selectedId)?->name ?? 'Select Interior';

    foreach($interiors as $interior) {
        $groupName = $interior->interiorGroup->name ?? 'Other';
        if (!isset($groupedInteriors[$groupName])) {
            $groupedInteriors[$groupName] = [];
        }
        $groupedInteriors[$groupName][] = [
            'value' => $interior->id,
            'label' => $interior->name
        ];
    }
@endphp

<div
    x-data="itemSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}',
        modalRadioName: 'interior_modal'
    })"
    class="interior-selector">

    <input type="hidden" name="interior_id" x-model="selectedId" />
    <div
        @click="openModal"
        class="interior-input"
        :class="{
            'has-selection': selectedId !== null && selectedId !== '',
        }"
    >
        <span x-text="selectedName" class="interior-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Interior" max-width="600px">
        <p class="modal-subtitle">Choose the interior type and color for your vehicle</p>

        <x-grouped-radio-list
            :groups="$groupedInteriors"
            name="interior_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
