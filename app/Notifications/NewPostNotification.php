<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification
{
    use Queueable;

    public function __construct(public Post $post)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nova publicação',
            'message' => '@'.$this->post->user->username.' publicou novo conteúdo.',
            'url' => route('creator.show', $this->post->user->username),
            'post_id' => $this->post->id,
        ];
    }
}