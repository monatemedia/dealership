<?php // app/Models/VehicleFeatures.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleFeatures extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFeaturesFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'vehicle_id',
        'abs',
        'air_conditioning',
        'power_windows',
        'power_door_locks',
        'cruise_control',
        'bluetooth_connectivity',
        'remote_start',
        'gps_navigation',
        'heated_seats',
        'climate_control',
        'rear_parking_sensors',
        'leather_seats',
    ];

    # Relationships
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
