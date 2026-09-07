<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = PaymentTransaction::query()
            ->with(['user', 'creator'])
            ->when($request->query('status') && in_array($request->query('status'), ['pending', 'paid', 'failed', 'refunded'], true), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(20);

        $totals = [
            'gross' => (int) PaymentTransaction::where('status', 'paid')->sum('gross_amount_cents'),
            'commission' => (int) PaymentTransaction::where('status', 'paid')->sum('commission_cents'),
            'fees' => (int) PaymentTransaction::where('status', 'paid')->sum('gateway_fee_cents'),
        ];

        return view('admin.transactions.index', compact('transactions', 'totals'));
    }
}