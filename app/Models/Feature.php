<?php // app/Models/Feature.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'feature_vehicle');
    }
}
