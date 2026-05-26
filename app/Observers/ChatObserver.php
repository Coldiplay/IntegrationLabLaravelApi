<?php

namespace App\Observers;

use App\Jobs\ChatUpdated;
use App\Models\Chat;

class ChatObserver
{
    /**
     * Handle the Chat "created" event.
     */
    public function created(Chat $chat): void
    {
        dispatch(new ChatUpdated($chat->id))
            ->onConnection('rabbitmq')
            ->onQueue('chats-updates-queue');
    }

    /**
     * Handle the Chat "updated" event.
     */
    public function updated(Chat $chat): void
    {
        dispatch(new ChatUpdated($chat->id))
            ->onConnection('rabbitmq')
            ->onQueue('chats-updates-queue');
    }

    /**
     * Handle the Chat "deleted" event.
     */
    public function deleted(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "restored" event.
     */
    public function restored(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "force deleted" event.
     */
    public function forceDeleted(Chat $chat): void
    {
        //
    }
}
