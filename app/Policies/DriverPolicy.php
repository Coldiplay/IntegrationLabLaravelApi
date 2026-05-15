<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DriverPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return Role::isLogistician($user)
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to view all drivers.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Driver $driver): Response
    {
        return $user->id === $driver->user_id
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to view this driver.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to create driver.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Driver $driver): Response
    {
        return $user->id === $driver->user_id
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to update this driver.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Driver $driver): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to delete this driver.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Driver $driver): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
        ? Response::allow()
        : Response::deny('You do not have permission to restore this driver.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Driver $driver): bool
    {
        return false;
    }
}
