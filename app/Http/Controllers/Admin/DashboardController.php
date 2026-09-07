<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $usersCount = User::count();
        $creatorsCount = User::has('creatorProfile')->count();
        $activeSubscriptions = Subscription::where('status', SubscriptionStatus::Active->value)->count();
        $postsCount = Post::count();
        $pendingReports = Report::where('status', ReportStatus::Pending->value)->count();

        $grossCents = (int) \App\Models\PaymentTransaction::where('status', TransactionStatus::Paid->value)->sum('gross_amount_cents');
        $platformCents = (int) \App\Models\PaymentTransaction::where('status', TransactionStatus::Paid->value)->sum('commission_cents') - (int) \App\Models\PaymentTransaction::where('status', TransactionStatus::Paid->value)->sum('gateway_fee_cents');

        $recentUsers = User::latest()->limit(8)->get();
        $recentCreators = User::has('creatorProfile')->with('creatorProfile')->latest()->limit(8)->get();
        $recentTransactions = \App\Models\PaymentTransaction::latest()->limit(8)->get();
        $recentReports = Report::with('reporter')->latest()->limit(8)->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'creatorsCount',
            'activeSubscriptions',
            'postsCount',
            'pendingReports',
            'grossCents',
            'platformCents',
            'recentUsers',
            'recentCreators',
            'recentTransactions',
            'recentReports',
        ));
    }
}