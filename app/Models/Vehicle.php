<?php // app/Models/Vehicle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory, SoftDeletes;

    protected $casts = [
        'published_at' => 'datetime',
        'processing_primary_image' => 'boolean',
    ];

    protected $fillable = [
        'main_category_id',
        'subcategory_id',
        'manufacturer_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'vehicle_type_id',
        'fuel_type_id',
        'transmission_id',  // ADD THIS
        'drivetrain_id',   // ADD THIS
        'color_id',
        'interior_id',
        'service_history_id',
        'accident_history_id',
        'exterior_condition_id',
        'interior_condition_id',
        'mechanical_condition_id',
        'user_id',
        'city_id',
        'address',
        'phone',
        'description',
        'published_at',
    ];

    # Define the relationships

    public function mainCategory() : BelongsTo
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    /**
     * Alias for backwards compatibility
     * Keep the old name pointing to the new relationship
     */
    public function vehicleCategory(): BelongsTo
    {
        return $this->subcategory();
    }

    // Define method for `VehicleType` and the return type
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    // Define method for `FuelType` and the return type
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    // ADD THIS
    public function transmission(): BelongsTo
    {
        return $this->belongsTo(Transmission::class);
    }

    // ADD THIS
    public function drivetrain(): BelongsTo
    {
        return $this->belongsTo(Drivetrain::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function interior(): BelongsTo
    {
        return $this->belongsTo(Interior::class);
    }

    public function accidentHistory(): BelongsTo
    {
        return $this->belongsTo(AccidentHistory::class);
    }

    public function serviceHistory(): BelongsTo
    {
        return $this->belongsTo(ServiceHistory::class);
    }
    public function exteriorCondition(): BelongsTo
    {
        return $this->belongsTo(Condition::class, 'exterior_condition_id');
    }

    public function interiorCondition(): BelongsTo
    {
        return $this->belongsTo(Condition::class, 'interior_condition_id');
    }

    public function mechanicalCondition(): BelongsTo
    {
        return $this->belongsTo(Condition::class, 'mechanical_condition_id');
    }

    // Define method for `Manufacturer` and the return type
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    // Define method for `Model` and the return type
    public function model(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Model::class);
    }

    // Define method for `Owner` and the return type
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias user() to owner() for convenience
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define method for `City` and the return type
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * app/Models/Vehicle::features
     * Summary of features
     * Define the features function and define the return type
     * @return HasOne<VehicleFeatures, Vehicle>
     */

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'feature_vehicle');
    }

    public function ownershipPaperwork()
    {
        return $this->belongsToMany(OwnershipPaperwork::class, 'ownership_paperwork_vehicle');
    }

    // Define the primaryImage function and define the return type
    public function primaryImage(): HasOne
    {
        return $this->hasOne(VehicleImage::class)
            ->orderBy('position', 'asc')
            ->limit(1);
        // return $this->hasOne(VehicleImage::class)->oldestOfMany('vehicle_images.position');
        // the save as above
        // return $this->hasOne(VehicleImage::class)->ofMany('position', 'min');
    }

    /**
     * app/Models/Vehicle::images
     * Summary of images
     * Define the images function and define the return type
     *
     * @return HasMany<VehicleImage, Vehicle>
     */
    public function images(): HasMany
    {
        // Return that the `Vehicle` object has many `VehicleImage` objects
        return $this->hasMany(VehicleImage::class, 'vehicle_id')->orderBy('position');
    }

    // Define method for users to add vehicles to watchlist
    public function favouredUsers(): BelongsToMany
    {
        // This vehicle belongs to many users' watchlist
        return $this->belongsToMany(User::class, 'favourite_vehicles', 'vehicle_id', 'user_id');
    }

    // Create a method to get the create date
    public function getCreateDate(): string
    {
        return (new \Carbon\Carbon($this->created_at))->format('Y-m-d');
    }

    // Create a method to get the page title
    public function getTitle()
    {
        // Return the vehicle title
        return $this->year . ' - ' . $this->manufacturer->name . ' ' . $this->model->name;
    }

    // Create a method to check if the vehicle is in the watchlist
    public function isInWatchlist(User $user = null)
    {
        return $this->favouredUsers->contains($user);
    }
}
