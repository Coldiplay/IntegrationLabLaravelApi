<?php

namespace App\Observers;

use App\Jobs\PublishModelEvent;
use App\Models\ChatMember;

class ChatMembersObserver
{
    public function created(ChatMember $chatMember): void
    {
        PublishModelEvent::dispatch(ChatMember::class,[
            'chat_id' => $chatMember->chat_id,
            'user_id' => $chatMember->user_id,
        ], 'created');
    }

    public function updated(ChatMember $chatMember): void
    {
        PublishModelEvent::dispatch(ChatMember::class, [
            'chat_id' => $chatMember->chat_id,
            'user_id' => $chatMember->user_id,
        ], 'updated');
    }

    public function deleted(ChatMember $chatMember): void
    {
        PublishModelEvent::dispatch(ChatMember::class, [
            'user_id' => $chatMember->user_id,
            'chat_id' => $chatMember->chat_id
        ], 'deleted');
    }
}
