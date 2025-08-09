<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
            ->with(['primaryImage', 'manufacturer', 'model'])
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

        // set spinner flag if user uploaded at least one image
        if (count($images) > 0) {
            $car->processing_primary_image = true;
            $car->save();
        }

        // Create features
        $car->features()->create($featuresData);

        // Iterate through the images
        foreach ($images as $i => $image) {
            $position = $i + 1;

            // Save file temporarily
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $storedPath = Storage::disk('local')->putFileAs('processing_queue', $image, $filename);


            // Full path to pass to job
            $fullTempPath = Storage::disk('local')->path($storedPath);

            ProcessCarImage::dispatch($fullTempPath, $car->id, $position);
        }

        // Redirect to car.index route
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

        dump($car->images);
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
        Gate::authorize('update', $car);
        // Get images from request
        $images = $request->file('images') ?? [];
        // Select max position of car images
        $position = $car->images()->max('position') ?? 0;

        // Send each image to ProcessCarImage job
        // and increment position for each image
        // to ensure correct ordering
        foreach ($images as $image) {
            $position++;
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $storedPath = Storage::disk('local')->putFileAs('processing_queue', $image, $filename);

            $fullTempPath = Storage::disk('local')->path($storedPath);
            ProcessCarImage::dispatch($fullTempPath, $car->id, $position);
        }

        // Redirect back to car.images route
        // with success message
        return redirect()->route('car.images', $car)
            ->with('success', 'New images were added');
    }

    /**
     * Summary of status
     * @return \Illuminate\Database\Eloquent\Collection<int, array{id: mixed, primary_image_url: mixed, processing_primary_image: mixed>|\Illuminate\Support\Collection<int, array{id: mixed, primary_image_url: mixed, processing_primary_image: mixed}>}
     */
    public function status()
    {
        $cars = auth()->user()->cars()  // assuming user has a 'cars' relationship
            ->select('id', 'processing_primary_image')
            ->with('primaryImage')  // eager load relation, adjust as needed
            ->get();

        return $cars->map(function ($car) {
            return [
                'id' => $car->id,
                'processing_primary_image' => $car->processing_primary_image,
                'primary_image_url' => $car->primaryImage?->getUrl() ?: asset('img/no_image.png'),
            ];
        });
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
