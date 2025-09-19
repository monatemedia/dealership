<?php

// app/Models/VehicleCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleCategory extends Model
{
    use HasFactory;

    // Disable timestamps
    public $timestamps = false; // ← disables created_at and updated_at

    protected $fillable = [
        'name',
        'singular',
        'long_name',
        'description',
        'image_path',
        'slug',
        // 'vehicle_types',
        // 'fuel_types'
    ];

    // If you want to cast vehicle_types and fuel_types as arrays
    // (uncomment if you decide to store them as JSON)
    protected $casts = [
        // 'vehicle_types' => 'array',
        // 'fuel_types' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug'; // use slug instead of id for route binding
    }

    // A category has many vehicles
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
