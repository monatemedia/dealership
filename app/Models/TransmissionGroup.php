<?php // app/Models/TransmissionGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransmissionGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];

    public function transmissions(): HasMany
    {
        return $this->hasMany(Transmission::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'transmission_group_category')
            ->withPivot('default_transmission', 'can_edit');
    }
}
