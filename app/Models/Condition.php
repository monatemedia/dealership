<?php
// app/Models/Condition.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'order'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function vehiclesExterior(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'exterior_condition_id');
    }

    public function vehiclesInterior(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'interior_condition_id');
    }

    public function vehiclesMechanical(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'mechanical_condition_id');
    }
}
