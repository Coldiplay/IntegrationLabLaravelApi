<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\DriversShift;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DriversShiftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return Role::isDriver($user)
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to view any shifts.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DriversShift $driversShift): Response
    {
        return $driversShift->driver_id === $user->id
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to view this driver\'s shifts.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return ($user->driver()->exists())
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to create shifts.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DriversShift $driversShift): Response
    {
        return $user->id === $driversShift->driver_id
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to update this shift.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DriversShift $driversShift): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to delete shifts.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DriversShift $driversShift): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to restore shifts.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DriversShift $driversShift): bool
    {
        return false;
    }
}
