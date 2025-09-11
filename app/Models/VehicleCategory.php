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
        'slug',
        // 'vehicle_types',
        // 'fuel_types'
    ];

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
