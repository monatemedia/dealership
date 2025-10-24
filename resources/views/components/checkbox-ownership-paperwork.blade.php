{{-- resources/views/components/checkbox-ownership-paperwork.blade.php --}}

@props(['vehicle' => null])

@php
    $paperwork = config('ownership_paperwork.ownership_paperwork');
    $selectedPaperwork = old('ownership_paperwork', $vehicle?->ownershipPaperwork->pluck('name')->toArray() ?? []);
@endphp

<div class="form-group @error('ownership_paperwork') has-error @enderror">
    <label>Ownership & Documentation</label>

    @foreach ($paperwork as $category => $items)
        @if ($category !== 'None' && count($items) > 0)
            <h4 style="margin-top: 1rem; margin-bottom: 0.5rem; font-weight: 600;">{{ $category }}</h4>

            <div class="row">
                @php
                    $chunks = collect($items)->chunk(ceil(count($items) / 2));
                @endphp

                @foreach ($chunks as $chunk)
                    <div class="col">
                        @foreach ($chunk as $item)
                            <label class="checkbox">
                                <input
                                    type="checkbox"
                                    name="ownership_paperwork[]"
                                    value="{{ $item }}"
                                    @checked(in_array($item, $selectedPaperwork))
                                />
                                {{ $item }}
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <p class="error-message">
        {{ $errors->first('ownership_paperwork') }}
    </p>
</div>
