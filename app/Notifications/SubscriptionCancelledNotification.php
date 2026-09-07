<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(public Subscription $subscription)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Assinatura cancelada',
            'message' => '@'.$this->subscription->user->username.' cancelou a assinatura.',
            'url' => route('creator.subscribers', $this->subscription->creator),
            'subscription_id' => $this->subscription->id,
        ];
    }
}