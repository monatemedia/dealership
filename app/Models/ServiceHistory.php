<?php
// app/Models/ServiceHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceHistory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'order'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
