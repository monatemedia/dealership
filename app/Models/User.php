<?php // app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'google_id',
        'facebook_id',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Define method for users to add to vehicles to their watchlist with return type
    public function favouriteVehicles(): BelongsToMany
    {
        // This user has many vehicles in his watchlist
        return $this->belongsToMany(Vehicle::class, 'favourite_vehicles')
            // The pivot table is favourite_vehicles
            ->withPivot('id')
            ->orderBy('favourite_vehicles.id', 'desc');
    }

    // Define method for users to add vehicles they own return type
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function isOauthUser(): bool
    {
        return !$this->password;
    }
}
