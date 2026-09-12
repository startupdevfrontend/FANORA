<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreatorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = CreatorProfile::query()
            ->with(['user.profile', 'categories'])
            ->where('verification_status', 'approved');

        if ($search = trim((string) $request->query('q'))) {
            // Input sanitization: limit length, escape wildcards, strip tags
            $search = mb_substr(strip_tags($search), 0, 64);
            $search = str_replace(['%', '_', '\\'], ['\%', '\_', '\\\\'], $search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('tagline', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->query('category')) {
            // Validate slug format to prevent injection via category param
            $slug = (string) $request->query('category');
            if (preg_match('/^[a-z0-9\-]+$/', $slug)) {
                $query->whereHas('categories', fn ($c) => $c->where('slug', $slug));
            }
        }

        if ($request->query('sort') === 'popular') {
            $query->orderByDesc('subscriber_count');
        } else {
            $query->latest();
        }

        $creators = $query->paginate(12)->withQueryString();

        $categories = Cache::remember('explore:categories', 3600, fn () => Category::where('is_active', true)->orderBy('name')->get());

        return view('explore', compact('creators', 'categories'));
    }
}