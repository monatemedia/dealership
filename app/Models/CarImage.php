<?php

namespace App\Models;

use \Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    /** @use HasFactory<\Database\Factories\CarImageFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'image_path',
        'position',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getUrl()
    {
        // Check if the image path is a URL
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path; // Return the URL
        }
        // Otherwise, return the URL from the storage
        return Storage::url($this->image_path);
    }
}
