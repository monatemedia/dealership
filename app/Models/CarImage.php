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
    public $timestamps = true;

    protected $fillable = [
        'original_filename',
        'temp_file_path',
        'image_path',
        'position',
        'status'
    ];

    // Hide fields from all array/JSON outputs
    protected $hidden = [
        'temp_file_path',
        'created_at',
        'updated_at'
    ];

    /**
     * Summary of car
     * @return BelongsTo<Car, CarImage>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Summary of getUrl
     */
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
