<?php
return [
    'channels' => [
        'sms' => [
            'provider' => 'aspnet',
            'base_url' => env('SMS_BASE_URL', 'https://sms.example.com'),
            'send_endpoint' => '/api/v1/sms/send',
            'api_key' => env('SMS_API_KEY'),
            'hmac_secret' => env('SMS_HMAC_SECRET'),
            'sender_id' => env('SMS_SENDER_ID', 'MyApp'),
            'timeout' => (int) env('SMS_TIMEOUT_MS', 5000),
            'retry' => [
                'max' => (int) env('SMS_RETRY_MAX', 5),
                // In seconds; job backoff will use this schedule
                'backoff' => [5, 30, 120, 600, 1800],
            ],
            'webhook' => [
                'path' => '/webhooks/sms/status',
                'secret' => env('SMS_STATUS_WEBHOOK_SECRET'),
                'ips' => env('SMS_WEBHOOK_IPS'), // comma-separated; optional
                'signature_header' => 'X-Signature',
            ],
        ],
    ],

    // Log payloads to attempts table (be mindful of PII)
    'log_payloads' => false,
];
