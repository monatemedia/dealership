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

    public function toSearchableArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => (string) ($this->name ?? ''),
            'province_id' => (int) ($this->province_id ?? 0),
            'created_at' => 0, // Fixed: No timestamps on this model, so always 0
        ];
    }

    public function getScoutKey()
    {
        return (string) $this->id;
    }

    public function getScoutKeyName()
    {
        return 'id';
    }

    public function searchableAs(): string
    {
        return 'cities';
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

}
