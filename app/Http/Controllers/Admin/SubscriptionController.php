<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $subscriptions = Subscription::query()
            ->with(['user', 'creator.creatorProfile'])
            ->when($request->query('status') && in_array($request->query('status'), ['active', 'pending', 'cancelled', 'expired'], true), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(20);

        $counts = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', SubscriptionStatus::Active->value)->count(),
            'pending' => Subscription::where('status', SubscriptionStatus::Pending->value)->count(),
            'cancelled' => Subscription::where('status', SubscriptionStatus::Cancelled->value)->count(),
            'expired' => Subscription::where('status', SubscriptionStatus::Expired->value)->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'counts'));
    }
}