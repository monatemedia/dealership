<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelType extends Model
{
    /** @use HasFactory<\Database\Factories\FuelTypeFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name'];

    public function cars(): HasMany
    {
        // This car type has many cars
        return $this->hasMany(Car::class);
    }
}
