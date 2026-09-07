<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreatorVerificationApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public $user)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Verificação aprovada',
            'message' => 'Parabéns! Seu perfil de creator foi verificado e já pode monetizar.',
            'url' => route('creator.dashboard'),
        ];
    }
}