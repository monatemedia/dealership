<x-app-layout title="Edit {{ $vehicle->getTitle() }}">
    <main>
        <div class="container-small">
            <h1 class="vehicle-details-page-title">
                Edit Vehicle: {{ $vehicle->getTitle() }}
            </h1>
            <form
                action="{{ route('vehicle.update', $vehicle) }}"
                method="POST"
                enctype="multipart/form-data"
                class="card add-new-vehicle-form"
            >
                @csrf
                @method('PUT')
                <div class="form-content">
                    {{-- @dump($vehicle) --}}
                    <div class="form-details">
                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('vehicle_category_id') has-error @enderror">
                                    <label>Vehicle Category</label>
                                    <x-select-vehicle-category :value="old('vehicle_category_id', $vehicle->vehicle_category_id)"/>
                                    <p class="error-message">
                                        {{ $errors->first('vehicle_category_id') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('year') has-error @enderror">
                                    <label>Year</label>
                                    <x-select-year :value="old('year', $vehicle->year)"/>
                                    <p class="error-message">
                                        {{ $errors->first('year') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('manufacturer_id') has-error @enderror">
                                    <label>Manufacturer</label>
                                    <x-select-manufacturer :value="old('manufacturer_id', $vehicle->manufacturer_id)"/>
                                    <p class="error-message">
                                        {{ $errors->first('manufacturer_id') }}
                                    </p>
                                </div>
                            </div>
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
                            <x-radio-list-vehicle-type :value="old('vehicle_type_id', $vehicle->vehicle_type_id)" />
                            <p class="error-message">
                                {{ $errors->first('vehicle_type_id') }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group @error('price') has-error @enderror">
                                    <label>Price</label>
                                    <input type="number" min="0" step="100" name="price"
                                        value="{{ old('price', $vehicle->price) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('price') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('vin') has-error @enderror">
                                    <label>Vin Code</label>
                                    <input type="number" placeholder="Vin Code" name="vin"
                                        value="{{ old('vin', $vehicle->vin) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('vin') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('mileage') has-error @enderror">
                                    <label>Mileage (km)</label>
                                    <input type="number" min="0" step="1000" name="mileage"
                                        value="{{ old('mileage', $vehicle->mileage) }}"/>
                                    <p class="error-message">
                                        {{ $errors->first('mileage') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @error('fuel_type_id') has-error @enderror">
                            <label>Fuel Type</label>
                            <x-radio-list-fuel-type :value="old('fuel_type_id', $vehicle->fuel_type_id)"/>
                            <p class="error-message">
                                {{ $errors->first('fuel_type_id') }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Province</label>
                                    <x-select-province
                                        :value="old('province_id', $vehicle->city->province_id)"
                                    />
                                </div>
                            </div>
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
                            <x-checkbox-vehicle-features :$vehicle/>
                        <div class="form-group @error('description') has-error @enderror">
                            <label>Detailed Description</label>
                            <textarea rows="10" name="description">{{ old('description', $vehicle->description) }}</textarea>
                            <p class="error-message">
                                {{ $errors->first('description') }}
                            </p>
                        </div>
                        <div class="form-group @error('published_at') has-error @enderror">
                            <label>Publish Date</label>
                            <input type="date" name="published_at"
                            value="{{ old('published_at', $vehicle->published_at?->toDateString()) }}">
                            <p class="error-message">
                                {{ $errors->first('published_at') }}
                            </p>
                        </div>
                    </div>
                    <div class="form-images">
                        <p>
                            Manage your images
                            <a href="{{ route('vehicle.images', $vehicle) }}">from here</a>
                        </p>
                        <div class="vehicle-form-images">
                            @foreach($vehicle->images as $image)
                                <a href="#" class="vehicle-form-image-preview">
                                    <img src="{{ $image->getUrl() }}" alt="">
                                </a>
                            @endforeach
                        </div>
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
