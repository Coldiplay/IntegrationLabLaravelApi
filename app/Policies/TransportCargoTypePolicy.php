<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\TransportCargoType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransportCargoTypePolicy
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
            : Response::deny('You are not authorized to view vehicles.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransportCargoType $transportCargoType): Response
    {
        return Role::isDriver($user)
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to view vehicles.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to update vehicles.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransportCargoType $transportCargoType): Response
    {
        return Response::denyAsNotFound('Method not found');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransportCargoType $transportCargoType): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to update vehicles.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransportCargoType $transportCargoType): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to update vehicles.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransportCargoType $transportCargoType): bool
    {
        return false;
    }
}
