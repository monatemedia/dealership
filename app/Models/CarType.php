<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarType extends Model
{
    /** @use HasFactory<\Database\Factories\CarTypeFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name'];

    // Define relation to cars and return type
    public function cars(): HasMany
    {
        // This car type has many cars
        return $this->hasMany(Car::class, 'car_type_id'); // car_type_id is optional
    }
}
