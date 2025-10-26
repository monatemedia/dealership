{{-- resources/views/components/radio-list.blade.php --}}
<div class="row">
    @foreach ($items as $item)
        <div class="col">
            <label class="inline-radio">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $item->id }}"
                    @checked($value == $item->id)
                />
                {{ $item->name }}
            </label>
        </div>

        {{-- Split into columns of four --}}
        @if ($loop->iteration % 4 == 0 && !$loop->last)
            </div>
            <div class="row">
        @endif
    @endforeach
</div>
