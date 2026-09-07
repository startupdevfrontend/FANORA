<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreatorVerificationRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public $user, public string $reason)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Verificação rejeitada',
            'message' => 'Sua solicitação de verificação foi rejeitada: '.$this->reason,
            'url' => route('creator.verification'),
        ];
    }
}