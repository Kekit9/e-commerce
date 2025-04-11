<?php

namespace App\Policies;

use App\Models\User;

class ServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow access to both authenticated users and admins
        return true;
    }
}
