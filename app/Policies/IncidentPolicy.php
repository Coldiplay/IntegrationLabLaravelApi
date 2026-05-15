<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncidentPolicy
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
            : Response::deny('You are not authorized to view any incidents.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Incident $incident): Response
    {
        return $user->id === $incident->driver_id
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to view this incident.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isDriver($user)
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to create incidents.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Incident $incident): Response
    {
        return $user->id === $incident->driver_id
            || Role::isLogistician($user)
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to update this incident.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Incident $incident): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to delete this incident.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Incident $incident): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to restore incidents.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Incident $incident): bool
    {
        return false;
    }
}
