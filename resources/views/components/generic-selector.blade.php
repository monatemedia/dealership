{{-- resources/views/components/generic-selector.blade.php --}}
@props([
    'items',
    'defaultItem' => null,
    'value' => null,
    'name',
    'groupRelation',
    'label',
    'subtitle',
    'placeholder' => null
])

@php
    $groupedItems = [];
    $selectedId = $value ?? $items->firstWhere('name', $defaultItem)?->id;
    $selectedName = $items->firstWhere('id', $selectedId)?->name ?? ($placeholder ?? "Select {$label}");

    foreach($items as $item) {
        $groupName = $item->{$groupRelation}->name ?? 'Other';
        if (!isset($groupedItems[$groupName])) {
            $groupedItems[$groupName] = [];
        }
        $groupedItems[$groupName][] = [
            'value' => $item->id,
            'label' => $item->name
        ];
    }
@endphp

<div
    x-data="itemSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}',
        modalRadioName: '{{ $name }}_modal'
    })"
    class="generic-selector">

    <input type="hidden" name="{{ $name }}" x-model="selectedId" />
    <div
        @click="openModal"
        class="selector-input"
        :class="{ 'has-selection': selectedId !== null && selectedId !== '' }"
    >
        <span x-text="selectedName" class="selector-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay :title="'Select ' . $label" max-width="600px">
        <p class="modal-subtitle">{{ $subtitle }}</p>

        <x-grouped-radio-list
            :groups="$groupedItems"
            :name="$name . '_modal'"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
