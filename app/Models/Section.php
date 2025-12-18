<?php
// app/Models/Section.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'sections';

    protected $fillable = [
        'name',
        'singular',
        'long_name',
        'description',
        'image_path',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
