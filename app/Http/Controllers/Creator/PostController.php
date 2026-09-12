<?php

namespace App\Http\Controllers\Creator;

use App\Enums\PostVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Models\PostMedia;
use App\Services\AuditService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        protected MediaService $media,
        protected AuditService $audit,
    ) {
    }

    public function index(): View
    {
        $posts = auth()->user()
            ->posts()
            ->with('media')
            ->latest()
            ->paginate(12);

        return view('creator.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('creator.posts.create');
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        // SECURITY: privileged fields (user_id, status, published_at) set explicitly, not via mass assignment
        $post = new Post();
        $post->user_id = auth()->id();
        $post->body = $request->string('body');
        $post->visibility = (string) $request->string('visibility');
        $post->status = 'published';
        $post->published_at = now();
        $post->save();

        if ($request->hasFile('media')) {
            $builder = $post->media();

            foreach ($request->file('media') as $sort => $file) {
                $mediaType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $data = $this->media->storeForPost(auth()->user(), $file, $mediaType);

                $builder->create(array_merge($data, ['sort_order' => $sort]));
            }
        }

        $this->audit->log(auth()->user(), 'post.created', $post, null, $post->only('visibility', 'body'));

        return redirect()->route('creator.posts.index')->with('status', 'Publicação criada com sucesso.');
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        return view('creator.posts.edit', compact('post'));
    }

    public function update(PostStoreRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $old = $post->only(['body', 'visibility']);

        $post->update([
            'body' => $request->string('body'),
            'visibility' => (string) $request->string('visibility'),
        ]);

        if ($request->hasFile('media')) {
            $builder = $post->media();
            $last = $post->media()->max('sort_order') ?? -1;

            foreach ($request->file('media') as $sort => $file) {
                $mediaType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                $data = $this->media->storeForPost(auth()->user(), $file, $mediaType);

                $builder->create(array_merge($data, ['sort_order' => $last + 1 + $sort]));
            }
        }

        $this->audit->log(auth()->user(), 'post.updated', $post, $old, $post->only(['body', 'visibility']));

        return redirect()->route('creator.posts.index')->with('status', 'Publicação atualizada.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        foreach ($post->media as $media) {
            $this->media->deleteMedia($media);
        }

        $this->audit->log(auth()->user(), 'post.deleted', $post);

        $post->delete();

        return back()->with('status', 'Publicação excluída.');
    }

    public function deleteMedia(Post $post, PostMedia $media): RedirectResponse
    {
        $this->authorize('update', $post);

        abort_if($media->post_id !== $post->id, 404);

        $this->media->deleteMedia($media);
        $media->delete();

        return back()->with('status', 'Mídia removida.');
    }
}