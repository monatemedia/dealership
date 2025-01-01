<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    /** @use HasFactory<\Database\Factories\FuelTypeFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name'];
}
