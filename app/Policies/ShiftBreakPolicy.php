<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\ShiftBreak;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShiftBreakPolicy
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
            : Response::deny('You are not authorized to view any shift breaks.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ShiftBreak $shiftBreak): Response
    {
        return $user->id == $shiftBreak->driver->user_id
            || Role::isLogistician($user)
            || Role::isDriver($user)
            ? Response::allow()
            : Response::deny('You are not authorized to view shift breaks of other drivers.');
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
            : Response::deny('You are not authorized to create shift breaks.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShiftBreak $shiftBreak): Response
    {
        return $user->id == $shiftBreak->driver->user_id
        || Role::isLogistician($user)
        || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to update this shift break.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShiftBreak $shiftBreak): Response
    {
        return Role::isLogistician($user)
        || Role::isDriver($user)
            ? Response::allow()
            : Response::deny('You are not authorized to delete shift breaks.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShiftBreak $shiftBreak): Response
    {
        return Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You are not authorized to restore shift breaks.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShiftBreak $shiftBreak): bool
    {
        return false;
    }
}
