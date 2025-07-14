<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'province_id'];

    // Relationships
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function cars(): HasMany
    {
        // This car type has many cars
        return $this->hasMany(Car::class);
    }
}
