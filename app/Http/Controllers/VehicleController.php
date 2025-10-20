<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Models\Feature;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Vehicle;
use App\Services\VehicleImage\VehicleImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessVehicleImage;
use Illuminate\Validation\ValidationException;

// Add Implement HasMiddleware to the VehicleController
class VehicleController extends Controller
{
    protected VehicleImageService $vehicleImageService;

    public function __construct(VehicleImageService $vehicleImageService)
    {
        $this->vehicleImageService = $vehicleImageService;
    }

    /**
     * Display the index page
     * VehicleController::index
     */
    public function index(Request $request)
    {
        // Find vehicles for authenticated user
        $vehicles = $request->user()
            ->vehicles()
            ->with([
                'primaryImage',
                'manufacturer',
                'model'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view(
            'vehicle.index', // Return the view
            [
                'vehicles' => $vehicles // Pass the vehicles to the view
            ]
        );
    }

    /**
     * VehicleController::create
     * Show the form for creating a new resource.
     *
     * Multi-step flow:
     * - If sub_category provided: show create form
     * - If main_category provided: redirect to sub_category selection
     * - If nothing provided: redirect to main_category selection
     */
    public function create(Request $request)
    {
        $user = $request->user();

        if (!$user->phone) {
            session(['url.intended' => route('vehicle.create')]);
            return redirect()->route('profile.index')
                ->with('info', 'Please provide a phone number before adding a vehicle');
        }

        Gate::authorize('create', Vehicle::class);

        $subCategorySlug = $request->query('sub_category');
        $mainCategorySlug = $request->query('main_category');

        // --- CASE 1: sub_category present ---
        if ($subCategorySlug) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            // dd($subCategory);

            if (!$subCategory) {
                return redirect()->route('main-categories.index')
                    ->with('error', "Invalid sub-category '{$subCategorySlug}'. Please select a valid one.");
            }

            $subCategory->load('mainCategory');
            $vehicleTypes = $subCategory->vehicleTypes()->get();
            // dd($vehicleTypes);

            return view('vehicle.create', [
                'subCategory' => $subCategory,
                'mainCategory' => $subCategory->mainCategory,
                'vehicleTypes' => $vehicleTypes,
            ]);
        }

        // --- CASE 2: main_category present, but no sub_category ---
        if ($mainCategorySlug) {
            $mainCategory = MainCategory::where('slug', $mainCategorySlug)->first();

            if (!$mainCategory) {
                return redirect()->route('main-categories.index')
                    ->with('error', "Invalid main category '{$mainCategorySlug}'.");
            }

            session()->put('selecting_category_for_create', true);
            return redirect()->route('main-category.sub-categories.index', [
                'mainCategory' => $mainCategory->slug,
            ]);
        }

        // --- CASE 3: neither present ---
        session()->put('selecting_category_for_create', true);
        return redirect()->route('main-categories.index')
            ->with('info', 'Please select a vehicle category to continue');
    }


    /**
     * app/Http/Controllers/VehicleController::store
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        // Get the user from the request object
        $user = $request->user();

        /**
         * Ensure user profile is complete (has phone number)
         * If not, store the intended route and redirect them to profile settings
         * so they can update their phone number before listing a vehicle.
         */
        if (!$user->phone) {
            // Store intended route
            session(['url.intended' => route('vehicle.create')]);
            // Redirect to profile.index with a warning message
            // to provide a phone number before adding a vehicle
            return redirect()->route('profile.index')
                ->with('warning', 'Please provide a phone number before adding a vehicle');
        }

        // Authorize user to create a vehicle (policy check)
        Gate::authorize('create', Vehicle::class);

        // Get validated request data
        $data = $request->validated();
        $selectedFeatures = $data['features'] ?? []; // Extract array of feature names
        $images = $request->file('images') ?: []; // Extract uploaded images

        // Limit images to a maximum of 12
        if (count($images) > 12) {
            $images = array_slice($images, 0, 12);
        }

        // Assign the authenticated user ID to the vehicle record
        $data['user_id'] = Auth::id();

        // Create the Vehicle record
        $vehicle = Vehicle::create($data);

        // Map feature names to IDs
        $featureIds = Feature::whereIn('name', $selectedFeatures)->pluck('id');
        $vehicle->features()->sync($featureIds);

        /**
         * Handle image uploads:
         * - Save each image to the private processing queue directory
         * - Create a VehicleImage record with the full temporary path
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

            // Create a VehicleImage record with all required fields
            $vehicleImage = $vehicle->images()->create([
                'original_filename' => $image->getClientOriginalName(),
                'temp_file_path' => $fullTempPath, // critical for the job to find the file
                'image_path' => '', // will be set after processing
                'position' => $position,
                'status' => 'pending',
            ]);

            // Dispatch the image processing job with the VehicleImage ID
            ProcessVehicleImage::dispatch($vehicleImage->id);
        }

        // Redirect back to the index with a success message
        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Vehicle $vehicle)
    {
        // If vehicle 'published_at' does not exist
        if (!$vehicle->published_at) {
            // Use not found method
            abort(404);
        }

        return view('vehicle.show', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);
        // dump($vehicle->images->toArray());
        return view('vehicle.edit', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * app/Http/Controllers/VehicleController::update
     * Update the specified resource in storage.
     */
    public function update(StoreVehicleRequest $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);

        $data = $request->validated(); // Get request data
        $selectedFeatures = $data['features'] ?? []; // array of feature names

        // Update vehicle details
        $vehicle->update($data);

        // Update pivot features
        $featureIds = Feature::whereIn('name', $selectedFeatures)->pluck('id');
        $vehicle->features()->sync($featureIds);

        // Flash success message
        $request->session()->flash('success', 'Vehicle was updated');

        // Redirect user back to vehicle listing page with success message
        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle was updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        Gate::authorize('delete', $vehicle);
        $vehicle->delete();
        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle was deleted');
    }

