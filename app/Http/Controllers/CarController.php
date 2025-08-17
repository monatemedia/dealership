<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessCarImage;

// Add Implement HasMiddleware to the CarController
class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Find cars for authenticated user
        $cars = $request->user()
            ->cars()
            ->with([
                'primaryImage',
                'manufacturer',
                'model'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view(
            'car.index', // Return the view
            [
                'cars' => $cars // Pass the cars to the view
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
        Gate::authorize('create', Car::class);
        return view('car.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        // Get the user from the request object
        $user = $request->user();

        /**
         * Ensure user profile is complete (has phone number)
         * If not, store the intended route and redirect them to profile settings
         * so they can update their phone number before listing a car.
         */
        if (!$user->phone) {
            // Store intended route
            session(['url.intended' => route('car.create')]);
            // Redirect to profile.index with a warning message
            // to provide a phone number before adding a car
            return redirect()->route('profile.index')
                ->with('warning', 'Please provide a phone number before adding a car');
        }

        // Authorize user to create a car (policy check)
        Gate::authorize('create', Car::class);

        // Get validated request data
        $data = $request->validated();
        $featuresData = $data['features']; // Extract features data
        $images = $request->file('images') ?: []; // Extract uploaded images

        // Limit images to a maximum of 12
        if (count($images) > 12) {
            $images = array_slice($images, 0, 12);
        }

        // Assign the authenticated user ID to the car record
        $data['user_id'] = Auth::id();

        // Create the Car record
        $car = Car::create($data);

        // Create associated features record
        $car->features()->create($featuresData);

        /**
         * Handle image uploads:
         * - Save each image to the private processing queue directory
         * - Create a CarImage record with the full temporary path
         * - Dispatch the processing job for each image
         */
        foreach ($images as $i => $image) {
            $position = $i + 1;

            // Generate a unique filename while preserving the extension
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the file in the private/processing_queue directory
            // 'private' here corresponds to storage/app/private
            $storedPath = Storage::disk('private')
                ->putFileAs('processing_queue', $image, $filename);

            // Get the absolute full path to where the file was stored
            $fullTempPath = Storage::disk('private')->path($storedPath);

            // Normalize slashes for cross-OS compatibility
            $fullTempPath = str_replace('\\', '/', $fullTempPath);

            // Create a CarImage record with all required fields
            $carImage = $car->images()->create([
                'original_filename' => $image->getClientOriginalName(),
                'temp_file_path' => $fullTempPath, // critical for the job to find the file
                'image_path' => '', // will be set after processing
                'position' => $position,
                'status' => 'pending',
            ]);

            // Dispatch the image processing job with the CarImage ID
            ProcessCarImage::dispatch($carImage->id);
        }

        // Redirect back to the index with a success message
        return redirect()->route('car.index')
            ->with('success', 'Car was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Car $car)
    {
        // If car 'published_at' does not exist
        if (!$car->published_at) {
            // Use not found method
            abort(404);
        }

        return view('car.show', [
            'car' => $car
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        Gate::authorize('update', $car);
        dump($car->images->toArray());
        return view('car.edit', [
            'car' => $car
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCarRequest $request, Car $car)
    {
        Gate::authorize('update', $car);
        $data = $request->validated(); // Get request data
        $features = array_merge([
            'abs' => 0,
            'air_conditioning' => 0,
            'power_windows' => 0,
            'power_door_locks' => 0,
            'cruise_control' => 0,
            'bluetooth_connectivity' => 0,
            'remote_start' => 0,
            'gps_navigation' => 0,
            'heated_seats' => 0,
            'climate_control' => 0,
            'rear_parking_sensors' => 0,
            'leather_seats' => 0,
        ], $data['features'] ?? []);
        // Update car details
        $car->update($data);
        // Update Car features
        $car->features()->update($features);
        // Flash success message
        $request->session()->flash('success', 'Car was updated');
        // Redirect user back to car listing page with success message
        return redirect()->route('car.index')
            ->with('success', 'Car was updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        Gate::authorize('delete', $car);
        $car->delete();
        return redirect()->route('car.index')
            ->with('success', 'Car was deleted');
    }

    /**
     * Search for cars
     */
    public function search(Request $request) // Import request object
    {
        // Get request data in correct format
        $manufacturer = $request->integer('manufacturer_id');
        $model = $request->integer('model_id');
        $carType = $request->integer('car_type_id');
        $fuelType = $request->integer('fuel_type_id');
        $province = $request->integer('province_id');
        $city = $request->integer('city_id');
        $yearFrom = $request->integer('year_from');
        $yearTo = $request->integer('year_to');
        $priceFrom = $request->integer('price_from');
        $priceTo = $request->integer('price_to');
        $mileage = $request->integer('mileage');
        $sort = $request->input('sort', '-published_at');

        // Get the query builder instance with conditions
        $query = Car::where('published_at', '<', now()) // Only show cars that are published
            ->with([ // Eager load the relationships
                'city',
                'carType',
                'fuelType',
                'manufacturer',
                'model',
                'primaryImage',
                'favouredUsers'
            ]);

        // Filtering is done after the initial query!
        if ($manufacturer) { // If manufacturer is recieved
            $query->where('manufacturer_id', $manufacturer); // then where manufacturer
        }
        if ($model) {
            $query->where('model_id', $model);
        }
        /**
         ** Filtering by province is tricky, because cars do not have a `province_id`
         ** Here we need to implement a join to
         **/
        if ($province) { // If province is recieved
            $query->join( // Do a join on
                'cities', // 'cities'
                'cities.id', // where 'cities.id`
                '=', // is equal to
                'cars.city_id' // 'cars.city_id'
            )
                ->where( // where
                    'cities.province_id', // 'cities.province_id'
                    $province
                ); // matches the $province from the search
        }
        if ($city) {
            $query->where('city_id', $city);
        }
        if ($carType) {
            $query->where('car_type_id', $carType);
        }
        if ($fuelType) {
            $query->where('fuel_type_id', $fuelType);
        }
        if ($yearFrom) {
            $query->where('year', '>=', $yearFrom);
        }
        if ($yearTo) {
            $query->where('year', '<=', $yearTo);
        }
        if ($priceFrom) {
            $query->where('price', '>=', $priceFrom);
        }
        if ($priceTo) {
            $query->where('price', '<=', $priceTo);
        }
        if ($mileage) {
            $query->where('mileage', '<=', $mileage);
        }

        // If sorting starts with '-'
        if (str_starts_with($sort, '-')) {
            // Take field name without the '-' and put into $sortBy
            $sortBy = substr($sort, 1);
            // On search query call OrderBy descending
            $query->orderBy($sortBy, 'desc');
        } else {
            // Else OrderBy ascending
            $query->orderBy($sort);
        }

        $cars = $query
            ->paginate(15)
            ->withQueryString();

        return view('car.search', [
            'cars' => $cars,
        ]);
    }

    /**
     * Show the images of a car.
     */
    public function carImages(Car $car)
    {

        Gate::authorize('update', $car);

        dump($car->images->toArray());
        return view('car.images', ['car' => $car]);
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
        // Ensure the authenticated user is allowed to update this car
        Gate::authorize('update', $car);

        // Get uploaded images from request (default to empty array if none)
        $images = $request->file('images') ?? [];

        // Get the current max position from existing car images (for ordering)
        $position = $car->images()->max('position') ?? 0;

        // Loop through each uploaded image and save it
        foreach ($images as $image) {
            $position++; // increment position for each new image

            // Generate a unique filename while keeping the original extension
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the file in the private/processing_queue directory
            // "private" = storage/app/private (not publicly accessible)
            $storedPath = Storage::disk('private')
                ->putFileAs('processing_queue', $image, $filename);

            // Get the absolute path to the stored file
            $fullTempPath = Storage::disk('private')->path($storedPath);

            // Normalize slashes for cross-OS compatibility (Windows/Linux)
            $fullTempPath = str_replace('\\', '/', $fullTempPath);

            // Create a CarImage record in the database so the job has context
            $carImage = $car->images()->create([
                'original_filename' => $image->getClientOriginalName(), // keep original name for reference
                'temp_file_path' => $fullTempPath,                   // temporary file path (to be processed)
                'image_path' => '',                             // final processed path will be set later
                'position' => $position,                      // image order within this car
                'status' => 'pending',                      // start as pending
            ]);

            // Dispatch the image processing job
            // The job only needs the CarImage ID, it will fetch the record itself
            ProcessCarImage::dispatch($carImage->id);
        }

        // Redirect back to car.images route
        // with success message
        return redirect()->route('car.images', $car)
            ->with('success', 'New images were added');
    }

    /**
     * Summary of status
     * @return \Illuminate\Database\Eloquent\Collection<int, array{id: mixed, primary_image_status: mixed, primary_image_url: string>|\Illuminate\Support\Collection<int, array{id: mixed, primary_image_status: mixed, primary_image_url: string}>}
     */
    public function status(): JsonResponse
    {
        try {
            $cars = auth()->user()->cars()
                ->with([
                    'primaryImage' => function ($query) {
                        $query->select('id', 'car_id', 'status', 'image_path', 'position');
                    }
                ])
                ->select('id')
                ->get();
        } catch (\Throwable $e) {
            Log::error('Error loading cars for status()', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Unable to load cars.',
            ], 500);
        }

        $result = $cars->map(function ($car) {
            $status = 'failed';
            $url = asset('img/no_image.png');

            try {
                if ($car->primaryImage) {
                    $status = $car->primaryImage->status ?? 'failed';
                    if (!empty($car->primaryImage->image_path)) {
                        $url = asset('storage/' . ltrim($car->primaryImage->image_path, '/'));
                    }
                } else {
                    Log::warning('Car missing primary image.', [
                        'car_id' => $car->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Error accessing primaryImage for car.', [
                    'car_id' => $car->id,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                ]);
            }

            return [
                'id' => $car->id,
                'primary_image_status' => $status,
                'primary_image_url' => $url,
            ];
        });

        return response()->json($result);
    }

    /**
     * Summary of showPhone
     * @param \App\Models\Car $car
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPhone(Car $car)
    {
        return response()->json(['phone' => $car->phone]);
    }
}
