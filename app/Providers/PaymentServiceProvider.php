<?php

namespace App\Providers;

use App\Services\EarningsService;
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
            ];

            // Real integrations should be registered here when implemented.
            // $drivers['stripe'] = $app->make(StripeGateway::class);
            // $drivers['pix'] = ...;

            return new PaymentGatewayManager($drivers);
        });
    }

    public function boot(): void
    {
        // No boot logic required for the MVP.
    }
}