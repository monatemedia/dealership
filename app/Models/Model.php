<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /** @use HasFactory<\Database\Factories\ModelFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'manufacturer_id'];
}
