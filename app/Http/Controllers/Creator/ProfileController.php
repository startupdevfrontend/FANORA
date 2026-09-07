<?php

namespace App\Http\Controllers\Creator;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatorProfileRequest;
use App\Models\Category;
use App\Models\CreatorProfile;
use App\Services\CreatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected CreatorService $creators)
    {
    }

    public function index(): View
    {
        $profile = auth()->user()->creatorProfile ?? new CreatorProfile();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $allowedPrices = config('fanora.allowed_subscription_prices');

        return view('creator.profile.edit', compact('profile', 'categories', 'allowedPrices'));
    }

    public function update(CreatorProfileRequest $request): RedirectResponse
    {
        $this->creators->createOrUpdateProfile(
            auth()->user(),
            $request->validated(),
            $request->input('categories', []),
        );

        return back()->with('status', 'Perfil de creator atualizado.');
    }
}