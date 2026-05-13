<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SmsStatusWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $config = config('notifications.channels.sms.webhook');

        // IP allow list (optional)
        if (!empty($config['ips'])) {
            $allowedIps = array_map('trim', explode(',', $config['ips']));
            if (!in_array($request->ip(), $allowedIps, true)) {
                return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        }

        // Verify signature
        $secret = $config['secret'] ?? null;
        $sigHeader = $config['signature_header'] ?? 'X-Signature';
        if ($secret) {
            $payload = $request->getContent();
            $expected = base64_encode(hash_hmac('sha256', $payload, $secret, true));
            $provided = $request->header($sigHeader);
            if (!hash_equals($expected, (string) $provided)) {
                return response()->json(['error' => 'Invalid signature'], Response::HTTP_FORBIDDEN);
            }
        }

        $data = $request->all();
        // Expected fields from ASP.NET microservice; adjust names if different
        $messageId = $data['messageId'] ?? $data['id'] ?? null;
        $status = strtolower($data['status'] ?? ''); // e.g., delivered, failed, undeliverable
        $providerCode = $data['code'] ?? null;
        $providerMsg = $data['message'] ?? null;

        if (!$messageId) {
            return response()->json(['error' => 'Missing messageId'], 422);
        }

        $notification = Notification::query()->where('external_message_id', $messageId)->first();
        if (!$notification) {
            // Accept but log unknown messageId
            return response()->json(['ok' => true, 'note' => 'Unknown messageId'], 202);
        }

        // Map provider status to internal
        $map = [
            'delivered' => 'delivered',
            'failed' => 'failed',
            'undeliverable' => 'failed',
            'sent' => 'sent',
        ];
        $internal = $map[$status] ?? 'sent';

        $notification->status = $internal;
        if ($internal === 'delivered') {
            $notification->delivered_at = now();
        }
        $notification->error_code = $providerCode;
        $notification->error_message = $providerMsg;
        $notification->save();

        return response()->json(['ok' => true]);
    }
}
