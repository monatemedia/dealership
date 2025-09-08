<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class VehiclePolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has a phone number
        return !!$user->phone; // Convert phone existance into true or false usiing double negation
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehicle $vehicle): Response
    {
        return $user->id === $vehicle->user_id ? Response::allow()
            : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $vehicle): Response
    {
        return $user->id === $vehicle->user_id ? Response::allow()
            : Response::denyWithStatus(404);
    }
}
