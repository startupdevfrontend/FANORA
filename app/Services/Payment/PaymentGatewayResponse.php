<?php

namespace App\Services\Payment;

/**
 * Structured result returned by a gateway when a subscription is created.
 */
final class PaymentGatewayResponse
{
    public function __construct(
        public readonly bool $successful,
        public readonly ?string $gatewayTransactionId = null,
        public readonly ?string $checkoutUrl = null,
        /** @var array<string, mixed> */
        public readonly array $raw = [],
        public readonly ?string $message = null,
    ) {
    }

    public static function success(
        ?string $gatewayTransactionId = null,
        ?string $checkoutUrl = null,
        array $raw = [],
    ): self {
        return new self(true, $gatewayTransactionId, $checkoutUrl, $raw);
    }

    public static function failure(string $message, array $raw = []): self
    {
        return new self(false, null, null, $raw, $message);
    }
}