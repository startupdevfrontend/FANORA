<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PostVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Services\AuditService;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected MediaService $media, protected AuditService $audit)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $posts = Post::query()
            ->where('status', 'published')
            ->with(['user.profile', 'media'])
            ->where(function ($q) {
                $q->where('visibility', PostVisibility::Public->value);
            })
            ->orWhere(function ($q) use ($user) {
                if (! $user) {
                    return;
                }

                $q->where('visibility', PostVisibility::SubscribersOnly->value)
                    ->whereIn('user_id', $user->subscriptions()->where('status', 'active')->pluck('creator_id'));
            })
            ->latest()
            ->paginate(15);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->isVerifiedCreator(), 403, 'Creator não verificado.');

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'visibility' => ['required', 'in:public,subscribers_only'],
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'visibility' => $validated['visibility'],
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->audit->log($request->user(), 'post.created', $post);

        return response()->json(['message' => 'Publicação criada.', 'post' => $post], 201);
    }
}