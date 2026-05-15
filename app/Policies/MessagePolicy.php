<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Message $message): Response
    {
        return $message->chat->chatMembers()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You do not have permission to view this message.');
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
            : Response::deny('You are not a member of this chat.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Message $message): Response
    {
        return $user->id == $message->sender_id
            && ChatMember::where('chat_id', $message->chat_id)
            ->where('user_id', $user->id)
            ->exists()
            ? Response::allow()
            : Response::deny('You do not have permission to edit this message.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $message): Response
    {
        return $message->sender_id === $user->id
            && ChatMember::where('chat_id', $message->chat_id)
            ->where('user_id', $user->id)
            ->exists()
            ? Response::allow()
            : Response::deny('You do not own this message.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Message $message): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Message $message): bool
    {
        return false;
    }
}
