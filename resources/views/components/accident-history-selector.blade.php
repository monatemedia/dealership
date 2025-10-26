{{-- resources/views/components/accident-history-selector.blade.php --}}
@props(['accidentHistories', 'defaultAccidentHistory' => null, 'value' => null])

@php
    $groupedAccidentHistories = [];
    $selectedId = $value ?? $accidentHistories->firstWhere('name', $defaultAccidentHistory)?->id;
    $selectedName = $accidentHistories->firstWhere('id', $selectedId)?->name ?? 'Select Accident History';

    foreach($accidentHistories as $accidentHistory) {
        $groupName = $accidentHistory->accidentHistoryGroup->name ?? 'Other';
        if (!isset($groupedAccidentHistories[$groupName])) {
            $groupedAccidentHistories[$groupName] = [];
        }
        $groupedAccidentHistories[$groupName][] = [
            'value' => $accidentHistory->id,
            'label' => $accidentHistory->name
        ];
    }
@endphp

<div
    x-data="itemSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}',
        modalRadioName: 'accident_history_modal'
    })"
    class="accident-history-selector">

    <input type="hidden" name="accident_history_id" x-model="selectedId" />
    <div
        @click="openModal"
        class="accident-history-input"
        :class="{
            'has-selection': selectedId !== null && selectedId !== '',
        }"
    >
        <span x-text="selectedName" class="accident-history-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Accident History" max-width="600px">
        <p class="modal-subtitle">Provide details about any accidents or damage</p>

        <x-grouped-radio-list
            :groups="$groupedAccidentHistories"
            name="accident_history_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
