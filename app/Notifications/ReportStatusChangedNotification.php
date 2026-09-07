<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Report $report)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Denúncia atualizada',
            'message' => 'Sua denúncia ('.$this->report->reason->label().') agora está '.strtolower($this->report->status->label()).'.',
            'url' => route('notifications.index'),
        ];
    }
}