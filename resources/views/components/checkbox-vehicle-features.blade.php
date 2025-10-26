{{-- resources/views/components/checkbox-vehicle-features.blade.php --}}

@props(['vehicle' => null, 'subcategory' => null])

@php
    // Get feature configuration for this subcategory
    $featureConfig = $subcategory?->getFeatureConfig() ?? ['can_edit' => true, 'groups' => collect([]), 'features' => collect([])];
    $canEdit = $featureConfig['can_edit'];
    $groupedFeatures = $featureConfig['groups'];
    $selectedFeatures = old('features', $vehicle?->features->pluck('name')->toArray() ?? []);
@endphp

@if($groupedFeatures->isNotEmpty())
    <div class="form-group @error('features') has-error @enderror">
        <label>Vehicle Features</label>

        @if(!$canEdit)
            <p class="text-muted">Features cannot be edited for this vehicle type.</p>
        @endif

        @foreach($groupedFeatures as $groupName => $features)
            <h4 style="margin-top: 1rem; margin-bottom: 0.5rem; font-weight: 600;">{{ $groupName }}</h4>

            <div class="row">
                @php
                    $chunks = $features->chunk(ceil($features->count() / 2));
                @endphp

                @foreach($chunks as $chunk)
                    <div class="col">
                        @foreach($chunk as $feature)
                            <label class="checkbox">
                                <input
                                    type="checkbox"
                                    name="features[]"
                                    value="{{ $feature->name }}"
                                    @checked(in_array($feature->name, $selectedFeatures))
                                    @disabled(!$canEdit)
                                />
                                {{ $feature->name }}
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach

        <p class="error-message">
            {{ $errors->first('features') }}
        </p>
    </div>
@endif
