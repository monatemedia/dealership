<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleTypeFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name'];

    // Define relation to vehicles and return type
    public function vehicles(): HasMany
    {
        // This vehicle type has many vehicles
        return $this->hasMany(Vehicle::class, 'vehicle_type_id'); // vehicle_type_id is optional
    }
}
