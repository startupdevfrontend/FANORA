<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreatorController extends Controller
{
    public function show(Request $request, string $username): View
    {
        $creator = $this->resolveCreator($username);

        $visiblePosts = Post::query()
            ->where('user_id', $creator->id)
            ->where('status', 'published')
            ->with('media')
            ->latest()
            ->paginate(9);

        $user = $request->user();
        $isFollowing = false;
        $hasActiveSubscription = false;
        $isBlocked = false;

        $mediaUrls = [];

        if ($user) {
            $isFollowing = $user->following()->where('users.id', $creator->id)->exists();
            $hasActiveSubscription = $user->activeSubscriptionFor($creator->id) !== null;
            $isBlocked = Block::where(function ($q) use ($user, $creator) {
                $q->where('blocker_id', $user->id)->where('blocked_id', $creator->id);
            })->orWhere(function ($q) use ($user, $creator) {
                $q->where('blocker_id', $creator->id)->where('blocked_id', $user->id);
            })->exists();
        }

        $mediaService = app(\App\Services\MediaService::class);

        foreach ($visiblePosts->getCollection() as $post) {
            if ($post->visibility !== 'public' && ! $hasActiveSubscription) {
                continue;
            }

            foreach ($post->media as $media) {
                $mediaUrls[$media->id] = $mediaService->temporaryUrl($media->file_path);
            }
        }

        return view('creators.show', compact('creator', 'visiblePosts', 'isFollowing', 'hasActiveSubscription', 'isBlocked', 'mediaUrls'));
    }

    public function follow(Request $request, string $username): RedirectResponse
    {
        $creator = $this->resolveCreator($username);

        abort_if($creator->id === $request->user()->id, 422, 'Você não pode seguir a si mesmo.');

        if ($request->user()->following()->where('users.id', $creator->id)->exists()) {
            $request->user()->following()->detach($creator->id);
            $creator->creatorProfile?->decrement('subscriber_count');

            return back()->with('status', 'Você deixou de seguir '.$creator->name.'.');
        }

        $request->user()->following()->attach($creator->id);
        $creator->creatorProfile?->increment('subscriber_count');

        return back()->with('status', 'Você agora segue '.$creator->name.'.');
    }

    protected function resolveCreator(string $username): User
    {
        $creator = User::query()
            ->where('username', $username)
            ->with(['profile', 'creatorProfile.categories'])
            ->firstOrFail();

        abort_if(! $creator->creatorProfile || ! $creator->isActive(), 404);

        return $creator;
    }
}