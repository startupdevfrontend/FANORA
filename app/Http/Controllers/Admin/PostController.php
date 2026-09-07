<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\AuditService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        protected AuditService $audit,
        protected MediaService $media,
    ) {
    }

    public function index(Request $request): View
    {
        $posts = Post::query()
            ->with('user.profile')
            ->when($request->query('status') && in_array($request->query('status'), ['published', 'hidden'], true), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function toggle(Post $post): RedirectResponse
    {
        $this->authorize('hide', $post);

        $newStatus = $post->status === 'published' ? 'hidden' : 'published';

        $post->update(['status' => $newStatus]);

        $this->audit->log(auth()->user(), 'post.status_updated', $post, ['status' => $post->status], ['status' => $newStatus]);

        return back()->with('status', 'Conteúdo atualizado.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('hide', $post);

        foreach ($post->media as $media) {
            $this->media->deleteMedia($media);
        }

        $this->audit->log(auth()->user(), 'post.removed_by_admin', $post);

        $post->delete();

        return back()->with('status', 'Conteúdo removido.');
    }
}