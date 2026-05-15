<?php

namespace App\Jobs;

use App\Contracts\Notifications\Channel as ChannelContract;
use App\Models\Notification;
use App\Models\NotificationAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries;

    public function __construct(public int $notificationId)
    {
        $this->onQueue('notifications');
        $this->tries = (int) (config('notifications.channels.sms.retry.max', 5));
    }

    public function backoff(): array
    {
        return config('notifications.channels.sms.retry.backoff', [5, 30, 120, 600, 1800]);
    }

    public function handle(ChannelContract $channel): void
    {
        $notification = Notification::findOrFail($this->notificationId);

        // Skip if already terminal
        if (in_array($notification->status, ['delivered', 'failed', 'cancelled'])) {
            return;
        }

        $notification->status = 'sending';
        $notification->attempts_count = ($notification->attempts_count ?? 0) + 1;
        $notification->save();

        $started = microtime(true);
        try {
            $result = $channel->send($notification);
            $duration = (int) ((microtime(true) - $started) * 1000);

            // Persist attempt
            NotificationAttempt::create([
                'notification_id' => $notification->id,
                'attempt_no' => $notification->attempts_count,
                'request_metadata' => config('notifications.log_payloads') ? $notification->payload : null,
                'response_status' => $result['status'] ?? 'unknown',
                'response_body' => $result,
                'duration_ms' => $duration,
            ]);

            if (($result['status'] ?? '') === 'failed') {
                $notification->status = 'failed';
                $notification->error_code = $result['provider_code'] ?? null;
                $notification->error_message = $result['provider_message'] ?? null;
                $notification->save();
                return;
            }

            $notification->external_message_id = $result['message_id'] ?? $notification->external_message_id;
            $notification->status = 'sent';
            $notification->sent_at = now();
            $notification->save();
        } catch (Throwable $e) {
            $duration = (int) ((microtime(true) - $started) * 1000);
            NotificationAttempt::create([
                'notification_id' => $notification->id,
                'attempt_no' => $notification->attempts_count,
                'request_metadata' => config('notifications.log_payloads') ? $notification->payload : null,
                'response_status' => 'exception',
                'response_body' => ['error' => $e->getMessage()],
                'duration_ms' => $duration,
            ]);
            // Re-throw to let the queue retry
            throw $e;
        }
    }
}
