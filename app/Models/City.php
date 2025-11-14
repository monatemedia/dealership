<?php // app/Models/City.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory, Searchable;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'province_id',
        'latitude',
        'longitude',
    ];

    // ADD THESE LINES:
    protected $keyType = 'int';
    public $incrementing = true;

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'province_id' => $this->province_id,
        ];
    }

    public function getScoutKey()
    {
        return (string) $this->id;
    }

    // ADD THIS METHOD:
    public function getScoutKeyName()
    {
        return 'id';
    }

    // Relationships
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function vehicles(): HasMany
    {
        // This vehicle type has many vehicles
        return $this->hasMany(Vehicle::class);
    }


    /**
     * City::distanceTo
     *
     * Calculate distance to another city in kilometers using Haversine formula
     *
     * @param City $otherCity
     * @return float Distance in kilometers
     *
     * Usage:
     * $capeTown = City::where('name', 'Cape Town')->first();
     * $johannesburg = City::where('name', 'Johannesburg')->first();
     * $distance = $capeTown->distanceTo($johannesburg); // Returns distance in km
     */
    public function distanceTo(City $otherCity): float
    {
        if (!$this->latitude || !$this->longitude || !$otherCity->latitude || !$otherCity->longitude) {
            return 0;
        }

        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($otherCity->latitude);
        $lonTo = deg2rad($otherCity->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }
}
