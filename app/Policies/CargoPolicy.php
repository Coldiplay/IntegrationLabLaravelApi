<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Cargo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CargoPolicy
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
    public function view(User $user, Cargo $cargo): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
        || $cargo->shipping()
            ->where('designated_driver_id', $user->id)
            ->exists()
            ? Response::allow()
            : Response::deny('You do not have permission to view this cargo.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to create cargo.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cargo $cargo): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to update cargo.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cargo $cargo): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to delete cargo.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cargo $cargo): Response
    {
        return Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to restore cargo.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cargo $cargo): Response
    {
        return Response::deny('You do not have permission to force delete cargo.');
    }
}
