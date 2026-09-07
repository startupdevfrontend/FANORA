<x-layouts.dashboard title="Ganhancias — FANORA" :active="'earnings'">

    <div class="mb-6">
        <x-page-title>Ganhancias</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Saldo acumulado de tus assinaturas.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card title="Receita bruta" :value="'R$ ' . number_format($grossCents / 100, 2, ',', '.')" />
        <x-stat-card title="Comissão FANORA" :value="'R$ ' . number_format($commissionCents / 100, 2, ',', '.') " />
        <x-stat-card title="Taxas do gateway" :value="'R$ ' . number_format($gatewayFeesCents / 100, 2, ',', '.') " />
        <x-stat-card title="Receita líquida" :value="'R$ ' . number_format($netCents / 100, 2, ',', '.') " :accent="true" />
    </div>

    <div class="mt-8 card p-5">
        <h2 class="text-lg font-semibold">Solicitar saque</h2>
        <p class="mt-1 text-sm text-brand-muted">Valor disponível para levantamento: <strong class="text-white">R$ {{ number_format($netCents / 100, 2, ',', '.') }}</strong>.</p>

        @php($hasPendingPayout = $payouts->where('status', 'pending')->isNotEmpty())

        @if ($netCents <= 0)
            <x-alert type="warning" class="mt-3">Sin saldo disponível para saque neste período.</x-alert>
        @elseif ($hasPendingPayout)
            <x-alert type="info" class="mt-3">Ya tienes um saque pendente para este período.</x-alert>
        @else
            <form method="POST" action="{{ route('creator.earnings.payout') }}" class="mt-4">
                @csrf
                <x-button type="submit" variant="primary" :disabled="$netCents <= 0">Solicitar saque</x-button>
            </form>
        @endif
    </div>

    <div class="mt-8 card p-5">
        <h2 class="text-lg font-semibold">Transacciones</h2>

        @if ($transactions->isEmpty())
            <p class="py-4 text-sm text-brand-muted">Sin transacciones.</p>
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Assinante</th>
                            <th>Bruto</th>
                            <th>Comissão</th>
                            <th>Taxas</th>
                            <th class="text-right">Neto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at->format('d/m/Y') }}</td>
                                <td>{{ $tx->user?->username ?? '—' }}</td>
                                <td>R$ {{ number_format($tx->gross_amount_cents / 100, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($tx->commission_cents / 100, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($tx->gateway_fee_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($tx->creator_amount_cents / 100, 2, ',', '.') }}</td>
                                <td><x-badge :color="match ($tx->status) { 'paid' => 'green', 'pending' => 'yellow', 'failed','refunded' => 'red', default => 'neutral' }">{{ $tx->status->label() }}</x-badge></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$transactions" />
        @endif
    </div>

    @if ($payouts->isNotEmpty())
        <div class="mt-8 card p-5">
            <h2 class="text-lg font-semibold">Histórico de saques</h2>

            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th class="text-right">Bruto</th>
                            <th class="text-right">Taxas</th>
                            <th class="text-right">Neto</th>
                            <th>Estado</th>
                            <th>Solicitado</th>
                            <th>Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payouts as $payout)
                            <tr>
                                <td>{{ $payout->period_started_on->format('d/m/Y') }} – {{ $payout->period_ended_on->format('d/m/Y') }}</td>
                                <td class="text-right">R$ {{ number_format($payout->gross_amount_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($payout->fees_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($payout->net_amount_cents / 100, 2, ',', '.') }}</td>
                                <td><x-badge :color="match ($payout->status) { 'pending' => 'yellow', 'processing' => 'neutral', 'paid' => 'green', 'failed' => 'red', default => 'neutral' }">{{ $payout->status->label() }}</x-badge></td>
                                <td>{{ $payout->requested_at?->format('d/m/Y') ?? '—' }}</td>
                                <td>{{ $payout->paid_at?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.dashboard>