<?php

namespace App\Observers;

use App\Jobs\MessageUpdated;
use App\Models\Message;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        dispatch(new MessageUpdated($message->id))
        ->onConnection('rabbitmq')
        ->onQueue('messages-updates-queue');
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        dispatch(new MessageUpdated($message->id))
            ->onConnection('rabbitmq')
            ->onQueue('messages-updates-queue');
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
