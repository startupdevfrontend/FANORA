<x-layouts.dashboard title="Transacciones — FANORA" admin="true" :active="'transactions'">

    <div class="mb-6">
        <x-page-title>Transacciones</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Movimientos financeiros da plataforma.</p>
    </div>

    <div class="grid gap-5 mb-6 sm:grid-cols-2 xl:grid-cols-3">
        <x-stat-card title="Bruto total" :value="'R$ ' . number_format($totals['gross'] / 100, 2, ',', '.')" />
        <x-stat-card title="Comissão FANORA" :value="'R$ ' . number_format($totals['commission'] / 100, 2, ',', '.')" :accent="true" />
        <x-stat-card title="Taxas do gateway" :value="'R$ ' . number_format($totals['fees'] / 100, 2, ',', '.') " />
    </div>

    @php($items = $transactions->getCollection())

    <div class="card p-5">
        @if ($items->isEmpty())
            <x-empty-state title="Sin transacciones" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Assinante</th>
                            <th>Creator</th>
                            <th class="text-right">Bruto</th>
                            <th class="text-right">Comissão</th>
                            <th class="text-right">Taxas</th>
                            <th class="text-right">Neto creator</th>
                            <th>Proveedor</th>
                            <th>Estado</th>
                            <th class="text-right">Pago el</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $tx)
                            <tr>
                                <td>#{{ $tx->id }}</td>
                                <td>{{ $tx->user?->username ?? '—' }}</td>
                                <td>{{ $tx->creator?->username ?? '—' }}</td>
                                <td class="text-right">R$ {{ number_format($tx->gross_amount_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($tx->commission_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($tx->gateway_fee_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($tx->creator_amount_cents / 100, 2, ',', '.') }}</td>
                                <td class="text-sm text-brand-muted">{{ $tx->provider ?? '—' }}</td>
                                <td><x-badge :color="match ($tx->status) { 'paid' => 'green', 'pending' => 'yellow', 'failed','refunded' => 'red', default => 'neutral' }">{{ $tx->status->label() }}</x-badge></td>
                                <td class="text-right">{{ $tx->paid_at?->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$transactions" />
        @endif
    </div>
</x-layouts.dashboard>