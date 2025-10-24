{{-- resources/views/components/grouped-radio-list.blade.php --}}
@props(['groups' => [], 'name' => 'selection', 'selected' => null])

<div class="grouped-list">
    @foreach($groups as $groupName => $items)
        @php
            // "None / Not Specified" is now a real item, so $items will never be an empty array
            // We just need to check if we should show the group header.
            $hasMultipleGroups = count($groups) > 1;
        @endphp

        <div class="grouped-list-section">
            @if($hasMultipleGroups && $groupName)
                <div class="grouped-list-header">
                    <h3>{{ $groupName }}</h3>
                </div>
            @endif

            <div class="grouped-list-items">
                {{-- The $isNoneOption logic is gone. We just loop. --}}
                @foreach($items as $item)
                    <label class="grouped-list-item">
                        <input
                            type="radio"
                            name="{{ $name }}"
                            value="{{ $item['value'] }}"
                            @checked($selected == $item['value'])
                        />
                        <span class="grouped-list-item-label">{{ $item['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
