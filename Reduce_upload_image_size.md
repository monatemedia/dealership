# Reduce upload Image Sizes

## Instruction 
I'm using laravel 12 with blade to render front-end views. I'm using XAMPP on Windows 10.

I want us to optimise images uploaded to ensure fast load times, especially on mobile devices. 

Before you answer me, if anything is unclear or if you need more information to give an informed solution, ask me. 

- We don't need to store the originals. 
- To keep the application snappy, I want us to run image processing async in a queue.
- I want us to use Intervention Image to resize and generate variants (large, medium, thumbnail) at upload time.
- I want us to use Spatie Laravel Image Optimizer to compress the saved variants after Intervention saves them
- We already have that all the required system tools are installed and available like jpegoptim, optipng, etc.

```sh
sudo apt install jpegoptim optipng pngquant gifsicle svgo imagemagick
```

To support WebP

```sh
sudo apt install webp
```

- We're dealing with image uploads, multiple resolutions, frontend performance, and storage cleanup. Right now, we're just storing the original upload — no resizing, no optimization. 
- We need to:
    - Resize to standard dimensions (large, medium, thumb)
    - Optimize those resized images (to reduce size without quality loss)
    - Possibly convert to WebP for modern browsers (optional but highly recommended)
    - Store intelligently (naming conventions, directory separation, maybe even CDN later)
    - Convert images to WebP format instead of JPG using .encode('webp', 80)
- We should create three new image sizes
  - large = 520x600px
  - medium = 300x300px
  - thumb = 100x100px
- We should remove unnecessary metadata and bloated compression without noticeable quality loss
- When uploading car images:
    - Use Intervention to generate 3 sizes.
    - Save each version to storage/app/public/images.
    - Run Spatie Optimizer on each version.
    - Save the paths to your DB with image type metadata (large, medium, thumb).

I want us to implement Intervention ImageManager with dependency injection. If it makes sense to, I would like us to use Spatie Laravel ImageOptimizer with dependency injection as well.

## Intervention Image Version 3

### Installation Instructions

Learn how to install Intervention Image for PHP with Composer and discover what are requirements to run the library with your server environment seamlessly.

    Server Requirements
        Image Processing Extension
    Installation

Server Requirements

Before you begin with the installation make sure that your server environment supports the following requirements.

    PHP >= 8.1
    Mbstring PHP Extension
    Image Processing PHP Extension

Although it is not a requirement, it is highly recommended to have the Exif PHP extension installed as well. This is used, among other things, to correctly display the orientation of images.
Image Processing Extension

Your server environment must have at least one PHP image processing extension installed. Intervention Image currently supports the three most popular.

    GD Image
    Imagick
    libvips

GD is part of most PHP installations. However I recommend using Imagick because I think it is faster and more efficient especially for larger images. Support for libvips is available via a driver that can be installed as an add-on package.

Based on your environmen, the appropriate driver must be configured later.
Installation

Install Intervention Image with Composer by running the following command.

composer require intervention/image

This will install Intervention Image with the most recent version, your composer.json is automatically updated and you will be able use the package's classes via the autoloader. To do this you will need to require the just created vendor/autoload.php file to PSR-4 autoload all your installed composer packages.

require './vendor/autoload.php';
 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

// create new manager instance with desired driver
$manager = new ImageManager(new Driver());

After installation you are ready to configure the image manager and read or create image instances.


### Laravel Framework Integration

Intervention Image can be easily integrated into a Laravel application with the official integration package. This package provides a Laravel service provider, facade, a publishable configuration file and more.
Installation

Instead of installing the Intervention Image directly, it is only necessary to integrate the intervention/image-laravel package. The corresponding base libraries are automatically installed as well.

composer require intervention/image-laravel

Application-wide Configuration

The extension comes with a global configuration file that is recognized by Laravel. It is therefore possible to store the settings for Intervention Image once centrally and not have to define them individually each time you call the image manager.

The configuration file can be copied to the application with the following command.

php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"

This command will publish the configuration file config/image.php. Here you can set the desired driver and its configuration options for Intervention Image. By default the library is configured to use GD library for image processing.

