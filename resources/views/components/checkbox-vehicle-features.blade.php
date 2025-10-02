@props(['vehicle' => null])

@php
    $features = config('features'); // ['ABS', 'Air Conditioning', ...]
    $chunks = collect($features)->chunk(ceil(count($features) / 2)); // split into 2 equal cols
@endphp

<div class="form-group @error('features') has-error @enderror">
    <div class="row">
        @foreach ($chunks as $chunk)
            <div class="col">
                @foreach ($chunk as $feature)
                    <label class="checkbox">
                        <input
                            type="checkbox"
                            name="features[]"
                            value="{{ $feature }}"
                            @checked(
                                in_array($feature, old('features', $vehicle?->features->pluck('name')->toArray() ?? []))
                            )
                        />
                        {{ $feature }}
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
