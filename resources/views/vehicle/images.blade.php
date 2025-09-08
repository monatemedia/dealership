{{-- resources/views/vehicle/images.blade.php --}}

<x-app-layout title="My Vehicles" bodyClass="page-my-vehicles">
    <main>
        <div>
            <div class="container">
                <h1 class="vehicle-details-page-title">
                    Manage Images for {{ $vehicle->getTitle() }}
                </h1>
                {{-- <div class="vehicle-images-wrapper">
                    <form
                        action="{{ route('vehicle.updateImages', $vehicle) }}"
                        method="POST"
                        class="card p-medium form-update-images"
                    >
                    @csrf
                    @method('PUT')
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Delete</th>
                                        <th>Image</th>
                                        <th>Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vehicle->images as $image)
                                    <tr>
                                        <td>
                                            <input
                                                type="checkbox"
                                                name="delete_images[]"
                                                id="delete_image_{{ $image->id }}"
                                                value="{{ $image->id }}"
                                            >
                                        </td>
                                        <td>
                                            <img
                                                src="{{ $image->getUrl() }}"
                                                alt=""
                                                class="my-vehicles-img-thumbnail"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="positions[{{ $image->id }}]"
                                                value="{{ old('positions.'.$image->id, $image->position) }}"
                                            >
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-large">
                                            There are no images.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-medium">
                            <div class="flex justify-end">
                                <button class="btn btn-primary">Update Images</button>
                            </div>
                        </div>
                    </form>
                    <form
                        action="{{ route('vehicle.addImages', $vehicle) }}"
                        enctype="multipart/form-data"
                        method="POST"
                        class="card form-images p-medium mb-large"
                    >
                    @csrf
                        <div class="form-image-upload">
                            <div class="upload-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width: 48px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <input id="vehicleFormImageUpload" type="file" name="images[]" multiple />
                        </div>
                        <div id="imagePreviews" class="vehicle-form-images"></div>
                        <div class="p-medium">
                            <div class="flex justify-end">
                                <button class="btn btn-primary">Add Images</button>
                            </div>
                        </div>
                    </form>
                </div> --}}
                {{-- Sortable List Component --}}
                <x-sortable-vehicle-images :vehicle="$vehicle"/>
            </div>
        </div>
    </main>
</x-app-layout>
