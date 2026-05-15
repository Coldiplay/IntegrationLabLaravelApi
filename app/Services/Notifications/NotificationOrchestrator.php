<?php

namespace App\Services\Notifications;

use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationOrchestrator
{
    public function __construct(
        private MessageBuilder $builder
    ) {
    }

    /**
     * Create and queue a notification for SMS channel.
     */
    public function notifySms(string $phone, string $templateKey, array $data = [], string $locale = 'ru', ?string $correlationId = null): Notification
    {
        $notification = new Notification();
        $notification->recipient_phone = $phone;
        $notification->channel = 'sms';
        $notification->template_key = $templateKey;
        $notification->template_version = null; // will be inferred from template if needed
        $notification->locale = $locale;
        $notification->payload = $data;
        $notification->status = 'queued';
        $notification->correlation_id = $correlationId ?: (string) Str::uuid();
        $notification->idempotency_key = $this->makeIdempotencyKey($templateKey, $phone, $data);

        // Pre-render body for channel
        $notification->rendered_body = app(MessageBuilder::class)->build($templateKey, $locale, $data);

        $notification->save();

        // Dispatch job to notifications queue
        dispatch((new SendNotificationJob($notification->id)))->onQueue('notifications');

        return $notification;
    }

    private function makeIdempotencyKey(string $templateKey, string $phone, array $data): string
    {
        // Stable hash based on business fact; adjust based on your domain
        return hash('sha256', $templateKey . '|' . $phone . '|' . json_encode($data));
    }
}
