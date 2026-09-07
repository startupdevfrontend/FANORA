<?php

namespace App\Http\Controllers\Creator;

use App\Enums\PayoutStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\CreatorPayout;
use App\Models\PaymentTransaction;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function __construct(protected AuditService $audit)
    {
    }

    public function __invoke(Request $request): View
    {
        $grossCents = (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->sum('gross_amount_cents');

        $commissionCents = (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->sum('commission_cents');

        $gatewayFeesCents = (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->sum('gateway_fee_cents');

        $netCents = (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->sum('creator_amount_cents');

        $transactions = PaymentTransaction::where('creator_id', auth()->id())
            ->latest()
            ->paginate(15);

        $payouts = CreatorPayout::where('creator_id', auth()->id())->latest()->limit(10)->get();

        return view('creator.earnings.index', compact(
            'grossCents',
            'commissionCents',
            'gatewayFeesCents',
            'netCents',
            'transactions',
            'payouts',
        ));
    }

    public function requestPayout(Request $request): RedirectResponse
    {
        $request->validate([]);

        $period = now()->subMonth();

        $yearMonth = $period->format('Y-m');

        $existing = CreatorPayout::where('creator_id', auth()->id())
            ->whereRaw("DATE_FORMAT(period_started_on, '%Y-%m') = ?", [$yearMonth])
            ->first();

        abort_if($existing !== null, 422, 'Já existe um saque solicitado para este período.');

        $grossCents = (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->whereBetween('paid_at', [$period->startOfMonth(), $period->endOfMonth()])
            ->sum('gross_amount_cents');

        $feesCents = (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->whereBetween('paid_at', [$period->startOfMonth(), $period->endOfMonth()])
            ->sum('commission_cents') + (int) PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->whereBetween('paid_at', [$period->startOfMonth(), $period->endOfMonth()])
            ->sum('gateway_fee_cents');

        $netCents = $grossCents - $feesCents;

        abort_if($netCents <= 0, 422, 'Não há rendimentos disponíveis para saque no período.');

        $payout = CreatorPayout::create([
            'creator_id' => auth()->id(),
            'period_started_on' => $period->startOfMonth(),
            'period_ended_on' => $period->endOfMonth(),
            'gross_amount_cents' => $grossCents,
            'fees_cents' => $feesCents,
            'net_amount_cents' => $netCents,
            'status' => PayoutStatus::Pending->value,
            'requested_at' => now(),
        ]);

        $this->audit->log(auth()->user(), 'payout.requested', $payout);

        return back()->with('status', 'Saque solicitado e encaminhado para processamento.');
    }
}