The configuration files looks like this.

```php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports “GD Library” and “Imagick” to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    |
    | - "autoOrientation" controls whether an imported image should be
    |    automatically rotated according to any existing Exif data.
    |
    | - "decodeAnimation" decides whether a possibly animated image is
    |    decoded as such or whether the animation is discarded.
    |
    | - "blendingColor" Defines the default blending color.
    |
    | - "strip" controls if meta data like exif tags should be removed when
    |    encoding images.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
        'strip' => false,
    ]
];

```

You can read more about the different options for driver selection, setting options for auto orientation, decoding animations and blending color.
Static Facade Interface

This package also integrates access to Intervention Image's central entry point, the ImageManager::class, via a static facade. The call provides access to the centrally configured image manager via singleton pattern.

The following code example shows how to read an image from an upload request the image facade in a Laravel route and save it on disk with a random file name.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

Route::get('/', function (Request $request) {
    $upload = $request->file('image');
    $image = Image::read($upload)
        ->resize(300, 200);

    Storage::put(
        Str::random() . '.' . $upload->getClientOriginalExtension(),
        $image->encodeByExtension($upload->getClientOriginalExtension(), quality: 70)
    );
});
```

Image Response Macro

```php
    image(Image $image, null|string|Format|MediaType|FileExtension $format = null, mixed ...$options)
```

The package includes a response macro that can be used to elegantly encode an image resource and convert it to an HTTP response in a single step.

The macro automatically takes care of the HTTP headers in the response that match the image and the desired output format.

The following code example shows how to read an image from disk apply modifications and use the image response macro to encode it and send the image back to the user in one call. Only the first parameter is required.

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

Route::get('/', function () {
    $image = Image::read(Storage::get('example.jpg'))
        ->scale(300, 200);

    return response()->image($image, Format::WEBP, quality: 65);
});


## Relevent `app.css` Logic

```css
.slide-content,
.slide-image {
  flex: 1;
}

.car-images-wrapper {
  display: flex;
  gap: 1rem;
}

.form-update-images {
  flex: 1
}

.car-images-carousel {
  display: flex;
  height: 520px;
  position: relative;
}

.car-image-wrapper {
  flex: 1;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;;
}

.car-active-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain; /* Ensures full image is shown */
  display: block;
  margin: auto;
}

.car-image-thumbnails {
  width: 110px;
  padding: 5px;
  overflow-x: hidden;
  overflow-y: auto;
}

.car-image-thumbnails img {
  width: 100%;
  height: 80px;
  border-radius: 4px;
  object-fit: contain;
  cursor: pointer;
}

.car-image-thumbnails .active-thumbnail {
  outline: 2px solid rgb(54, 145, 197);
}

/* width */
.car-image-thumbnails::-webkit-scrollbar {
  width: 4px;
}

/* Track */
.car-image-thumbnails::-webkit-scrollbar-track {
  background: transparent;
}

/* Handle */
.car-image-thumbnails::-webkit-scrollbar-thumb {
  background-color: transparent;
  transition: all 0.3s;
}

.car-image-thumbnails:hover::-webkit-scrollbar-thumb {
  background: #555;
}

/* Handle on hover */
.car-image-thumbnails::-webkit-scrollbar-thumb:hover {
  background: #444;
}

.car-details {
  padding: 1.5rem;
  min-width: 300px;
}

.car-images-and-description {
  flex: 1;
}


.car-details-owner-image {
  width: 44px;
}

.form-images {
  width: 280px;
  display: flex;
  flex-direction: column;
  padding-left: 1rem;
  border-left: 1px solid rgb(224, 224, 224);
  align-items: center;
  justify-content: start;
}

.form-image-upload {
  position: relative;
  display: inline-block;
  margin-bottom: 1rem;
}

.form-image-upload .upload-placeholder {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 100px;
  height: 100px;
  border-radius: 10px;
  border: 1px dotted var(--text-muted-color);
  color: var(--text-muted-color);
}

.form-image-upload input[type="file"] {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  opacity: 0;
  cursor: pointer;
}

