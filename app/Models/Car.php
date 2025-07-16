<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory, SoftDeletes;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'manufacturer_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'car_type_id',
        'fuel_type_id',
        'user_id',
        'city_id',
        'address',
        'phone',
        'description',
        'published_at',
    ];

    # Define the relationships

    // Define method for `CarType` and the return type
    public function carType(): BelongsTo
    {
        // This car belongs to a car type
        return $this->belongsTo(CarType::class, 'car_type_id');
        // car_type_id is optional
    }

    // Define method for `FuelType` and the return type
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
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

    // Define the features function and define the return type
    public function features(): HasOne
    {
        // Return that the `Car` object has one `CarFeatures` object relationship
        return $this->hasOne(CarFeatures::class, 'car_id');
    }

    // Define the primaryImage function and define the return type
    public function primaryImage(): HasOne
    {
        return $this->hasOne(CarImage::class)->oldestOfMany('position');
        // the save as above
        // return $this->hasOne(CarImage::class)->ofMany('position', 'min');
    }

    // Define the images function and define the return type
    public function images(): HasMany
    {
        // Return that the `Car` object has many `CarImage` objects
        return $this->hasMany(CarImage::class, 'car_id')->orderBy('position');
    }

    // Define method for users to add cars to watchlist
    public function favouredUsers(): BelongsToMany
    {
        // This car belongs to many users' watchlist
        return $this->belongsToMany(User::class, 'favourite_cars', 'car_id', 'user_id');
    }

    // Create a method to get the create date
    public function getCreateDate(): string
    {
        return (new \Carbon\Carbon($this->created_at))->format('Y-m-d');
    }

    // Create a method to get the page title
    public function getTitle()
    {
        // Return the car title
        return $this->year . ' - ' . $this->manufacturer->name . ' ' . $this->model->name;
    }

    // Create a method to check if the car is in the watchlist
    public function isInWatchlist(User $user = null)
    {
        return $this->favouredUsers->contains($user);
    }
}
