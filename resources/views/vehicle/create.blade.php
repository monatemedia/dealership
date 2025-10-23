{{-- resources/views/vehicle/create.blade.php --}}

@php
    // DEBUG: Check what we have
    // if (!isset($subCategory)) {
    //     dd('$subCategory is not set');
    // }
    // if (is_null($subCategory)) {
    //     dd('$subCategory is null');
    // }
    // if (!isset($mainCategory)) {
    //     dd('$mainCategory is not set');
    // }
    // if (is_null($mainCategory)) {
    //     dd('$mainCategory is null', compact('subCategory'));
    // }

    // If we get here, both exist
    $singular = $subCategory->singular ?? 'Vehicle';
@endphp

<x-app-layout title="Add New Vehicle">
    <main>
        {{-- @dump($singular) --}}
        {{-- @dd($vehicleTypes); --}}

        {{-- Add Alpine.js data store for modal management --}}
        <div class="container-small" x-data="{ isModalOpen: false }" @close-modal.window="isModalOpen = false">

            {{-- Modal backdrop and content --}}
            <div x-show="isModalOpen" class="modal-backdrop" style="display: none;">
                <div x-show="isModalOpen" class="modal-content" @click.outside="isModalOpen = false" style="display: none;">
                    {{--
                        The sortable images component is now inside the modal.
                        - mode="modal" tells the JS to handle it differently.
                        - :vehicle="null" is passed since we are in create mode.
                    --}}
                    <h2>Add Images</h2>
                    <x-sortable-vehicle-images :vehicle="null" mode="modal" />
                </div>
            </div>

            {{-- Debugging dump --}}
            {{-- @dump($subCategory) --}}

            {{-- Form --}}
            <h1 class="vehicle-details-page-title">
            Selling Your {{ $singular }}
            </h1>
            <form
                id="createVehicleForm"
                action="{{ route('vehicle.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="card add-new-vehicle-form"
            >
                @csrf
                {{-- @dump($errors) --}}
                <div class="form-content">
                    <div class="form-details">
                        {{-- Category fields (hidden) --}}
                        <input type="hidden" name="main_category_id" value="{{ $mainCategory->id }}" />
                        <input type="hidden" name="sub_category_id" value="{{ $subCategory->id }}" />
                        {{-- End Category fields (hidden) --}}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <input type="text" readonly value="{{ $subCategory->long_name }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('year') has-error @enderror">
                                    <label>Year</label>
                                    <x-select-year :value="old('year')" />
                                    <p class="error-message">
                                        {{ $errors->first('year') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('manufacturer_id') has-error @enderror">
                                    <label>Manufacturer</label>
                                    <x-select-manufacturer :value="old('manufacturer_id')"/>
                                    <p class="error-message">
                                        {{ $errors->first('manufacturer_id') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('model_id') has-error @enderror">
                                    <label>Model</label>
                                    <x-select-model :value="old('model_id')"/>
                                    <p class="error-message">
                                        {{ $errors->first('model_id') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @error('vehicle_type_id') has-error @enderror">
                            <label>Vehicle Type</label>
                            <x-radio-list-vehicle-type
                                :sub-category="$subCategory"
                                :value="old('vehicle_type_id')"
                            />
                            <p class="error-message">
                                {{ $errors->first('vehicle_type_id') }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('price') has-error @enderror">
                                    <label>Price</label>
                                    <input type="number" min="0" step="100" placeholder="Price" name="price"
                                        value="{{ old('price') }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('price') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('vin') has-error @enderror">
                                    <label>Vin Code</label>
                                    <input placeholder="Vin Code" name="vin"
                                        value="{{ old('vin') }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('vin') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mileage-input-group @error('mileage') has-error @enderror">
                                    <label class="mileage-label">Odometer Reading (km)</label>
                                    <div class="mileage-input-wrapper">
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            class="mileage-input"
                                            placeholder="Enter reading"
                                            name="mileage"
                                            value="{{ old('mileage') }}"
                                            data-unit="km"
                                        />
                                        <span class="unit-toggle" data-unit="km">km</span>
                                    </div>
                                    <p class="helper-text">Click (km) unit to change to (mi) or (hrs)</p>
                                    <input type="hidden" name="mileage_unit" value="km" class="mileage-unit-input">
                                    <p class="error-message">
                                        {{ $errors->first('mileage') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @error('fuel_type_id') has-error @enderror">
                            <label>Fuel Type</label>
                            @if($canEditFuelType)
                                <x-fuel-type-selector
                                    :fuelTypes="$fuelTypes"
                                    :defaultFuelType="$defaultFuelType"
                                    :value="old('fuel_type_id')"
                                />
                            @else
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $defaultFuelType }}"
                                    class="readonly-input"
                                />
                                <input
                                    type="hidden"
                                    name="fuel_type_id"
                                    value="{{ $fuelTypes->firstWhere('name', $defaultFuelType)?->id }}"
                                />
                            @endif
                            <p class="error-message">
                                {{ $errors->first('fuel_type_id') }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('province_id') has-error @enderror">
                                    <label>Province/Region</label>
                                    <x-select-province :value="old('province_id')"/>
                                    <p class="error-message">
                                        {{ $errors->first('province_id') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('city_id') has-error @enderror">
                                    <label>City</label>
                                    <x-select-city :value="old('city_id')"/>
                                    <p class="error-message">
                                        {{ $errors->first('city_id') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('address') has-error @enderror">
                                    <label>Address</label>
                                    <input placeholder="Address" name="address"
                                        value="{{ old('address') }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('address') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('phone') has-error @enderror">
                                    <label>Phone</label>
                                    <input placeholder="Phone" name="phone"
                                        value="{{ old('phone') }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('phone') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <x-checkbox-vehicle-features />

                        <div class="form-group @error('description') has-error @enderror">
                            <label>Detailed Description</label>
                            <textarea rows="10" name="description">{{ old('description') }}</textarea>
                            <p class="error-message">
                                {{ $errors->first('description') }}
                            </p>
                        </div>
                        <div class="form-group @error('published_at') has-error @enderror">
                            <label>Publish Date & Time</label>
                            <input
                                type="datetime-local"
                                name="published_at"
                                value="{{ old('published_at') }}"
                            >
                            <p class="error-message">{{ $errors->first('published_at') }}</p>
                        </div>
                    </div>
                    <div class="form-images">
                        @foreach($errors->get('images.*') as $imageErrors)
                            @foreach($imageErrors as $err)
                                <div class="text-error mb-small">{{ $err }}</div>
                            @endforeach
                        @endforeach

                        {{-- This div opens the modal AND dispatches an event --}}
                        <div class="form-image-upload"
                            @click="isModalOpen = true;
                            window.dispatchEvent(new CustomEvent('open-image-modal'))"
                        >
                            <div class="upload-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width: 48px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                {{-- <span>Add/Edit Images</span> --}}
                            </div>
                        </div>

                        {{--
                           This hidden input will be populated by the modal's JS.
                           It's what gets submitted with the form.
                        --}}
                        <input id="vehicleFormImageUpload" type="file" name="images[]" multiple hidden />

                        {{-- This div will be populated with image previews by the modal's JS --}}
                        <div id="imagePreviews" class="vehicle-form-images"></div>
                    </div>
                </div>
                <div class="p-medium" style="width: 100%">
                    <div class="flex justify-end gap-1">
                        <button type="button" class="btn btn-default">Reset</button>
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
