<?php // app/Models/DrivetrainGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DrivetrainGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];

    public function driveTrains(): HasMany
    {
        return $this->hasMany(Drivetrain::class);
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class, 'drivetrain_group_subcategory')
            ->withPivot('default_drivetrain', 'can_edit');
    }
}
