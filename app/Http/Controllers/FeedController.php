<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Post;
use App\Services\MediaService;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function __construct(protected MediaService $media)
    {
    }

    public function __invoke(): View
    {
        $user = auth()->user();

        $followedIds = $user->following()->pluck('users.id');

        $blockedIds = Block::where('blocker_id', $user->id)
            ->orWhere('blocked_id', $user->id)
            ->pluck('blocked_id')
            ->merge(Block::where('blocker_id', $user->id)->pluck('blocked_id'));

        $posts = Post::query()
            ->where('status', 'published')
            ->whereIn('user_id', $followedIds)
            ->with(['user.profile', 'media'])
            ->when($blockedIds->isNotEmpty(), fn ($q) => $q->whereNotIn('user_id', $blockedIds))
            ->latest()
            ->paginate(10);

        $access = [];

        foreach ($posts->getCollection() as $post) {
            $canView = $post->visibility === 'public' || $user->activeSubscriptionFor($post->user_id) !== null;

            $urls = [];

            if ($canView) {
                foreach ($post->media as $media) {
                    $urls[$media->id] = $this->media->temporaryUrl($media->file_path);
                }
            }

            $access[$post->id] = ['can_view' => $canView, 'media_urls' => $urls];
        }

        return view('feed.index', compact('posts', 'access'));
    }
}