<?php

namespace App\Http\Controllers\Creator;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $subscribersTotal = Subscription::where('creator_id', $user->id)->count();
        $activeSubscribers = Subscription::where('creator_id', $user->id)
            ->where('status', SubscriptionStatus::Active->value)
            ->count();
        $postsCount = $user->posts()->count();

        $grossCents = (int) PaymentTransaction::where('creator_id', $user->id)
            ->where('status', 'paid')
            ->sum('gross_amount_cents');
        $netCents = (int) PaymentTransaction::where('creator_id', $user->id)
            ->where('status', 'paid')
            ->sum('creator_amount_cents');

        $lastSubscriptions = Subscription::where('creator_id', $user->id)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        $lastPosts = $user->posts()->with('media')->latest()->limit(5)->get();

        $lastTransactions = PaymentTransaction::where('creator_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('creator.dashboard', compact(
            'subscribersTotal',
            'activeSubscribers',
            'postsCount',
            'grossCents',
            'netCents',
            'lastSubscriptions',
            'lastPosts',
            'lastTransactions',
        ));
    }
}