.car-form-images {
  display: flex;
  gap: 0.25rem;
  flex-wrap: wrap;
  justify-content: start;
}

.car-form-image-preview {
  position: relative;
}

.car-form-image-preview .delete-icon {
  position: absolute;
  right: 0.125rem;
  top: 0.125rem;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  background-color: rgba(0, 0, 0, 0.5);
  cursor: pointer;
  opacity: 0;
  transition: all 0.3s;
}

.car-form-image-preview:hover .delete-icon {
  opacity: 1;
}

.car-form-image-preview .delete-icon:hover {
  background-color: rgba(0, 0, 0, 0.7);
}

.car-form-image-preview img {
  width: 80px;
  height: 80px;
  max-width: 100%;
  border-radius: 10px;
  object-fit: cover;
  border-radius: 10px;
}

.auth-page-form,
.auth-page-image {
  flex: 1;
}

.auth-page-image {
  display: flex;
  align-items: center;
  justify-content: end;
}

  .hero-slide .slide-image {
    order: 1;
  }

  .auth-page-image {
    display: none;
  }

  .car-details {
    min-width: auto;
    width: 100%;
  }

  .car-images-and-description {
    min-width: auto;
  }

  .car-images-carousel {
    height: auto;
  }

  .car-details-content {
    flex-direction: column-reverse;
  }

  .car-image-wrapper {
    justify-content: center;
  }

  .car-image-thumbnails {
    display: none;
  }

  .add-new-car-form .form-details {
    order: 2;
  }

  .add-new-car-form .form-images {
    border-left: none;
    order: 1;
  }

  .add-new-car-form .form-content {
    flex-direction: column;
    align-items: stretch;
  }

  .car-images-wrapper {
    flex-direction: column;
    align-items: stretch;
  }

  .car-images-wrapper .form-update-images{
    order: 2;
  }

  .car-images-wrapper .form-images {
    width: 100%;
    order: 1;
  }

  .add-new-car-form .form-content .form-images {
    align-self: center;
  }
