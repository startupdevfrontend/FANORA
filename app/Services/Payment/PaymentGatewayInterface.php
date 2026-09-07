<?php

namespace App\Services\Payment;

/**
 * Payment gateway abstraction.
 *
 * Every provider integration must implement this contract so the platform never
 * talks directly to an external SDK from application code.
 *
 * Sandbox and production are strictly separated:
 *  - PAYMENT_ENV=sandbox     -> uses the SandboxGateway (no real charges)
 *  - PAYMENT_ENV=production  -> must be bound to a real provider integration
 *
 * The application NEVER considers a payment approved from the frontend.
 * Statuses are only updated by webhooks delivered by the gateway.
 */
interface PaymentGatewayInterface
{
    /**
     * Provider identifier, e.g. "sandbox" or a real provider name.
     */
    public function name(): string;

    /**
     * Whether this gateway runs in sandbox mode.
     */
    public function isSandbox(): bool;

    /**
     * Creates a subscription intent on the provider.
     */
    public function createSubscription(\App\Models\Subscription $subscription): PaymentGatewayResponse;

    /**
     * Cancels an active subscription at the provider.
     */
    public function cancelSubscription(\App\Models\Subscription $subscription): void;

    /**
     * Retrieves the current provider-side subscription details.
     *
     * @return array<string, mixed>
     */
    public function getSubscription(string $gatewayId): array;

    /**
     * Handles an incoming provider webhook and returns the parsed event.
     *
     * @return array<string, mixed>
     */
    public function handleWebhook(\Illuminate\Http\Request $request): array;
}