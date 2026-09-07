<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriberNotification extends Notification
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
            'title' => 'Nova assinatura',
            'message' => '@'.$this->subscription->user->username.' assinou seu perfil.',
            'url' => route('creator.subscribers', $this->subscription->creator),
            'subscription_id' => $this->subscription->id,
        ];
    }
}