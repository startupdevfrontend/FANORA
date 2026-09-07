<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockController extends Controller
{
    public function index(Request $request): View
    {
        $blocks = $request->user()
            ->blocks()
            ->with('blocked')
            ->latest()
            ->paginate(15);

        return view('blocks.index', compact('blocks'));
    }

    public function store(Request $request, string $username): RedirectResponse
    {
        $target = User::where('username', $username)->firstOrFail();

        $this->authorize('block', [User::class, $target]);

        Block::firstOrCreate([
            'blocker_id' => $request->user()->id,
            'blocked_id' => $target->id,
        ]);

        // Unfollow if following when blocked.
        $request->user()->following()->detach($target->id);

        return back()->with('status', 'Usuário bloqueado.');
    }

    public function destroy(Block $block): RedirectResponse
    {
        abort_if($block->blocker_id !== auth()->id(), 403);

        $block->delete();

        return back()->with('status', 'Bloqueio removido.');
    }
}