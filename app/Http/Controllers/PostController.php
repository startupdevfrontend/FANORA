<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatus;
use App\Models\Post;
use App\Models\User;
use App\Services\AuditService;
use App\Services\MediaService;
use App\Services\SubscriptionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptions, protected AuditService $audit)
    {
    }

    public function show(string $username, Post $post): View
    {
        abort_if($post->status !== 'published' && ! auth()->user()?->isAdmin(), 404);

        $post->load(['user.profile', 'media']);

        $canView = app('App\Policies\PostPolicy')->view(auth()->user(), $post);

        $mediaUrls = [];

        if ($canView) {
            $mediaService = app(MediaService::class);

            foreach ($post->media as $media) {
                $mediaUrls[$media->id] = $mediaService->temporaryUrl($media->file_path);
            }
        }

        return view('posts.show', compact('post', 'canView', 'mediaUrls'));
    }

    public function stream(Post $post, \App\Models\PostMedia $media): RedirectResponse
    {
        abort_if($media->post_id !== $post->id, 404);

        $canView = app('App\Policies\PostPolicy')->view(auth()->user(), $post);
        abort_unless($canView, 403, 'Você não tem permissão para acessar este conteúdo.');

        $url = app(MediaService::class)->temporaryUrl($media->file_path);

        app(AuditService::class)->log(auth()->user(), 'media.accessed', $media, null, [
            'post_id' => $post->id,
            'media_type' => $media->media_type,
        ]);

        return redirect($url);
    }
}