```

## Relevent `CarController.php` Logic

Here is the logic as it currently stands in the CarController

```php
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        $user = $request->user();

        // Check if user has a phone number
        if (!$user->phone) {
            // Store intended route
            session(['url.intended' => route('car.create')]);
            // Redirect to profile.index with a warning message
            // to provide a phone number before adding a car
            return redirect()->route('profile.index')
                ->with('warning', 'Please provide a phone number before adding a car');
        }

        // Authorize the user to create a car
        Gate::authorize('create', Car::class);

        // Get request data
        $data = $request->validated();

        $featuresData = $data['features']; // Get features data
        $images = $request->file('images') ?: []; // Get images data

        // Set user ID
        $data['user_id'] = Auth::id();

        // On Car call create method and provide the data
        $car = Car::create($data);

        // Create features
        $car->features()->create($featuresData);

        // Iterate through the images
        foreach ($images as $i => $image) {
            $path = $image->store('images', 'public'); // Store the image in the public disk
            $car->images()->create([ // Create a new image record
                'image_path' => $path, // Set the path to the image
                'position' => $i + 1 // Set the position to the index + 1
            ]);
        }

        // Redirect to car.index route
        return redirect()->route('car.index')
            ->with('success', 'Car was created');
    }

        /**
     * Update the images of a car.
     */
    public function updateImages(Request $request, Car $car)
    {
        Gate::authorize('update', $car);
        // Get Validated data of delete images and positions
        $data = $request->validate([
            'delete_images' => 'array',
            'delete_images.*' => 'integer',
            'positions' => 'array',
            'positions.*' => 'integer',
        ]);

        $deleteImages = $data['delete_images'] ?? [];
        $positions = $data['positions'] ?? [];

        // Select images to delete
        $imagesToDelete = $car->images()->whereIn('id', $deleteImages)->get();

        // Iterate over images to delete and delete them from file system
        foreach ($imagesToDelete as $image) {
            $path = str_replace('public/', '', $image->image_path);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Delete images from the database
        $car->images()->whereIn('id', $deleteImages)->delete();

        // Iterate over positions and update position for each image, by its ID
        foreach ($positions as $id => $position) {
            $car->images()->where('id', $id)->update(['position' => $position]);
        }

        // Redirect back to car.images route
        return redirect()->route('car.images', $car)
            ->with('success', 'Car images were updated');
    }

    /**
     * Add images to a car.
     */
    public function addImages(Request $request, Car $car)
    {
        Gate::authorize('update', $car);
        // Get images from request
        $images = $request->file('images') ?? [];
        // Select max position of car images
        $position = $car->images()->max('position') ?? 0;
        foreach ($images as $image) {
            // Save it on the file system
            $path = $image->store('images', 'public');
            // Save it in the database
            $car->images()->create([
                'image_path' => $path,
                'position' => $position + 1
            ]);
            $position++;
        }
        return redirect()->route('car.images', $car)
            ->with('success', 'New images were added');
    }
```

## Relevent `car\create.blade.php` Logic

```blade
                    <div class="form-images">
                        @foreach($errors->get('images.*') as $imageErrors)
                            @foreach($imageErrors as $err)
                                <div class="text-error mb-small">{{ $err }}</div>
                            @endforeach
                        @endforeach
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
```

## Relevent `car\edit.blade.php` Logic

```blade
                    <div class="form-images">
                        <p>
                            Manage your images
                            <a href="{{ route('car.images', $car) }}">from here</a>
                        </p>
                        <div class="car-form-images">
                            @foreach($car->images as $image)
                                <a href="#" class="car-form-image-preview">
                                    <img src="{{ $image->getUrl() }}" alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>
```

## Relevent `car\images.blade.php` Logic

```php
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
            </div>
        </div>
    </main>
</x-app-layout>
```

## Relevent `car/index.blade.php` Logic

```blade
<x-app-layout title="My Cars" bodyClass="page-my-cars">
    <main>
        <div>
            <div class="container">
                <h1 class="car-details-page-title">My Cars</h1>
                <div class="card p-medium">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Published</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cars as $car)
                                <tr>
                                    <td>
                                        <img
                                            src="{{ $car->primaryImage?->getUrl() ?: '/img/no_image.png' }}"
                                            alt=""
                                            class="my-cars-img-thumbnail"
                                        />
                                    </td>
                                    <td>{{ $car->getTitle() }}</td>
                                    <td>{{ $car->getCreateDate() }}</td>
                                    <td>{{ $car->published_at ? 'Yes' : 'No'}}</td>
                                    {{-- Show the edit, images and delete buttons --}}
                                    {{-- TODO We'll come back to this later --}}
                                    <td class="">
                                        <a href="{{ route('car.edit', $car) }}" class="btn btn-edit inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                style="width: 12px; margin-right: 5px">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>

                                            edit
                                        </a>
                                        <a href="{{ route('car.images', $car) }}" class="btn btn-edit inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                style="width: 12px; margin-right: 5px">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                            images
                                        </a>
                                        {{-- DELETE button --}}
                                        <form action="{{ route('car.destroy', $car) }}"
                                                method="POST" class="inline-flex">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                onclick="return confirm('Are you sure you want to delete this car?')"
                                                class="btn btn-delete inline-flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    style="width: 12px; margin-right: 5px"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                    />
                                                </svg>
                                                delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center p-large">
                                        You don't have any cars yet.
                                        <a href="{{ route('car.create') }}">
                                            Add a car
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    {{ $cars->onEachSide(1)->links() }}

                </div>
            </div>
        </div>
    </main>
</x-app-layout>
```

## Relevent `car\show.blade.php` Logic

```php
                    <div class="car-images-carousel">
                        <div class="car-image-wrapper">
                            <img
                            src="{{ $car->primaryImage?->getUrl() ?: '/img/no_image.png' }}"
                            alt="" class="car-active-image"
                            id="activeImage"
                        />
                        </div>
                        <div class="car-image-thumbnails">
                            @foreach($car->images as $image)
                                <img src="{{ $image->getUrl() }}" alt=""/>
                            @endforeach
                        </div>
                        <button class="carousel-button prev-button" id="prevButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 64px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button class="carousel-button next-button" id="nextButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 64px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
```

