<?php // app/Models/Transmission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transmission extends Model
{
    /** @use HasFactory<\Database\Factories\TransmissionFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'transmission_group_id'
    ];

    public function transmissionGroup(): BelongsTo
    {
        return $this->belongsTo(TransmissionGroup::class);
    }
}
