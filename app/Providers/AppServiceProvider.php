<?php

namespace App\Providers;

use App\Models\CreatorProfile;
use App\Models\Post;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use App\Policies\CreatorProfilePolicy;
use App\Policies\PostPolicy;
use App\Policies\ReportPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Post::class => PostPolicy::class,
        Subscription::class => SubscriptionPolicy::class,
        CreatorProfile::class => CreatorProfilePolicy::class,
        Report::class => ReportPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }

        RateLimiter::for('login', fn ($job) => Limit::perMinute(5)->by($job->ip()));
        RateLimiter::for('api', fn ($job) => Limit::perMinute(60)->by($job->user()?->id ?? $job->ip()));

        VerifyEmail::toMailUsing(
            fn (object $notifiable, string $url) => (new MailMessage)
                ->subject('Confirme seu e-mail na FANORA')
                ->greeting('Bem-vindo à FANORA!')
                ->line('Clique no botão abaixo para confirmar seu endereço de e-mail.')
                ->action('Confirmar e-mail', $url)
                ->line('Se você não criou uma conta, ignore este e-mail.')
        );
    }
}