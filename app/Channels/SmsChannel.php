<?php

namespace App\Channels;

use App\Contracts\Notifications\Channel;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class SmsChannel implements Channel
{
    public function send(Notification $notification): array
    {
        $config = config('notifications.channels.sms');
        $baseUrl = rtrim($config['base_url'] ?? '', '/');
        $endpoint = $config['send_endpoint'] ?? '/api/v1/sms/send';
        $timeout = (int) ($config['timeout'] ?? 5000);
        $senderId = $config['sender_id'] ?? 'MyApp';

        $payload = [
            'to' => $notification->recipient_phone,
            'text' => $notification->rendered_body ?? $notification->payload['text'] ?? '',
            'senderId' => $senderId,
            'callbackUrl' => url($config['webhook']['path'] ?? '/webhooks/sms/status'),
            'idempotencyKey' => $notification->idempotency_key,
        ];

        // Optional: add API key header
        $headers = [
            'Accept' => 'application/json',
            'X-Correlation-Id' => $notification->correlation_id ?? (string) Str::uuid(),
        ];
        if (!empty($config['api_key'])) {
            $headers['Authorization'] = 'Bearer ' . $config['api_key'];
        }

        // Optional HMAC signature of the request body
        if (!empty($config['hmac_secret'])) {
            $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $signature = base64_encode(hash_hmac('sha256', $body, $config['hmac_secret'], true));
            $headers['X-Signature'] = $signature;
        }

        $response = Http::timeout($timeout / 1000)
            ->withHeaders($headers)
            ->post($baseUrl . $endpoint, $payload);

        $status = $response->status();
        $json = $response->json();

        if ($status >= 200 && $status < 300) {
            return [
                'message_id' => $json['messageId'] ?? $json['id'] ?? null,
                'status' => $json['status'] ?? 'sent',
                'provider_code' => (string) ($json['code'] ?? ''),
                'provider_message' => (string) ($json['message'] ?? ''),
            ];
        }

        // Handle rate limit specifically
        if ($status === 429) {
            throw new RuntimeException('Rate limited by provider');
        }

        // Treat 4xx as non-retryable except 408; 5xx as retryable
        if ($status >= 500 || $status === 408) {
            throw new RuntimeException('Temporary upstream error: ' . $status);
        }

        // Permanent error
        return [
            'status' => 'failed',
            'provider_code' => (string) $status,
            'provider_message' => (string) ($json['error'] ?? $response->body()),
        ];
    }
}
