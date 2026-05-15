<?php

namespace App\Contracts\Notifications;

use App\Models\Notification;

interface Channel
{
    /**
     * Sends a notification via a specific channel.
     * Should throw exceptions on non-retryable fatal errors when appropriate.
     *
     * @return array{message_id?: string, status: string, provider_code?: string, provider_message?: string}
     */
    public function send(Notification $notification): array;
}
