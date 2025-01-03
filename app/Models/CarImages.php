<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImages extends Model
{
    /** @use HasFactory<\Database\Factories\CarImageFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'image_path',
        'position',
    ];
}
