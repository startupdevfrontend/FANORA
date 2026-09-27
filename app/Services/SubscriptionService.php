<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use App\Models\User;
use App\Notifications\NewSubscriberNotification;
use App\Services\Payment\PaymentGatewayManager;
use App\Services\Payment\PaymentGatewayResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    public function __construct(
        protected PaymentGatewayManager $gatewayManager,
        protected EarningsService $earnings,
        protected AuditService $audit,
    ) {
    }

    /**
     * Starts a subscription flow for the given subscriber/creator.
     *
     * The result never implies payment is approved. Only vendor webhooks may
     * move the subscription further in the financial contract.
     */
    public function subscribe(User $subscriber, User $creator): Subscription
    {
        abort_if(! $creator->isVerifiedCreator(), 422, 'Esse creator ainda não pode monetizar.');

        $price = $creator->creatorProfile->subscription_price_cents;

        abort_if($price === null, 422, 'O creator ainda não definiu o preço da assinatura.');

        $existingActive = $subscriber
            ->subscriptions()
            ->where('creator_id', $creator->id)
            ->where('status', SubscriptionStatus::Active->value)
            ->first();

        if ($existingActive !== null && $existingActive->ends_at?->isFuture()) {
            return $existingActive;
        }

        return DB::transaction(function () use ($subscriber, $creator, $price) {
            // Guard against double-submit races: lock the row for this creator.
            $duplicate = $subscriber
                ->subscriptions()
                ->where('creator_id', $creator->id)
                ->where('status', SubscriptionStatus::Active->value)
                ->lockForUpdate()
                ->first();

            if ($duplicate !== null && $duplicate->ends_at?->isFuture()) {
                return $duplicate;
            }

            $subscription = Subscription::create([
                'user_id' => $subscriber->id,
                'creator_id' => $creator->id,
                'value_cents' => $price,
                'status' => SubscriptionStatus::Pending->value,
            ]);

            $response = $this->gatewayManager->driver()->createSubscription($subscription);

            if ($response->successful) {
                $subscription->update([
                    'gateway_transaction_id' => $response->gatewayTransactionId,
                    'checkout_url' => $response->checkoutUrl,
                ]);
            }

            $this->audit->log($subscriber, 'subscription.created', $subscription, null, [
                'value_cents' => $price,
                'gateway' => $this->gatewayManager->driver()->name(),
            ]);

            return $subscription;
        });
    }

    /**
     * Cancels an existing subscription as requested by the subscriber.
     */
    public function cancel(Subscription $subscription, ?User $actor = null): void
    {
        abort_if($subscription->status !== SubscriptionStatus::Active, 422, 'Assinatura não está ativa.');

        DB::transaction(function () use ($subscription) {
            $subscription->lockForUpdate();
            $this->gatewayManager->driver()->cancelSubscription($subscription);

            $subscription->update(['status' => SubscriptionStatus::Cancelled->value]);
            $subscription->creator->creatorProfile?->decrement('subscriber_count');
        });

        $this->audit->log($actor ?? $subscription->user, 'subscription.cancelled', $subscription);
    }

    /**
     * Marks a subscription as cancelled because the gateway revoked it
     * (e.g. expired / inactivated at the provider). Never re-calls the gateway.
     *
     * ONLY called from a gateway webhook.
     */
    public function handleCancelled(Subscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {
            $subscription->lockForUpdate();

            if ($subscription->status === SubscriptionStatus::Active->value) {
                $subscription->update(['status' => SubscriptionStatus::Cancelled->value]);
                $subscription->creator->creatorProfile?->decrement('subscriber_count');
            }
        });

        $this->audit->log($subscription->user, 'subscription.cancelled_by_gateway', $subscription);
    }

    /**
     * Marks a confirmed charge as paid — ONLY called from a gateway webhook.
     *
     * Idempotent: replayed/concurrent webhooks for the same provider payment
     * are detected and skipped so creators are never double-credited.
     */
    public function confirmPaid(Subscription $subscription, array $providerData): Subscription
    {
        return DB::transaction(function () use ($subscription, $providerData) {
            $subscription->lockForUpdate();

            $transactionId = $providerData['transaction_id'] ?? $providerData['gateway_payment_id'] ?? null;

            // Idempotency: never double-credit the same provider payment.
            if ($transactionId !== null) {
                $alreadyRecorded = PaymentTransaction::where('provider_transaction_id', $transactionId)
                    ->where('subscription_id', $subscription->id)
                    ->exists();

                if ($alreadyRecorded) {
                    return $subscription;
                }
            }

            // Already active with an open period: nothing new to settle.
            if ($subscription->status === SubscriptionStatus::Active->value) {
                return $subscription;
            }

            $split = $this->earnings->calculateSplit($subscription->value_cents);

            // Financial settlement record + traceable charge.
            PaymentTransaction::create([
                'user_id' => $subscription->user_id,
                'creator_id' => $subscription->creator_id,
                'subscription_id' => $subscription->id,
                'gross_amount_cents' => $split['gross_cents'],
                'commission_rate' => $split['commission_rate'],
                'commission_cents' => $split['commission_cents'],
                'gateway_fee_cents' => $split['gateway_fee_cents'],
                'creator_amount_cents' => $split['creator_net_cents'],
                'status' => TransactionStatus::Paid->value,
                'provider' => $this->gatewayManager->driver()->name(),
                'provider_transaction_id' => $transactionId,
                'metadata' => $providerData,
                'paid_at' => now(),
            ]);

            SubscriptionTransaction::create([
                'subscription_id' => $subscription->id,
                'amount_cents' => $subscription->value_cents,
                'status' => TransactionStatus::Paid->value,
                'gateway_transaction_id' => $transactionId,
                'payload' => $providerData,
                'paid_at' => now(),
            ]);

            $subscription->update([
                'status' => SubscriptionStatus::Active->value,
                'starts_at' => $subscription->starts_at ?? now(),
                'ends_at' => max($subscription->ends_at ?? now(), now())->addMonth(),
            ]);

            // Increment creator subscriber counter once per activation window.
            $subscription->creator->creatorProfile?->increment('subscriber_count');

            $this->audit->log($subscription->user, 'subscription.paid', $subscription, null, $providerData);

            $subscription->creator->notify(new NewSubscriberNotification($subscription));

            Log::info('Subscription confirmed paid via webhook', [
                'subscription_id' => $subscription->id,
                'transaction_id' => $transactionId,
            ]);

            return $subscription;
        });
    }

    /**
     * Handles a refund / chargeback of a previously paid charge.
     *
     * Reverts the creator credit that was booked for the original payment and
     * revokes access. Only called from a gateway webhook.
     */
    public function handleRefund(Subscription $subscription, array $providerData): void
    {
        DB::transaction(function () use ($subscription, $providerData) {
            $subscription->lockForUpdate();

            $transactionId = $providerData['transaction_id'] ?? $providerData['gateway_payment_id'] ?? null;

            $original = $transactionId !== null
                ? PaymentTransaction::where('provider_transaction_id', $transactionId)
                    ->where('subscription_id', $subscription->id)
                    ->where('status', TransactionStatus::Paid->value)
                    ->first()
                : null;

            // Reversal ledger entry (negative creator credit) to keep math correct.
            if ($original) {
                PaymentTransaction::create([
                    'user_id' => $subscription->user_id,
                    'creator_id' => $subscription->creator_id,
                    'subscription_id' => $subscription->id,
                    'gross_amount_cents' => -$original->gross_amount_cents,
                    'commission_rate' => $original->commission_rate,
                    'commission_cents' => -$original->commission_cents,
                    'gateway_fee_cents' => -$original->gateway_fee_cents,
                    'creator_amount_cents' => -$original->creator_amount_cents,
                    'status' => TransactionStatus::Refunded->value,
                    'provider' => $original->provider,
                    'provider_transaction_id' => $transactionId,
                    'metadata' => ['refund_of' => $original->id, ...$providerData],
                    'paid_at' => now(),
                ]);
            }

            // Revoke access if the subscription was active.
            if ($subscription->status === SubscriptionStatus::Active->value) {
                $subscription->update(['status' => SubscriptionStatus::Cancelled->value]);
                $subscription->creator->creatorProfile?->decrement('subscriber_count');
            }

            $this->audit->log($subscription->user, 'subscription.refunded', $subscription, null, $providerData);
        });
    }

    /**
     * Expires subscriptions whose period has ended.
     */
    public function expireDueSubscriptions(): int
    {
        $due = Subscription::where('status', SubscriptionStatus::Active->value)
            ->where('ends_at', '<=', now())
            ->pluck('id');

        $updated = 0;

        foreach ($due->chunk(100) as $ids) {
            $updated += Subscription::whereIn('id', $ids)
                ->update(['status' => SubscriptionStatus::Expired->value]);

            foreach ($ids as $id) {
                $subscription = Subscription::find($id);
                $subscription?->creator->creatorProfile?->decrement('subscriber_count');
            }
        }

        return $updated;
    }
}