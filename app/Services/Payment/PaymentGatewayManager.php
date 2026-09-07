<?php

namespace App\Services\Payment;

use InvalidArgumentException;

/**
 * Resolves the active payment gateway from configuration.
 */
class PaymentGatewayManager
{
    public function __construct(protected array $drivers = [])
    {
    }

    public function driver(?string $name = null): PaymentGatewayInterface
    {
        $name ??= $this->current();

        return $this->drivers[$name] ?? throw new InvalidArgumentException(
            "Nenhum driver de pagamento registrado para [{$name}]."
        );
    }

    public function current(): string
    {
        $provider = config('payment.provider');

        // Sandbox is the default separated environment.
        if (config('payment.env') === 'sandbox' && blank($provider)) {
            return 'sandbox';
        }

        if (blank($provider)) {
            throw new InvalidArgumentException(
                'PAYMENT_PROVIDER não configurado. Defina PAYMENT_ENV=sandbox para desenvolvimento.'
            );
        }

        return strtolower((string) $provider);
    }

    public function isSandbox(): bool
    {
        return $this->driver()->isSandbox();
    }
}