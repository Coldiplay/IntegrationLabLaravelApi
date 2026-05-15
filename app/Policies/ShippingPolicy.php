<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShippingPolicy
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
            : Response::deny('You are not authorized to view any shippings.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shipping $shipping): Response
    {
        return Role::isLogistician($user)
        || $user->id === $shipping->designated_driver_id
        || Role::isAdmin($user)
            ? Response::allow()
            :Response::deny('You are not authorized to view this shipping.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Role::isLogistician($user)
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to create shippings.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shipping $shipping): Response
    {
        return Role::isLogistician($user)
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to update shippings.');
    }

//    public function changeStartEndState(User $user, Shipping $shipping): Response
//    {
//        return Role::isDriver($user)
//            && $shipping->designated_driver_id === $user->id
//            || Role::isLogistician($user)
//            || Role::isAdmin($user)
//            ? Response::allow()
//            : Response::deny('You are not authorized to change this shipping\' state.');
//    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shipping $shipping): Response
    {
        return Role::isLogistician($user)
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to delete shippings.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shipping $shipping): Response
    {
        return Role::isLogistician($user)
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to restore shippings.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shipping $shipping): bool
    {
        return false;
    }
}
