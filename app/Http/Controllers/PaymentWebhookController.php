<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentGatewayManager;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Receives provider webhooks and forwards them to the active gateway.
 *
 * A webhook is the ONLY source of truth regarding financial confirmation —
 * never the frontend.
 */
class PaymentWebhookController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $gateways,
        protected SubscriptionService $subscriptions,
    ) {
    }

    public function handle(Request $request): JsonResponse
    {
        $event = $this->gateways->driver()->handleWebhook($request);

        try {
            $this->dispatch($event);
        } catch (Exception $e) {
            Log::error('Webhook dispatch failed', [
                'error' => $e->getMessage(),
                'event' => $event,
            ]);

            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Routes parsed gateway events to the domain layer.
     *
     * @param  array<string, mixed>  $event
     */
    protected function dispatch(array $event): void
    {
        $type = $event['event'] ?? null;
        $data = $event['data'] ?? [];

        if ($type === 'subscription.created' || $type === 'subscription.paid') {
            $subscription = \App\Models\Subscription::find((int) ($data['subscription_id'] ?? 0));

            if (! $subscription) {
                Log::warning('Webhook for unknown subscription', $data);

                return;
            }

            if ($type === 'subscription.paid') {
                $this->subscriptions->confirmPaid($subscription, $data);
            }

            return;
        }

        if ($type === 'subscription.cancelled') {
            $subscription = \App\Models\Subscription::find((int) ($data['subscription_id'] ?? 0));

            if ($subscription) {
                $this->subscriptions->cancel($subscription);
            }
        }

        Log::info('Webhook event not mapped', ['event' => $type]);
    }
}