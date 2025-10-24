<?php // app/Models/DriveTrain.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriveTrain extends Model
{
    /** @use HasFactory<\Database\Factories\DriveTrainFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $table = 'drive_trains'; // Explicitly set table name
    protected $fillable = [
        'name',
        'drive_train_group_id'
    ];

    public function driveTrainGroup(): BelongsTo
    {
        return $this->belongsTo(DriveTrainGroup::class);
    }
}
