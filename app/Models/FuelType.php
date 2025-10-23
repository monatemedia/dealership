<?php // app/Models/FuelType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelType extends Model
{
    /** @use HasFactory<\Database\Factories\FuelTypeFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'fuel_type_group_id'
    ];

    public function fuelTypeGroup(): BelongsTo
    {
        return $this->belongsTo(FuelTypeGroup::class);
    }

    public function vehicles(): HasMany
    {
        // This vehicle type has many vehicles
        return $this->hasMany(Vehicle::class);
    }
}
