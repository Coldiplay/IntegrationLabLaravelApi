<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\CargoType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CargoTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CargoType $cargoType): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isAdmin($user)
            ||  Role::isLogistician($user)
            ? Response::allow()
            : Response::deny('You do not have permission to create cargo type.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CargoType $cargoType): Response
    {
        return Role::isAdmin($user)
        ||  Role::isLogistician($user)
            ? Response::allow()
            : Response::deny('You do not have permission to update cargo type.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CargoType $cargoType): Response
    {
        return Role::isAdmin($user)
        ||  Role::isLogistician($user)
            ? Response::allow()
            : Response::deny('You do not have permission to delete cargo type.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CargoType $cargoType): Response
    {
        return Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to restore cargo type.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CargoType $cargoType): bool
    {
        return false;
    }
}
