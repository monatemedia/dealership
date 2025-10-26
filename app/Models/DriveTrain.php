<?php // app/Models/Drivetrain.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drivetrain extends Model
{
    /** @use HasFactory<\Database\Factories\DrivetrainFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $table = 'drivetrains'; // Explicitly set table name
    protected $fillable = [
        'name',
        'drivetrain_group_id'
    ];

    public function drivetrainGroup(): BelongsTo
    {
        return $this->belongsTo(DrivetrainGroup::class);
    }
}
