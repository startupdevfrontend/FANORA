<x-layouts.dashboard title="Assinaturas — FANORA" admin="true" :active="'subscriptions'">

    <div class="mb-6">
        <x-page-title>Assinaturas</x-page-title>
    </div>

    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        <x-badge color="neutral">Total: {{ $counts['total'] ?? 0 }}</x-badge>
        <x-badge color="green">Ativas: {{ $counts['active'] ?? 0 }}</x-badge>
        <x-badge color="yellow">Pendentes: {{ $counts['pending'] ?? 0 }}</x-badge>
        <x-badge color="red">Canceladas: {{ $counts['cancelled'] ?? 0 }}</x-badge>
        <x-badge color="red">Expiradas: {{ $counts['expired'] ?? 0 }}</x-badge>
    </div>

    @php($items = $subscriptions->getCollection())

    <div class="card p-5">
        @if ($items->isEmpty())
            <x-empty-state title="Sin assinaturas" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>Assinante</th>
                            <th>Creator</th>
                            <th class="text-right">Valor</th>
                            <th>Estado</th>
                            <th>Início</th>
                            <th>Venc.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $subscription)
                            <tr>
                                <td class="flex items-center gap-3">
                                    <x-avatar :path="$subscription->user->profile?->avatar_path" :name="$subscription->user->name" size="sm" />
                                    <span>{{ $subscription->user->username }}</span>
                                </td>
                                <td>{{ $subscription->creator->username ?? '—' }}</td>
                                <td class="text-right">R$ {{ number_format($subscription->value_cents / 100, 2, ',', '.') }}/mês</td>
                                <td><x-badge :color="match ($subscription->status) { 'active' => 'green', 'pending' => 'yellow', 'cancelled','expired' => 'red', default => 'neutral' }">{{ $subscription->status->label() }}</x-badge></td>
                                <td>{{ $subscription->starts_at?->format('d/m/Y') }}</td>
                                <td>{{ $subscription->ends_at?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$subscriptions" />
        @endif
    </div>
</x-layouts.dashboard>