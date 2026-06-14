<?php

namespace App\Observers;

use App\Jobs\PublishModelEvent;
use App\Models\Chat;

class ChatObserver
{
    public function created(Chat $chat): void {
        PublishModelEvent::dispatch(Chat::class, $chat->id, 'created');
    }

    public function updated(Chat $chat): void {
        PublishModelEvent::dispatch(Chat::class, $chat->id, 'updated');
    }

    public function deleted(Chat $chat): void {
        PublishModelEvent::dispatch(Chat::class, $chat->id, 'deleted');
    }
}
