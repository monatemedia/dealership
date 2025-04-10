<x-app-layout>
    <main>
        <div class="container-small">
            <h1 class="car-details-page-title">Add new car</h1>
            <form
                action="{{ route('car.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="card add-new-car-form"
            >
                @csrf
                @dump($errors)
                <div class="form-content">
                    <div class="form-details">
                        <div class="row">
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
                                    <x-select-model :value="old('manufacturer_id')"/>
                                    <p class="error-message">
                                        {{ $errors->first('model_id') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group @error('year') has-error @enderror">
                                    <label>Year</label>
                                    <x-select-year />
                                    <p class="error-message">
                                        {{ $errors->first('year') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Car Type</label>
                            <x-radio-list-car-type />
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" placeholder="Price" name="price"/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Vin Code</label>
                                    <input placeholder="Vin Code" name="vin"/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Mileage (ml)</label>
                                    <input placeholder="Mileage" name="mileage"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Fuel Type</label>
                            <x-radio-list-fuel-type />
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Province</label>
                                    <x-select-province />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>City</label>
                                    <x-select-city />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input placeholder="Address" name="address" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input placeholder="Phone" name="phone"/>
                                </div>
                            </div>
                        </div>
                            <x-checkbox-car-features />
                        <div class="form-group">
                            <label>Detailed Description</label>
                            <textarea rows="10" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="date" name="published_at" />
                                Published
                            </label>
                        </div>
                    </div>
                    <div class="form-images">
                        <div class="form-image-upload">
                            <div class="upload-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width: 48px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <input id="carFormImageUpload" type="file" name="images[]" multiple />
                        </div>
                        <div id="imagePreviews" class="car-form-images"></div>
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
