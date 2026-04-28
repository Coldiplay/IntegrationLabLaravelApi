<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, $userId): Response
    {
        return ($user->id === $userId)
            ? Response::allow()
            : Response::deny('You do not have permission to view other people\'s chats.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        return (!!$chat->isPrivateChat
            || ChatMember::query()
                ->where('chat_id', '=', $chat->id)
                ->where('user_id', '=', $user->id)
                ->exists())
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
        return (ChatMember::query()
            ->where('chat_id', '=', $chat->id)
            ->where('user_id', '=', $user->id)
            ->exists())
            ? Response::allow()
            : Response::deny('You do not have permission to edit this chat.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chat): Response
    {
        //TODO: Сделать администратора
        return Response::deny('You do not have permission to delete this chat.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chat $chat): Response
    {
        return Response::deny('You do not have permission to restore this chat.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chat $chat): Response
    {
        return Response::deny('You do not have permission to force delete this chat.');
    }
}
