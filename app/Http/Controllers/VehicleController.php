<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Models\Condition;
use App\Models\Feature;
use App\Models\Section;
use App\Models\OwnershipPaperwork;
use App\Models\ServiceHistory;
use App\Models\Category;
use App\Models\Vehicle;
use App\Services\VehicleImage\VehicleImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
     * - If category provided: show create form
     * - If section provided: redirect to category selection
     * - If nothing provided: redirect to section selection
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

        $categorySlug = $request->query('category');
        $sectionSlug = $request->query('section');

        // --- CASE 1: category present ---
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();

            if (!$category) {
                return redirect()->route('sections.index')
                    ->with('error', "Invalid category '{$categorySlug}'. Please select a valid one.");
            }

            $category->load('section');
            $vehicleTypes = $category->vehicleTypes()->get();

            // Get all configs
            $fuelConfig = $category->getFuelTypeConfig();
            $transmissionConfig = $category->getTransmissionConfig(); // <-- ADDED
            $drivetrainConfig = $category->getDrivetrainConfig();   // <-- ADDED
            $colorConfig = $category->getColorConfig();              // NEW
            $interiorConfig = $category->getInteriorConfig();        // NEW
            $accidentHistoryConfig = $category->getAccidentHistoryConfig(); // NEW
            $serviceHistories = ServiceHistory::orderBy('order')->get();
            $conditions = Condition::orderBy('order')->get();

            return view('vehicle.create', [
                'category' => $category,
                'section' => $category->section,
                'vehicleTypes' => $vehicleTypes,

                // Fuel Types
                'fuelTypes' => $fuelConfig['fuel_types'],
                'defaultFuelType' => $fuelConfig['default'],
                'canEditFuelType' => $fuelConfig['can_edit'],

                // Transmissions
                'transmissions' => $transmissionConfig['transmissions'],        // <-- ADDED
                'defaultTransmission' => $transmissionConfig['default'],       // <-- ADDED
                'canEditTransmission' => $transmissionConfig['can_edit'],     // <-- ADDED

                // Drivetrains
                'drivetrains' => $drivetrainConfig['drivetrains'],          // <-- ADDED
                'defaultDrivetrain' => $drivetrainConfig['default'],         // <-- ADDED
                'canEditDrivetrain' => $drivetrainConfig['can_edit'],       // <-- ADDED

                // Colors - NEW
                'colors' => $colorConfig['colors'],
                'defaultColor' => $colorConfig['default'],
                'canEditColor' => $colorConfig['can_edit'],

                // Interiors - NEW
                'interiors' => $interiorConfig['interiors'],
                'defaultInterior' => $interiorConfig['default'],
                'canEditInterior' => $interiorConfig['can_edit'],

                // Accident History - NEW
                'accidentHistories' => $accidentHistoryConfig['accident_histories'],
                'defaultAccidentHistory' => $accidentHistoryConfig['default'],
                'canEditAccidentHistory' => $accidentHistoryConfig['can_edit'],

                // Service Histories - NEW
                'serviceHistories' => $serviceHistories,

                // Conditions - NEW
                'conditions' => $conditions,
            ]);
        }

        // --- CASE 2: section present, but no category ---
        if ($sectionSlug) {
            $section = Section::where('slug', $sectionSlug)->first();

            if (!$section) {
                return redirect()->route('sections.index')
                    ->with('error', "Invalid Section '{$sectionSlug}'.");
            }

            // Mark session: selecting category for vehicle creation
            session([
                'selecting_category_for_create' => true,
                'from_vehicle_create' => true, // <-- one-time flag
            ]);

            return redirect()->route('section.categories.index', [
                'section' => $section->slug,
            ])
            ->with('info', 'Please select a category');
        }

        // --- CASE 3: neither present ---
        session([
            'selecting_category_for_create' => true,
            'from_vehicle_create' => true, // <-- one-time flag
        ]);

        return redirect()->route('sections.index')
            ->with('info', 'Please select a section');
    }

    /**
     * app/Http/Controllers/VehicleController::store
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $user = $request->user();

        // 1. Check user profile requirements
        if (!$user->phone) {
            session(['url.intended' => route('vehicle.create')]);
            return redirect()->route('profile.index')
                ->with('warning', 'Please provide a phone number before adding a vehicle');
        }

        // 2. Authorize
        Gate::authorize('create', Vehicle::class);

        // 3. Get validated data FIRST
        $data = $request->validated();

        dd($data);

        // 4. Resolve Manufacturer and Model (using your new helper methods)
        // We pass the raw input from the request to resolve either the ID or create a new entry
        $data['manufacturer_id'] = $this->resolveManufacturerId($request->input('manufacturer_id'));
        $data['model_id'] = $this->resolveModelId($data['manufacturer_id'], $request->input('model_id'));

        // 5. Category and Section Logic
        $category = Category::findOrFail($data['category_id']);
        $data['section_id'] = $category->section_id;
        $data['user_id'] = Auth::id();

        // 6. Handle published_at timezone conversion
        if (!empty($data['published_at'])) {
            $data['published_at'] = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $data['published_at'],
                'Africa/Johannesburg'
            )->setTimezone('UTC');
        } else {
            $data['published_at'] = now();
        }

        // 7. Create the Vehicle record (ONLY ONCE)
        $vehicle = Vehicle::create($data);

        // 8. Handle Relationships (Features and Paperwork)
        $selectedFeatures = $data['features'] ?? [];
        $selectedPaperwork = $data['ownership_paperwork'] ?? [];

        $featureIds = Feature::whereIn('name', $selectedFeatures)->pluck('id');
        $vehicle->features()->sync($featureIds);

        $paperworkIds = OwnershipPaperwork::whereIn('name', $selectedPaperwork)->pluck('id');
        $vehicle->ownershipPaperwork()->sync($paperworkIds);

        // 9. Handle Image Uploads
        $images = $request->file('images') ?: [];
        if (count($images) > 12) {
            $images = array_slice($images, 0, 12);
        }

        foreach ($images as $i => $image) {
            $position = $i + 1;
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            $storedPath = Storage::disk('private')->putFileAs('processing_queue', $image, $filename);
            $fullTempPath = str_replace('\\', '/', Storage::disk('private')->path($storedPath));

            $vehicleImage = $vehicle->images()->create([
                'original_filename' => $image->getClientOriginalName(),
                'temp_file_path' => $fullTempPath,
                'image_path' => '',
                'position' => $position,
                'status' => 'pending',
            ]);

            ProcessVehicleImage::dispatch($vehicleImage->id);
        }

        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle was created');
    }

    /**
     * VehicleController::show
     * Display the specified resource.
     */
    public function show(Request $request, Vehicle $vehicle)
    {
        // If vehicle 'published_at' does not exist
        if (!$vehicle->published_at) {
            // Use not found method
            abort(404);
        }

        // Eager load all relationships
        $vehicle->load([
            'manufacturer',
            'model',
            'city',
            'vehicleType',
            'fuelType',
            'transmission',
            'drivetrain',
            'color',              // NEW
            'interior',           // NEW
            'accidentHistory',    // NEW
            'owner',
            'primaryImage',
            'images',
            'features.featureGroups',
            'features',
            'favouredUsers'
        ]);

        // Get feature groups for this category with their features
        $featureGroups = \App\Models\FeatureGroup::with(['features'])
            ->whereHas('categories', function($query) use ($vehicle) {
                $query->where('categories.id', $vehicle->category_id);
            })
            ->get();

        // Get all ownership paperwork
        $ownershipPaperwork = OwnershipPaperwork::all();

        return view('vehicle.show', [
            'vehicle' => $vehicle,
            'featureGroups' => $featureGroups,
            'ownershipPaperwork' => $ownershipPaperwork
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);

        // Eager load relationships
        $vehicle->load([
            'vehicleType.category.section',
            'category.section',
            'manufacturer',
            'model',
            'fuelType',
            'transmission',
            'drivetrain',
            'color',
            'interior',
            'accidentHistory',
            'serviceHistory',
            'exteriorCondition',
            'interiorCondition',
            'mechanicalCondition',
            'city.province',
            'features',
            'ownershipPaperwork',
            'images',
        ]);

        // Prefer vehicleType's category if it exists; fallback to vehicle->category
        $category = $vehicle->vehicleType?->category ?? $vehicle->category;

        if (!$category) {
            abort(404, 'No category found for this vehicle.');
        }

        $section = $category->section;
        if (!$section) {
            abort(404, 'No section found for this category.');
        }

        // Get all the same config data as create
        $fuelConfig = $category->getFuelTypeConfig();
        $transmissionConfig = $category->getTransmissionConfig();
        $drivetrainConfig = $category->getDrivetrainConfig();
        $colorConfig = $category->getColorConfig();
        $interiorConfig = $category->getInteriorConfig();
        $accidentHistoryConfig = $category->getAccidentHistoryConfig();
        $serviceHistories = ServiceHistory::orderBy('order')->get();
        $conditions = Condition::orderBy('order')->get();

        return view('vehicle.edit', [
            'vehicle' => $vehicle,
            'category' => $category,
            'section' => $section,

            // Fuel Types
            'fuelTypes' => $fuelConfig['fuel_types'],
            'defaultFuelType' => $fuelConfig['default'],
            'canEditFuelType' => $fuelConfig['can_edit'],

            // Transmissions
            'transmissions' => $transmissionConfig['transmissions'],
            'defaultTransmission' => $transmissionConfig['default'],
            'canEditTransmission' => $transmissionConfig['can_edit'],

            // Drivetrains
            'drivetrains' => $drivetrainConfig['drivetrains'],
            'defaultDrivetrain' => $drivetrainConfig['default'],
            'canEditDrivetrain' => $drivetrainConfig['can_edit'],

            // Colors
            'colors' => $colorConfig['colors'],
            'defaultColor' => $colorConfig['default'],
            'canEditColor' => $colorConfig['can_edit'],

            // Interiors
            'interiors' => $interiorConfig['interiors'],
            'defaultInterior' => $interiorConfig['default'],
            'canEditInterior' => $interiorConfig['can_edit'],

            // Accident History
            'accidentHistories' => $accidentHistoryConfig['accident_histories'],
            'defaultAccidentHistory' => $accidentHistoryConfig['default'],
            'canEditAccidentHistory' => $accidentHistoryConfig['can_edit'],

            // Service Histories
            'serviceHistories' => $serviceHistories,

            // Conditions
            'conditions' => $conditions,
        ]);
    }

/**
 * Update the specified resource in storage.
 */
public function update(StoreVehicleRequest $request, Vehicle $vehicle)
{
    Gate::authorize('update', $vehicle);

    $data = $request->validated();

    // 1. Resolve Manufacturer and Model IDs
    // This handles both existing IDs and new free-text input
    $data['manufacturer_id'] = $this->resolveManufacturerId($request->input('manufacturer_id'));
    $data['model_id'] = $this->resolveModelId($data['manufacturer_id'], $request->input('model_id'));

    // 2. Handle published_at timezone conversion
    if (!empty($data['published_at'])) {
        $data['published_at'] = \Carbon\Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $data['published_at'],
            'Africa/Johannesburg'
        )->setTimezone('UTC');
    }

    // 3. Update vehicle details
    $vehicle->update($data);

    // 4. Update Pivot Tables (Features & Paperwork)
    $selectedFeatures = $data['features'] ?? [];
    $featureIds = Feature::whereIn('name', $selectedFeatures)->pluck('id');
    $vehicle->features()->sync($featureIds);

    $selectedPaperwork = $data['ownership_paperwork'] ?? [];
    $paperworkIds = OwnershipPaperwork::whereIn('name', $selectedPaperwork)->pluck('id');
    $vehicle->ownershipPaperwork()->sync($paperworkIds);

    // 5. Redirect Logic
    if ($request->has('redirect_to_images')) {
        return redirect()->route('vehicle.images', $vehicle)
            ->with('success', 'Vehicle updated. Now manage your images.');
    }

    return redirect()->route('vehicle.index')
        ->with('success', 'Vehicle updated successfully.');
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
     * app/Http/Controllers/VehicleController::search
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

private function resolveManufacturerId($input): int
{
    // If the user selected an existing item from a dropdown, it's an ID
    if (is_numeric($input) && Manufacturer::where('id', $input)->exists()) {
        return (int) $input;
    }

    // If it's a string (new manufacturer), normalize it
    $name = trim($input);
    $normalized = strtolower($name);

    // Check Aliases first to avoid duplicates
    $alias = \App\Models\ManufacturerAlias::where('alias', $normalized)->first();
    if ($alias) return $alias->manufacturer_id;

    // Check if the name exists but was sent as a string instead of an ID
    $existing = Manufacturer::whereRaw('LOWER(name) = ?', [$normalized])->first();
    if ($existing) return $existing->id;

    // Create new
    $new = Manufacturer::create([
        'name' => $name,
        'source' => \App\Enums\DataSource::USER->value
    ]);

    $new->aliases()->create(['alias' => $normalized]);
    return $new->id;
}

    private function resolveModelId(int $manufacturerId, $input): int
    {
        if (is_numeric($input) && \App\Models\Model::where('id', $input)->exists()) {
            return (int) $input;
        }

        $normalized = strtolower(trim($input));

        // Check Aliases specifically for this manufacturer
        $alias = \App\Models\ModelAlias::where('alias', $normalized)
            ->whereHas('model', fn($q) => $q->where('manufacturer_id', $manufacturerId))
            ->first();
        if ($alias) return $alias->model_id;

        $model = \App\Models\Model::firstOrCreate(
            ['name' => $input, 'manufacturer_id' => $manufacturerId],
            ['source' => \App\Enums\DataSource::USER->value]
        );

        $model->aliases()->firstOrCreate(['alias' => $normalized]);

        return $model->id;
    }
}
