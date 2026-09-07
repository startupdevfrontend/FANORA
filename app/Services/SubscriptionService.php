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
            $subscription = Subscription::create([
                'user_id' => $subscriber->id,
                'creator_id' => $creator->id,
                'value_cents' => $price,
                'status' => SubscriptionStatus::Pending->value,
            ]);

            $response = $this->gatewayManager->driver()->createSubscription($subscription);

            if ($response->successful) {
                $subscription->update(['gateway_transaction_id' => $response->gatewayTransactionId]);
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
            $this->gatewayManager->driver()->cancelSubscription($subscription);
            $subscription->refresh();
        });

        $this->audit->log($actor ?? $subscription->user, 'subscription.cancelled', $subscription);
    }

    /**
     * Marks a confirmed charge as paid — ONLY called from a gateway webhook.
     */
    public function confirmPaid(Subscription $subscription, array $providerData): Subscription
    {
        return DB::transaction(function () use ($subscription, $providerData) {
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
                'provider_transaction_id' => $providerData['transaction_id'] ?? null,
                'metadata' => $providerData,
                'paid_at' => now(),
            ]);

            SubscriptionTransaction::create([
                'subscription_id' => $subscription->id,
                'amount_cents' => $subscription->value_cents,
                'status' => TransactionStatus::Paid->value,
                'gateway_transaction_id' => $providerData['transaction_id'] ?? null,
                'payload' => $providerData,
                'paid_at' => now(),
            ]);

            $subscription->update([
                'status' => SubscriptionStatus::Active->value,
                'starts_at' => $subscription->starts_at ?? now(),
                'ends_at' => now()->addMonth(),
            ]);

            // Increment creator subscriber counter.
            $subscription->creator->creatorProfile?->increment('subscriber_count');

            $this->audit->log($subscription->user, 'subscription.paid', $subscription, null, $providerData);

            $subscription->creator->notify(new NewSubscriberNotification($subscription));

            Log::info('Subscription confirmed paid via webhook', [
                'subscription_id' => $subscription->id,
                'transaction_id' => $providerData['transaction_id'] ?? null,
            ]);

            return $subscription;
        });
    }

    /**
     * Expires subscriptions whose period has ended.
     */
    public function expireDueSubscriptions(): int
    {
        return Subscription::where('status', SubscriptionStatus::Active->value)
            ->where('ends_at', '<=', now())
            ->update(['status' => SubscriptionStatus::Expired->value]);
    }
}