    /**
     * Search for vehicles
     */
    public function search(Request $request) // Import request object
    {
        // Get request data in correct format
        $manufacturer = $request->integer('manufacturer_id');
        $model = $request->integer('model_id');
        $vehicleType = $request->integer('vehicle_type_id');
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
        $query = Vehicle::where('published_at', '<', now()) // Only show vehicles that are published
            ->with([ // Eager load the relationships
                'city',
                'vehicleType',
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
         ** Filtering by province is tricky, because vehicles do not have a `province_id`
         ** Here we need to implement a join to
         **/
        if ($province) { // If province is recieved
            $query->join( // Do a join on
                'cities', // 'cities'
                'cities.id', // where 'cities.id`
                '=', // is equal to
                'vehicles.city_id' // 'vehicles.city_id'
            )
                ->where( // where
                    'cities.province_id', // 'cities.province_id'
                    $province
                ); // matches the $province from the search
        }
        if ($city) {
            $query->where('city_id', $city);
        }
        if ($vehicleType) {
            $query->where('vehicle_type_id', $vehicleType);
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

        $vehicles = $query
            ->paginate(15)
            ->withQueryString();

        return view('vehicle.search', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * app/Http/Controllers/VehicleController::vehicleImages
     * Show the images of a vehicle.
     */
    public function vehicleImages(Vehicle $vehicle)
    {

        Gate::authorize('update', $vehicle);

        // dump($vehicle->images->toArray());
        // dump($vehicle->toArray());
        return view('vehicle.images', ['vehicle' => $vehicle]);
    }

    /**
     * Update the images of a vehicle.
     */
    public function updateImages(Request $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);
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
        $imagesToDelete = $vehicle->images()->whereIn('id', $deleteImages)->get();

        // Iterate over images to delete and delete them from file system
        foreach ($imagesToDelete as $image) {
            $path = str_replace('public/', '', $image->image_path);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Delete images from the database
        $vehicle->images()->whereIn('id', $deleteImages)->delete();

        // Iterate over positions and update position for each image, by its ID
        foreach ($positions as $id => $position) {
            $vehicle->images()->where('id', $id)->update(['position' => $position]);
        }

        // Redirect back to vehicle.images route
        return redirect()->route('vehicle.images', $vehicle)
            ->with('success', 'Vehicle images were updated');
    }

    /**
     * VehicleController::addImages
     * Add images to a vehicle.
     */
    public function addImages(Request $request, Vehicle $vehicle)
    {
        // Ensure the authenticated user is allowed to update this vehicle
        Gate::authorize('update', $vehicle);

        // Get uploaded images from request (default to empty array if none)
        $images = $request->file('images') ?? [];

        // Get the current max position from existing vehicle images (for ordering)
        $position = $vehicle->images()->max('position') ?? 0;

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

            // Create a VehicleImage record in the database so the job has context
            $vehicleImage = $vehicle->images()->create([
                'original_filename' => $image->getClientOriginalName(), // keep original name for reference
                'temp_file_path' => $fullTempPath,                   // temporary file path (to be processed)
                'image_path' => '',                             // final processed path will be set later
                'position' => $position,                      // image order within this vehicle
                'status' => 'pending',                      // start as pending
            ]);

            // Dispatch the image processing job
            // The job only needs the VehicleImage ID, it will fetch the record itself
            ProcessVehicleImage::dispatch($vehicleImage->id);
        }

        // Redirect back to vehicle.images route
        // with success message
        return redirect()->route('vehicle.images', $vehicle)
            ->with('success', 'New images were added');
    }

    /**
     * app/Http/Controllers/VehicleController::syncImages
     * Sync vehicle images (upload, delete, reorder).
     * Summary of syncImages
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vehicle $vehicle
     * @param \App\Services\VehicleImage\VehicleImageService $imageService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncImages(Request $request, Vehicle $vehicle, VehicleImageService $imageService)
    {
        Gate::authorize('update', $vehicle);

        try {
            // Catch validation issues manually
            try {
                $request->validate([
                    'images.*' => 'file|mimes:jpg,jpeg,png|max:2048',
                    'payload' => 'required|string',
                ]);
            } catch (ValidationException $e) {
                // Redirect to vehicle.index with flash error
                return redirect()
                    ->route('vehicle.index')
                    ->with('error', 'One or more uploaded images exceed 2MB or are invalid.');
            }

            $payload = json_decode($request->input('payload', '[]'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()
                    ->route('vehicle.index')
                    ->with('error', 'Invalid JSON payload.');
            }

            $uploadedFiles = $request->file('images', []);
            $uploadIndex = 0;

            $imagesData = [];
            foreach ($payload as $item) {
                if (!isset($item['id'], $item['action']))
                    continue;

                $record = [
                    'id' => $item['id'],
                    'action' => $item['action'],
                    'position' => $item['position'] ?? null,
                ];

                if ($item['action'] === 'upload' && isset($uploadedFiles[$uploadIndex])) {
                    $record['file'] = $uploadedFiles[$uploadIndex];
                    $uploadIndex++;
                }

                $imagesData[] = $record;
            }

            // Pass array to service
            $imageService->sync($vehicle, $imagesData);

            return redirect()
                ->route('vehicle.index')
                ->with('success', 'Images synced successfully.');

        } catch (\Throwable $e) {
            Log::error("Sync failed at controller", [
                'vehicle_id' => $vehicle->id,
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('vehicle.images', $vehicle)
                ->with('error', 'Failed to sync images.');
        }
    }

    /**
     * app/Http/Controllers/VehicleController.php
     * Summary of status
     */
    public function status(): JsonResponse
    {
        try {
            $vehicles = auth()->user()->vehicles()
                ->with([
                    'primaryImage',
                    'images' => function ($query) {
                        $query->select('id', 'vehicle_id', 'status', 'image_path', 'position');
                    }
                ])
                ->select('id')
                ->get();
        } catch (\Throwable $e) {
            Log::error('Error loading vehicles for status()', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Unable to load vehicles.'], 500);
        }

        $result = $vehicles->map(function ($vehicle) {
            $primary = $vehicle->primaryImage;
            $images = $vehicle->images ?? collect();

            return [
                'vehicle_id' => $vehicle->id,
                'primary_image' => [
                    'id' => $primary->id ?? null,
                    'status' => $primary->status ?? 'failed',
                    'url' => ($primary && $primary->status === 'completed' && $primary->image_path)
                        ? asset('storage/' . ltrim($primary->image_path, '/'))
                        : null,
                ],
                'images' => $images->map(fn($img) => [
                    'id' => $img->id,
                    'status' => $img->status,
                    'url' => ($img->status === 'completed' && $img->image_path)
                        ? asset('storage/' . ltrim($img->image_path, '/'))
                        : null,
                    'position' => $img->position,
                ])
            ];
        });

        return response()->json($result);
    }

    /**
     * Summary of showPhone
     * @param \App\Models\Vehicle $vehicle
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPhone(Vehicle $vehicle)
    {
        return response()->json(['phone' => $vehicle->phone]);
    }
}
