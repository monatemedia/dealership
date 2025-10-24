<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleTypeFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $table = 'vehicle_types';

    protected $fillable = [
        'name',
        'long_name',
        'description',
        'image_path',
        'slug',
        'subcategory_id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
