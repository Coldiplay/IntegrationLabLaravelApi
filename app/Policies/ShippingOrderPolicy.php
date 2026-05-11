<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\ShippingOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShippingOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ShippingOrder $shippingOrder): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to view any shipping orders.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to view create shipping orders.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShippingOrder $shippingOrder): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to update shipping orders.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShippingOrder $shippingOrder): Response
    {
        return Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to delete shipping orders.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShippingOrder $shippingOrder): Response
    {
        return Role::isDriver($user)
            ? Response::allow()
            : Response::deny('You are not authorized to restore shipping orders.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShippingOrder $shippingOrder): bool
    {
        return false;
    }
}
