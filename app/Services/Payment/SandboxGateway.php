<?php

namespace App\Services\Payment;

use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Sandbox gateway.
 *
 * This is NOT a fake "works like real money" payment simulator. It is the
 * integration point left as a stub so that the whole platform flow (checkout
 * request, subscription record, transaction traceability, webhook routing) can
 * be exercised locally without touching a real provider.
 *
 * Rules:
 *  - A subscription created here stays `pending`.
 *  - Nothing is ever marked as paid from the application side.
 *  - A webhook may only react to an event if PAYMENT_ENV=sandbox AND the
 *    request carries the configured PAYMENT_WEBHOOK_SECRET, but real money is
 *    never implied.
 *  - Sandbox events are always logged and, for development/demo only, the
 *    `sandboxActivate` helper can move a subscription to `active` while
 *    `APP_ENV=local` or in tests. It is explicitly not part of the payment
 *    contract and never enabled in production.
 */
class SandboxGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'sandbox';
    }

    public function isSandbox(): bool
    {
        return true;
    }

    public function createSubscription(Subscription $subscription): PaymentGatewayResponse
    {
        $gatewayId = 'sandbox_'.Str::uuid()->toString();

        // Traceable record of the checkout intent. Always pending.
        SubscriptionTransaction::create([
            'subscription_id' => $subscription->id,
            'amount_cents' => $subscription->value_cents,
            'status' => TransactionStatus::Pending->value,
            'gateway_transaction_id' => $gatewayId,
            'payload' => ['gateway' => $this->name(), 'created_locally' => true],
        ]);

        return PaymentGatewayResponse::success(
            gatewayTransactionId: $gatewayId,
            raw: [
                'gateway' => $this->name(),
                'message' => 'Sandbox checkout registered. Awaiting webhook confirmation.',
            ],
        );
    }

    public function cancelSubscription(Subscription $subscription): void
    {
        $subscription->update([
            'status' => SubscriptionStatus::Cancelled->value,
            'cancelled_at' => now(),
        ]);

        $subscription->transactions()->create([
            'amount_cents' => 0,
            'status' => TransactionStatus::Pending->value,
            'payload' => ['gateway' => $this->name(), 'event' => 'subscription.cancelled'],
        ]);

        Log::info('SandboxGateway.cancelSubscription', [
            'subscription_id' => $subscription->id,
        ]);
    }

    public function getSubscription(string $gatewayId): array
    {
        return [
            'id' => $gatewayId,
            'status' => 'pending',
            'gateway' => $this->name(),
        ];
    }

    public function handleWebhook(Request $request): array
    {
        $provided = (string) $request->header('X-FANORA-WEBHOOK-SECRET');
        $expected = (string) config('payment.webhook_secret');

        // Never accept an unsigned/unknown event, even in sandbox.
        if (config('payment.env') !== 'sandbox' || $provided === '' || $provided !== $expected) {
            Log::warning('SandboxGateway.webhook_rejected', [
                'headers' => $request->headers->all(),
            ]);

            abort(403, 'Assinatura de webhook inválida.');
        }

        $event = $request->input('event', 'unknown');
        $payload = $request->input('data', []);

        Log::info('SandboxGateway.webhook', [
            'event' => $event,
            'payload' => $payload,
        ]);

        return ['event' => $event, 'data' => $payload];
    }

    /**
     * Developer/test-only helper to move a sandbox subscription to active.
     *
     * Guarded to local and testing environments. Never available in production.
     * This exists solely so the exclusive-content flow can be demoed locally
     * without any real payment; real provider confirmations must arrive via
     * webhook in integrations.
     */
    public function sandboxActivate(Subscription $subscription): void
    {
        abort_if(
            app()->environment('production'),
            403,
            'Operação disponível apenas em ambiente sandbox.'
        );

        $subscription->update([
            'status' => SubscriptionStatus::Active->value,
            'starts_at' => $subscription->starts_at ?? now(),
            'ends_at' => $subscription->ends_at ?? now()->addMonth(),
            'gateway_transaction_id' => $subscription->gateway_transaction_id
                ?? 'sandbox_'.Str::uuid()->toString(),
        ]);

        $subscription->transactions()->create([
            'subscription_id' => $subscription->id,
            'amount_cents' => $subscription->value_cents,
            'status' => TransactionStatus::Paid->value,
            'gateway_transaction_id' => $subscription->gateway_transaction_id,
            'payload' => ['gateway' => $this->name(), 'demo_activation' => true],
            'paid_at' => now(),
        ]);
    }
}