<?php

namespace App\Http\Controllers\Creator;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    public function __invoke(Request $request): View
    {
        $subs = Subscription::where('creator_id', auth()->id())
            ->with('user.profile')
            ->when($request->query('status') && in_array($request->query('status'), ['active', 'cancelled', 'pending', 'expired'], true), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(20);

        $counts = [
            'all' => Subscription::where('creator_id', auth()->id())->count(),
            'active' => Subscription::where('creator_id', auth()->id())->where('status', SubscriptionStatus::Active->value)->count(),
        ];

        return view('creator.subscribers.index', compact('subs', 'counts'));
    }
}