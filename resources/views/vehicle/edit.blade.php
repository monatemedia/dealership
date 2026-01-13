{{-- resources/views/vehicle/edit.blade.php --}}
@php
    // Get the singular form for the category
    $singular = $category->singular ?? 'Vehicle';
@endphp

<x-app-layout title="Edit {{ $vehicle->getTitle() }}">
    <main>
        <div class="container-small">

            {{-- Form --}}
            <h1 class="vehicle-details-page-title">
                Edit Your {{ $singular }}
            </h1>
            <form
                id="editVehicleForm"
                action="{{ route('vehicle.update', $vehicle) }}"
                method="POST"
                enctype="multipart/form-data"
                class="card add-new-vehicle-form"
            >
                @csrf
                @method('PUT')

                <div class="form-content">
                    <div class="form-details">
                        {{-- Category fields (hidden) --}}
                        <input type="hidden" name="section_id" value="{{ $section->id }}" />
                        <input type="hidden" name="category_id" value="{{ $category->id }}" />
                        {{-- End Category fields (hidden) --}}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <input type="text" readonly value="{{ $category->long_name }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Year --}}
                            <div class="col">
                                <div class="form-group @error('year') has-error @enderror">
                                    <label>Year</label>
                                    <x-select-year :value="old('year', $vehicle->year)" />
                                    <p class="error-message">
                                        {{ $errors->first('year') }}
                                    </p>
                                </div>
                            </div>
                            {{-- Manufacturer --}}
                            <div class="col">
                                <div class="form-group @error('manufacturer_id') has-error @enderror">
                                    <label>Manufacturer</label>
                                    <x-select-manufacturer :value="old('manufacturer_id', $vehicle->manufacturer_id)"/>
                                    <p class="error-message">
                                        {{ $errors->first('manufacturer_id') }}
                                    </p>
                                </div>
                            </div>
                            {{-- Model --}}
                            <div class="col">
                                <div class="form-group @error('model_id') has-error @enderror">
                                    <label>Model</label>
                                    <x-select-model :value="old('model_id', $vehicle->model_id)"/>
                                    <p class="error-message">
                                        {{ $errors->first('model_id') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group @error('vehicle_type_id') has-error @enderror">
                            <label>Vehicle Type</label>
                            <x-radio-list-vehicle-type
                                :category="$category"
                                :value="old('vehicle_type_id', $vehicle->vehicle_type_id)"
                            />
                            <p class="error-message">
                                {{ $errors->first('vehicle_type_id') }}
                            </p>
                        </div>

                        <div class="row">
                            {{-- Price --}}
                            <div class="col">
                                <div class="form-group @error('price') has-error @enderror">
                                    <label>Price</label>
                                    <input type="number" min="0" step="100" placeholder="Price" name="price"
                                        value="{{ old('price', $vehicle->price) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('price') }}
                                    </p>
                                </div>
                            </div>
                            {{-- VIN Code --}}
                            <div class="col">
                                <div class="form-group @error('vin') has-error @enderror">
                                    <label>Vin Code</label>
                                    <input placeholder="Vin Code" name="vin"
                                        value="{{ old('vin', $vehicle->vin) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('vin') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Odometer Reading --}}
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
                                            value="{{ old('mileage', $vehicle->mileage) }}"
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
                            {{-- Fuel Type --}}
                            <div class="col">
                                <div class="form-group @error('fuel_type_id') has-error @enderror">
                                    <label>Fuel Type</label>
                                    @if($canEditFuelType)
                                        <x-generic-selector
                                            :items="$fuelTypes"
                                            :defaultItem="$defaultFuelType"
                                            :value="old('fuel_type_id', $vehicle->fuel_type_id)"
                                            name="fuel_type_id"
                                            groupRelation="fuelTypeGroup"
                                            label="Fuel Type"
                                            subtitle="Choose the fuel type for your vehicle"
                                        />
                                    @else
                                        <input type="text" readonly value="{{ $vehicle->fuelType->name ?? $defaultFuelType ?? 'None / Not Specified' }}" class="readonly-input" />
                                        <input type="hidden" name="fuel_type_id" value="{{ $vehicle->fuel_type_id ?? $fuelTypes->firstWhere('name', $defaultFuelType)?->id ?? '' }}" />
                                    @endif
                                    <p class="error-message">{{ $errors->first('fuel_type_id') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Transmission --}}
                            <div class="col">
                                <div class="form-group @error('transmission_id') has-error @enderror">
                                    <label>Transmission</label>
                                    @if($canEditTransmission)
                                        <x-generic-selector
                                            :items="$transmissions"
                                            :defaultItem="$defaultTransmission"
                                            :value="old('transmission_id', $vehicle->transmission_id)"
                                            name="transmission_id"
                                            groupRelation="transmissionGroup"
                                            label="Transmission"
                                            subtitle="Choose the transmission for your vehicle"
                                        />
                                    @else
                                        <input type="text" readonly value="{{ $vehicle->transmission->name ?? $defaultTransmission ?? 'None / Not Specified' }}" class="readonly-input" />
                                        <input type="hidden" name="transmission_id" value="{{ $vehicle->transmission_id ?? $transmissions->firstWhere('name', $defaultTransmission)?->id ?? '' }}" />
                                    @endif
                                    <p class="error-message">{{ $errors->first('transmission_id') }}</p>
                                </div>
                            </div>
                            {{-- Drivetrain --}}
                            <div class="col">
                                <div class="form-group @error('drivetrain_id') has-error @enderror">
                                    <label>Drivetrain</label>
                                    @if($canEditDrivetrain)
                                        <x-generic-selector
                                            :items="$drivetrains"
                                            :defaultItem="$defaultDrivetrain"
                                            :value="old('drivetrain_id', $vehicle->drivetrain_id)"
                                            name="drivetrain_id"
                                            groupRelation="drivetrainGroup"
                                            label="Drivetrain"
                                            subtitle="Choose the drive train for your vehicle"
                                        />
                                    @else
                                        <input type="text" readonly value="{{ $vehicle->drivetrain->name ?? $defaultDrivetrain ?? 'None / Not Specified' }}" class="readonly-input" />
                                        <input type="hidden" name="drivetrain_id" value="{{ $vehicle->drivetrain_id ?? $drivetrains->firstWhere('name', $defaultDrivetrain)?->id ?? '' }}" />
                                    @endif
                                    <p class="error-message">{{ $errors->first('drivetrain_id') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Province --}}
                            <div class="col">
                                <div class="form-group @error('province_id') has-error @enderror">
                                    <label>Province/Region</label>
                                    <x-select-province :value="old('province_id', $vehicle->city->province_id)"/>
                                    <p class="error-message">
                                        {{ $errors->first('province_id') }}
                                    </p>
                                </div>
                            </div>
                            {{-- City --}}
                            <div class="col">
                                <div class="form-group @error('city_id') has-error @enderror">
                                    <label>City</label>
                                    <x-select-city :value="old('city_id', $vehicle->city_id)"/>
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
                                        value="{{ old('address', $vehicle->address) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('address') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('phone') has-error @enderror">
                                    <label>Phone</label>
                                    <input placeholder="Phone" name="phone"
                                        value="{{ old('phone', $vehicle->phone) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('phone') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group @error('description') has-error @enderror">
                            <label>Detailed Description</label>
                            <textarea rows="10" name="description">{{ old('description', $vehicle->description) }}</textarea>
                            <p class="error-message">
                                {{ $errors->first('description') }}
                            </p>
                        </div>

                        <div class="form-group @error('published_at') has-error @enderror" style="display: none;">
                            <label>Publish Date & Time</label>
                            <input
                                type="datetime-local"
                                name="published_at"
                                value="{{ old('published_at', $vehicle->published_at?->timezone('Africa/Johannesburg')->format('Y-m-d\TH:i')) }}"
                            >
                            <p class="error-message">{{ $errors->first('published_at') }}</p>
                        </div>

                        {{-- Materials & Condition Section --}}
                        <x-collapsible-section
                            title="Materials & Condition (Optional)"
                            :open="false"
                            storage-key="materials-condition-section"
                        >
                            <div class="row">
                                {{-- Exterior Color --}}
                                <div class="col">
                                    <div class="form-group @error('color_id') has-error @enderror">
                                        <label>Exterior Color</label>
                                        @if($canEditColor)
                                            <x-generic-selector
                                                :items="$colors"
                                                :defaultItem="$defaultColor"
                                                :value="old('color_id', $vehicle->color_id)"
                                                name="color_id"
                                                groupRelation="colorGroup"
                                                label="Exterior Color"
                                                subtitle="Choose the exterior color for your vehicle"
                                            />
                                        @else
                                            <input type="text" readonly value="{{ $vehicle->color->name ?? $defaultColor ?? 'None / Not Specified' }}" class="readonly-input" />
                                            <input type="hidden" name="color_id" value="{{ $vehicle->color_id ?? $colors->firstWhere('name', $defaultColor)?->id ?? '' }}" />
                                        @endif
                                        <p class="error-message">{{ $errors->first('color_id') }}</p>
                                    </div>
                                </div>

                                {{-- Interior --}}
                                <div class="col">
                                    <div class="form-group @error('interior_id') has-error @enderror">
                                        <label>Interior</label>
                                        @if($canEditInterior)
                                            <x-generic-selector
                                                :items="$interiors"
                                                :defaultItem="$defaultInterior"
                                                :value="old('interior_id', $vehicle->interior_id)"
                                                name="interior_id"
                                                groupRelation="interiorGroup"
                                                label="Interior"
                                                subtitle="Choose the interior type and color for your vehicle"
                                            />
                                        @else
                                            <input type="text" readonly value="{{ $vehicle->interior->name ?? $defaultInterior ?? 'None / Not Specified' }}" class="readonly-input" />
                                            <input type="hidden" name="interior_id" value="{{ $vehicle->interior_id ?? $interiors->firstWhere('name', $defaultInterior)?->id ?? '' }}" />
                                        @endif
                                        <p class="error-message">{{ $errors->first('interior_id') }}</p>
                                    </div>
                                </div>
                            </div>

                                {{-- Accident History --}}
                            <div class="row">
                                <div class="col">
                                    <div class="form-group @error('accident_history_id') has-error @enderror">
                                        <label>Accident History</label>
                                        @if($canEditAccidentHistory)
                                            <x-generic-selector
                                                :items="$accidentHistories"
                                                :defaultItem="$defaultAccidentHistory"
                                                :value="old('accident_history_id', $vehicle->accident_history_id)"
                                                name="accident_history_id"
                                                groupRelation="accidentHistoryGroup"
                                                label="Accident History"
                                                subtitle="Provide details about any accidents or damage"
                                            />
                                        @else
                                            <input type="text" readonly value="{{ $vehicle->accidentHistory->name ?? $defaultAccidentHistory ?? 'No Accidents Reported' }}" class="readonly-input" />
                                            <input type="hidden" name="accident_history_id" value="{{ $vehicle->accident_history_id ?? $accidentHistories->firstWhere('name', $defaultAccidentHistory)?->id ?? '' }}" />
                                        @endif
                                        <p class="error-message">{{ $errors->first('accident_history_id') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Exterior Condition --}}
                            <div class="form-group @error('exterior_condition_id') has-error @enderror">
                                <label>Exterior Condition</label>
                                <x-radio-list
                                    :items="$conditions"
                                    name="exterior_condition_id"
                                    :value="old('exterior_condition_id', $vehicle->exterior_condition_id)"
                                />
                                <p class="error-message">
                                    {{ $errors->first('exterior_condition_id') }}
                                </p>
                            </div>

                            {{-- Interior Condition --}}
                            <div class="form-group @error('interior_condition_id') has-error @enderror">
                                <label>Interior Condition</label>
                                <x-radio-list
                                    :items="$conditions"
                                    name="interior_condition_id"
                                    :value="old('interior_condition_id', $vehicle->interior_condition_id)"
                                />
                                <p class="error-message">
                                    {{ $errors->first('interior_condition_id') }}
                                </p>
                            </div>

                            {{-- Mechanical Condition --}}
                            <div class="form-group @error('mechanical_condition_id') has-error @enderror">
                                <label>Mechanical Condition</label>
                                <x-radio-list
                                    :items="$conditions"
                                    name="mechanical_condition_id"
                                    :value="old('mechanical_condition_id', $vehicle->mechanical_condition_id)"
                                />
                                <p class="error-message">
                                    {{ $errors->first('mechanical_condition_id') }}
                                </p>
                            </div>

                            {{-- Service History --}}
                            <div class="form-group @error('service_history_id') has-error @enderror">
                                <label>Service History</label>
                                <x-radio-list
                                    :items="$serviceHistories"
                                    name="service_history_id"
                                    :value="old('service_history_id', $vehicle->service_history_id)"
                                />
                                <p class="error-message">
                                    {{ $errors->first('service_history_id') }}
                                </p>
                            </div>

                        </x-collapsible-section>

                        {{-- Vehicle Features Section --}}
                        <x-collapsible-section
                            title="Vehicle Features (Optional)"
                            :open="false"
                            storage-key="vehicle-features-section"
                        >
                            <x-checkbox-vehicle-features
                                :category="$category"
                                :vehicle="$vehicle"
                            />
                        </x-collapsible-section>

                        {{-- Ownership & Documentation Section --}}
                        <x-collapsible-section
                            title="Ownership & Documentation (Optional)"
                            :open="false"
                            storage-key="ownership-paperwork-section"
                        >
                            <x-checkbox-ownership-paperwork :vehicle="$vehicle" />
                        </x-collapsible-section>

                    </div>
                    <div class="form-images">
                        {{-- Click to manage images --}}
                        <a
                            href="{{ route('vehicle.images', $vehicle) }}"
                            class="form-image-upload"
                        >
                            <div class="upload-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width: 48px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </a>

                        {{-- Current images preview --}}
                        <div class="vehicle-form-images">
                            @foreach($vehicle->images as $image)
                                <div class="vehicle-form-image-preview">
                                    <img src="{{ $image->getUrl() }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="p-medium" style="width: 100%">
                    <div class="flex justify-end gap-1">
                        <button type="button" class="btn btn-default">Reset</button>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
