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
        abort_unless(auth()->user()->isVerifiedCreator(), 403, 'Área exclusiva para creators verificados.');

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

        $pendingPayoutExists = CreatorPayout::where('creator_id', auth()->id())
            ->where('status', PayoutStatus::Pending->value)
            ->exists();

        return view('creator.earnings.index', compact(
            'grossCents',
            'commissionCents',
            'gatewayFeesCents',
            'netCents',
            'transactions',
            'payouts',
            'pendingPayoutExists',
        ));
    }

    public function requestPayout(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isVerifiedCreator(), 403, 'Área exclusiva para creators verificados.');

        $yearMonth = $request->input('period', now()->format('Y-m'));

        // Validate period format (Y-m) — portable across DB drivers.
        if (! preg_match('/^\d{4}-\d{2}$/', (string) $yearMonth)) {
            return back()->withErrors(['period' => 'Período inválido.']);
        }

        [$year, $month] = array_map('intval', explode('-', $yearMonth));
        $start = now()->setDate($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        if ($start->isFuture()) {
            return back()->withErrors(['period' => 'Período não pode estar no futuro.']);
        }

        $existing = CreatorPayout::where('creator_id', auth()->id())
            ->whereBetween('period_started_on', [$start, $end])
            ->first();

        if ($existing !== null) {
            return back()->withErrors(['period' => 'Já existe um saque solicitado para este período.']);
        }

        $paidBase = fn () => PaymentTransaction::where('creator_id', auth()->id())
            ->where('status', TransactionStatus::Paid->value)
            ->whereBetween('paid_at', [$start, $end]);

        $grossCents = (int) $paidBase()->sum('gross_amount_cents');
        $commissionCents = (int) $paidBase()->sum('commission_cents');
        $feesCents = $commissionCents + (int) $paidBase()->sum('gateway_fee_cents');
        $netCents = $grossCents - $feesCents;

        abort_if($netCents <= 0, 422, 'Não há rendimentos disponíveis para saque no período.');

        $minCents = (int) config('fanora.min_payout_cents', 5000);

        if ($netCents < $minCents) {
            return back()->withErrors(['period' => sprintf('Valor mínimo para saque: R$ %s.', number_format($minCents / 100, 2, ',', '.'))]);
        }

        $payout = CreatorPayout::create([
            'creator_id' => auth()->id(),
            'period_started_on' => $start,
            'period_ended_on' => $end,
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