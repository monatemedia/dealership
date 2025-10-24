{{-- resources/views/components/grouped-radio-list.blade.php --}}
@props(['groups' => [], 'name' => 'selection', 'selected' => null])

<div class="grouped-list">
    @foreach($groups as $groupName => $items)
        @php
            // Check if this is an empty array (None option)
            $isNoneOption = empty($items);
            // Check if there's only one group (excluding empty None options)
            $hasMultipleGroups = count(array_filter($groups, fn($g) => !empty($g))) > 1;
            // Show header if: multiple groups OR (single group AND not empty)
            $showHeader = $hasMultipleGroups || (!$isNoneOption && $groupName);
        @endphp

        <div class="grouped-list-section">
            @if($showHeader)
                <div class="grouped-list-header">
                    <h3>{{ $groupName }}</h3>
                </div>
            @endif

            <div class="grouped-list-items">
                @if($isNoneOption)
                    {{-- Special case for empty array - show "None / Not Specified" --}}
                    <label class="grouped-list-item">
                        <input
                            type="radio"
                            name="{{ $name }}"
                            value=""
                            @checked($selected === '' || $selected === null)
                        />
                        <span class="grouped-list-item-label">None / Not Specified</span>
                    </label>
                @else
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
                @endif
            </div>
        </div>
    @endforeach
</div>
