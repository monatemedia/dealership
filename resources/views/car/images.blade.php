<x-app-layout title="My Cars" bodyClass="page-my-cars">
    <main>
        <div>
            <div class="container">
                <h1 class="car-details-page-title">
                    Manage Images for {{ $car->getTitle() }}
                </h1>
                <div class="car-images-wrapper">
                    <form
                        action="{{ route('car.updateImages', $car) }}"
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
                                    @forelse ($car->images as $image)
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
                                                class="my-cars-img-thumbnail"
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
                        action="{{ route('car.addImages', $car) }}"
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
                            <input id="carFormImageUpload" type="file" name="images[]" multiple />
                        </div>
                        <div id="imagePreviews" class="car-form-images"></div>
                        <div class="p-medium">
                            <div class="flex justify-end">
                                <button class="btn btn-primary">Add Images</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="container">
                    <h1 class="car-details-page-title">
                        Manage Images for {{ $car->getTitle() }}
                    </h1>
                    {{-- Sortable List --}}
                    <div class="sortable-list-wrapper">
                        {{-- <h1 class="car-details-page-title">
                            Manage Images for {{ $car->getTitle() }}
                        </h1> --}}

                        <label class="add-images">
                            <input type="file" id="fileInput" multiple accept=".jpg,.jpeg,.png" hidden>
                            <i class="fa-solid fa-plus"></i>
                            <strong>Add Images</strong>
                            <p style="font-size:12px;color:#64748b;">Click to select images from your device</p>
                        </label>

                        <p class="subtitle">Drag to reorder • Click trash to mark for deletion</p>

                        <div id="list"></div>

                        <div class="submit-section">
                            <div>
                                <h3>Ready to Submit</h3>
                                <p id="markedCount"></p>
                            </div>
                            <button id="submitBtn"><i class="fa-solid fa-paper-plane"></i>Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
