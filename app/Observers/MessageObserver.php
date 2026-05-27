<?php

namespace App\Observers;

use App\Jobs\MessageUpdated;
use App\Models\Message;
use Str;

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
        if (!$this->shouldPublish($message)) {
            return;
        }

        $correlationId = request()?->header('X-Correlation-Id') ?? (string) Str::uuid7();

        dispatch(new MessageUpdated($message->id, $correlationId))
            ->onConnection('rabbitmq')
            ->onQueue('messages-updates-queue');
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        dispatch(new MessageUpdated($message->id))
            ->onConnection('rabbitmq')
            ->onQueue('messages-delete-queue');
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

    protected function shouldPublish(Message $message): bool
    {
        // Публикуем, только если менялись что-то кроме служебного updated_at
        $dirty = array_keys($message->getChanges());
        $meaningful = array_diff($dirty, ['updated_at', 'updatedAt']);
        return !empty($meaningful);
    }
}
