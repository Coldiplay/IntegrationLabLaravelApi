<?php

namespace App\Observers;

use App\Jobs\ChatMemberUpdated;
use App\Models\ChatMember;

class ChatMembersObserver
{
    /**
     * Handle the ChatMember "created" event.
     */
    public function created(ChatMember $chatMember): void
    {
        dispatch(new ChatMemberUpdated($chatMember->user_id, $chatMember->chat_id))
            ->onConnection('rabbitmq')
            ->onQueue('chatMembers-updates-queue');
    }

    /**
     * Handle the ChatMember "updated" event.
     */
    public function updated(ChatMember $chatMember): void
    {
        dispatch(new ChatMemberUpdated($chatMember->user_id, $chatMember->chat_id))
            ->onConnection('rabbitmq')
            ->onQueue('chatMembers-updates-queue');
    }

    /**
     * Handle the ChatMember "deleted" event.
     */
    public function deleted(ChatMember $chatMember): void
    {
        //
    }

    /**
     * Handle the ChatMember "restored" event.
     */
    public function restored(ChatMember $chatMember): void
    {
        //
    }

    /**
     * Handle the ChatMember "force deleted" event.
     */
    public function forceDeleted(ChatMember $chatMember): void
    {
        //
    }
}
