<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PostVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\AuditService;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function __construct(protected MediaService $media, protected AuditService $audit)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $posts = Post::query()
            ->where('status', 'published')
            ->with(['user.profile', 'media'])
            ->where(function ($query) use ($user) {
                $query->where('visibility', PostVisibility::Public->value);

                if (! $user) {
                    return;
                }

                $query->orWhere(function ($q) use ($user) {
                    $q->where('visibility', PostVisibility::SubscribersOnly->value)
                        ->whereIn('user_id', $user->subscriptions()->where('status', 'active')->pluck('creator_id'));
                });
            })
            ->latest()
            ->paginate(15);

        return PostResource::collection($posts);
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        abort_unless($request->user()->isVerifiedCreator(), 403, 'Creator não verificado.');

        $validated = $request->validated();

        // SECURITY: privileged fields set explicitly
        $post = new Post();
        $post->user_id = $request->user()->id;
        $post->body = $validated['body'];
        $post->visibility = $validated['visibility'];
        $post->status = 'published';
        $post->published_at = now();
        $post->save();

        $this->audit->log($request->user(), 'post.created', $post);

        return response()->json(['message' => 'Publicação criada.', 'post' => new PostResource($post)], 201);
    }

    public function show(Request $request, Post $post): JsonResponse
    {
        abort_unless($post->status === 'published', 404);

        if ($post->visibility !== PostVisibility::Public) {
            abort_unless($request->user() !== null && $request->user()->activeSubscriptionFor($post->user_id) !== null, 403, 'Conteúdo exclusivo para assinantes.');
        }

        return response()->json(new PostResource($post->load('media', 'user')));
    }
}