<?php

namespace App\Providers;

use App\Services\EarningsService;
use App\Services\Payment\AsaasGateway;
use App\Services\Payment\PaymentGatewayManager;
use App\Services\Payment\SandboxGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayManager::class, function ($app) {
            $drivers = [
                'sandbox' => new SandboxGateway(),
                'asaas' => $app->make(AsaasGateway::class),
            ];

            // Real integrations should be registered here when implemented.
            // $drivers['stripe'] = $app->make(StripeGateway::class);
            // $drivers['pix'] = ...;

            return new PaymentGatewayManager($drivers);
        });

        // Allow direct resolution of AsaasGateway via DI container
        $this->app->bind(AsaasGateway::class, function ($app) {
            return new AsaasGateway();
        });
    }

    public function boot(): void
    {
        // No boot logic required for the MVP.
    }
}