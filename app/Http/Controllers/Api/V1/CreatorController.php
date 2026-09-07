<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CreatorProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CreatorProfile::query()
            ->with(['user.profile', 'categories'])
            ->where('verification_status', 'approved');

        if ($search = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q
                ->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%"))
                ->orWhere('tagline', 'like', "%{$search}%"));
        }

        if ($request->query('category')) {
            $query->whereHas('categories', fn ($c) => $c->where('slug', $request->query('category')));
        }

        return response()->json($query->paginate(15));
    }

    public function show(string $username): JsonResponse
    {
        $user = User::where('username', $username)
            ->with(['profile', 'creatorProfile.categories'])
            ->firstOrFail();

        if (! $user->creatorProfile || ! $user->isActive()) {
            return response()->json(['message' => 'Creator não encontrado.'], 404);
        }

        $posts = $user->posts()
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->with('media')
            ->latest()
            ->paginate(10);

        return response()->json([
            'creator' => $user,
            'posts' => $posts,
        ]);
    }
}