<?php

namespace App\Services;

use App\Models\PaymentTransaction;

/**
 * Financial math for the platform.
 *
 * All rates are configurable via config/fanora.php / environment — never
 * hardcoded in business logic.
 */
class EarningsService
{
    public function commissionRate(): int
    {
        return (int) config('fanora.commission_rate', 20);
    }

    public function referenceGatewayFeeRate(): float
    {
        return (float) config('payment.reference_gateway_fee_rate', 4.99);
    }

    /**
     * Splits a gross amount into the platform financial breakdown.
     *
     * Returns cents-based fields:
     *  - gross
     *  - commission (FANORA take rate)
     *  - gateway_fee
     *  - creator_net
     */
    public function calculateSplit(int $grossCents, ?float $gatewayFeeRate = null): array
    {
        $gatewayFeeRate ??= $this->referenceGatewayFeeRate();

        $commissionCents = (int) round($grossCents * ($this->commissionRate() / 100));
        $gatewayFeeCents = (int) round($grossCents * ($gatewayFeeRate / 100));
        $creatorNetCents = $grossCents - $commissionCents - $gatewayFeeCents;

        return [
            'gross_cents' => $grossCents,
            'commission_rate' => $this->commissionRate(),
            'commission_cents' => $commissionCents,
            'gateway_fee_cents' => $gatewayFeeCents,
            'creator_net_cents' => max(0, $creatorNetCents),
        ];
    }

    public function centsToReais(int $cents): string
    {
        return number_format($cents / 100, 2, ',', '.');
    }

    public function reaisToCents(float $reais): int
    {
        return (int) round($reais * 100);
    }

    /**
     * Creates a PaymentTransaction for a paid charge with full audit trail.
     */
    public function recordPayment(
        PaymentTransaction $txn,
        array $split,
        string $status = 'paid',
    ): PaymentTransaction {
        $txn->fill([
            'gross_amount_cents' => $split['gross_cents'],
            'commission_rate' => $split['commission_rate'],
            'commission_cents' => $split['commission_cents'],
            'gateway_fee_cents' => $split['gateway_fee_cents'],
            'creator_amount_cents' => $split['creator_net_cents'],
            'status' => $status,
        ])->save();

        return $txn;
    }
}