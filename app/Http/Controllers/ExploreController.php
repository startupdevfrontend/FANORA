<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = CreatorProfile::query()
            ->with(['user.profile', 'categories'])
            ->where('verification_status', 'approved');

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('tagline', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->query('category')) {
            $query->whereHas('categories', fn ($c) => $c->where('slug', $request->query('category')));
        }

        if ($request->query('sort') === 'popular') {
            $query->orderByDesc('subscriber_count');
        } else {
            $query->latest();
        }

        $creators = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('explore', compact('creators', 'categories'));
    }
}