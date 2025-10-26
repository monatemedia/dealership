{{-- resources/views/components/color-selector.blade.php --}}
@props(['colors', 'defaultColor' => null, 'value' => null])

@php
    $groupedColors = [];
    $selectedId = $value ?? $colors->firstWhere('name', $defaultColor)?->id;
    $selectedName = $colors->firstWhere('id', $selectedId)?->name ?? 'Select Color';

    foreach($colors as $color) {
        $groupName = $color->colorGroup->name ?? 'Other';
        if (!isset($groupedColors[$groupName])) {
            $groupedColors[$groupName] = [];
        }
        $groupedColors[$groupName][] = [
            'value' => $color->id,
            'label' => $color->name
        ];
    }
@endphp

<div
    x-data="itemSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}',
        modalRadioName: 'color_modal'
    })"
    class="color-selector">

    <input type="hidden" name="color_id" x-model="selectedId" />
    <div
        @click="openModal"
        class="color-input"
        :class="{
            'has-selection': selectedId !== null && selectedId !== '',
        }"
    >
        <span x-text="selectedName" class="color-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Exterior Color" max-width="600px">
        <p class="modal-subtitle">Choose the exterior color for your vehicle</p>

        <x-grouped-radio-list
            :groups="$groupedColors"
            name="color_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
