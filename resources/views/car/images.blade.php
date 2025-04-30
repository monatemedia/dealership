<x-app-layout title="My Cars" bodyClass="page-my-cars">
    <main>
        <div>
            <div class="container">
                <h1 class="car-details-page-title">
                    Manage Images for {{ $car->getTitle() }}
                </h1>
                <div class="card p-medium">
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
                                            <a href="{{ route('car.create') }}">
                                                Add images
                                            </a>
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
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
