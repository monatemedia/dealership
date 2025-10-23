{{-- resources/views/components/grouped-radio-list.blade.php --}}
@props(['groups' => [], 'name' => 'selection', 'selected' => null])

<div class="grouped-list">
    @foreach($groups as $groupName => $items)
        <div class="grouped-list-section">
            @if($groupName && count($groups) > 1)
                <div class="grouped-list-header">
                    <h3>{{ $groupName }}</h3>
                </div>
            @endif

            <div class="grouped-list-items">
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
