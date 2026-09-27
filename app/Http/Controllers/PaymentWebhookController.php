<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
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
        try {
            $event = $this->gateways->driver()->handleWebhook($request);
            $this->dispatch($event);
        } catch (Exception $e) {
            Log::error('Webhook dispatch failed', [
                'error' => $e->getMessage(),
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

        if (in_array($type, ['subscription.created', 'subscription.paid', 'payment.refunded', 'subscription.cancelled'], true)) {
            $subscription = Subscription::find((int) ($data['subscription_id'] ?? 0));

            if (! $subscription) {
                Log::warning('Webhook for unknown subscription', ['event' => $type]);

                return;
            }

            match ($type) {
                'subscription.paid' => $this->subscriptions->confirmPaid($subscription, $data),
                'subscription.cancelled' => $this->subscriptions->handleCancelled($subscription),
                'payment.refunded' => $this->subscriptions->handleRefund($subscription, $data),
                default => null,
            };

            return;
        }

        // Informational events (payment.created, overdue...) never mutate state.
        Log::info('Webhook event not mapped', ['event' => $type]);
    }
}