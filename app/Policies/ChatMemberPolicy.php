<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatMemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to view all chat members.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        return $chat->chatMembers()
            ->where('user_id', $user->id)
            ->exists()
            || Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to view this chat.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Chat $chat): Response
    {
        return $chat->chatMembers()
            ->where('user_id', $user->id)
            ->exists()
            ? Response::allow()
            : Response::deny('You do not have permission to invite people to this chat.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChatMember $chatMember): Response
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChatMember $chatMember): Response
    {
        return $user->id === $chatMember->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to remove users from chat.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChatMember $chatMember): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChatMember $chatMember): bool
    {
        return false;
    }
}
