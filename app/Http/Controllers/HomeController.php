<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreatorProfile;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredCreators = CreatorProfile::query()
            ->with(['user.profile', 'categories'])
            ->where('is_featured', true)
            ->where('verification_status', 'approved')
            ->latest()
            ->limit(6)
            ->get();

        $recentCreators = CreatorProfile::query()
            ->with(['user.profile'])
            ->where('verification_status', 'approved')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('home', compact('featuredCreators', 'recentCreators', 'categories'));
    }
}