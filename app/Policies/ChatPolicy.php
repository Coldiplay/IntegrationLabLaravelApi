<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    /**
     * Determine whether the user can view all models where user in.
     */
    public function viewAllUserChat(User $user): Response
    {
        return Response::allow();
        //return ($user->id === $userId || Role::isAdmin($user))
        //    ? Response::allow()
        //    : Response::deny('You do not have permission to view other people\'s chats.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        return (//!$chat->isPrivateChat ||
            $chat->chatMembers()->where('user_id', $user->id)->exists())
            ? Response::allow()
            : Response::deny('You do not have permission to view this chat.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chat $chat): Response
    {
        return ($chat->chatMembers()
            ->where('user_id', $user->id)
            ->exists())
            ? Response::allow()
            : Response::deny('You do not have permission to edit this chat.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chat): Response
    {
        //if ($chat->chatMembers()->havingRaw('count(*) = ?', 1)
        //    ->where('user_id', '=', $user->id)->exists()) {
        if ($chat->chatMembers()->where('user_id', $user->id)->exists()) {
            if ($chat->chatMembers()->count() > 1)
            {
                Response::deny('You cannot delete chat with more than one member.');
            }

            return Response::allow();
        }

        return Response::deny('You do not have permission to delete this chat.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chat $chat): Response
    {
        return Role::isAdmin($user)
            ? Response::allow()
            : Response::deny('You do not have permission to restore this chat.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chat $chat): Response
    {
        return Response::deny('You do not have permission to force delete this chat.');
    }
}
