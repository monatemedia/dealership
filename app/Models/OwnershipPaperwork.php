<?php // app/Models/OwnershipPaperwork.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnershipPaperwork extends Model
{
    protected $table = 'ownership_paperwork';

    protected $fillable = ['name', 'category'];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'ownership_paperwork_vehicle');
    }
}
