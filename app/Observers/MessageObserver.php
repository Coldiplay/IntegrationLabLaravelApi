<?php

namespace App\Observers;

use App\Jobs\MessageUpdated;
use App\Jobs\PublishModelEvent;
use App\Models\Message;

class MessageObserver
{
    public function created(Message $message): void {
        PublishModelEvent::dispatch(Message::class, $message->id, 'created');
    }

    public function updated(Message $message): void {
        PublishModelEvent::dispatch(Message::class, $message->id, 'updated');
    }

    public function deleted(Message $message): void {
        PublishModelEvent::dispatch(Message::class, $message->id, 'deleted');
    }
}
