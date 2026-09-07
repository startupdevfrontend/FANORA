<?php

namespace App\Policies;

use App\Enums\PostVisibility;
use App\Models\Block;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Whether a user may view a post, respecting visibility and blocks.
     */
    public function view(?User $user, Post $post): bool
    {
        if ($post->status !== 'published') {
            return $this->update($user, $post);
        }

        // Blocked users cannot see each other's content.
        if ($user) {
            $blocked = Block::where('blocker_id', $user->id)
                ->where('blocked_id', $post->user_id)
                ->orWhere(function ($q) use ($user, $post) {
                    $q->where('blocker_id', $post->user_id)->where('blocked_id', $user->id);
                })
                ->exists();

            if ($blocked) {
                return false;
            }
        }

        if ($post->visibility === PostVisibility::Public) {
            return true;
        }

        // Exclusive content requires an active subscription.
        return $user !== null && $user->activeSubscriptionFor($post->user_id) !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null && $user->isActive() && $user->isVerifiedCreator();
    }

    public function update(?User $user, Post $post): bool
    {
        return $user !== null && $user->isActive() && $post->user_id === $user->id;
    }

    public function delete(?User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }

    public function hide(?User $user, Post $post): bool
    {
        return $user !== null && $user->isAdmin();
    }
}