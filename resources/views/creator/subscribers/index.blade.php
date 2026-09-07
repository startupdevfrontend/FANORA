<x-layouts.dashboard title="Assinantes — FANORA" :active="'subscribers'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Assinantes</x-page-title>
    </div>

    <div class="mb-5 flex gap-3 text-sm">
        <x-badge color="neutral">Total: {{ $counts['all'] ?? 0 }}</x-badge>
        <x-badge color="green">Ativos: {{ $counts['active'] ?? 0 }}</x-badge>
        <x-badge color="yellow">Pendentes: {{ $counts['pending'] ?? 0 }}</x-badge>
        <x-badge color="red">Cancelados: {{ $counts['cancelled'] ?? 0 }}</x-badge>
    </div>

    @php($items = $subs->getCollection())

    @if ($items->isEmpty())
        <x-empty-state title="Sin assinantes apenas" description="Compartilhe seu perfil público para atrair teus primeiros fãs." />
    @else
        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>Assinante</th>
                            <th class="text-right">Mensalidade</th>
                            <th>Comenzó</th>
                            <th>Vence</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $subscription)
                            <tr>
                                <td class="flex items-center gap-3">
                                    <x-avatar :path="$subscription->user->profile?->avatar_path" :name="$subscription->user->name" size="sm" />
                                    <div>
                                        <span class="block text-sm text-white">{{ $subscription->user->name }}</span>
                                        <span class="text-xs text-brand-muted">{{ $subscription->user->username }}</span>
                                    </div>
                                </td>
                                <td class="text-right">R$ {{ number_format($subscription->value_cents / 100, 2, ',', '.') }}/mês</td>
                                <td>{{ $subscription->starts_at?->format('d/m/Y') }}</td>
                                <td>{{ $subscription->ends_at?->format('d/m/Y') ?? '—' }}</td>
                                <td><x-badge :color="match ($subscription->status) { 'active' => 'green', 'pending' => 'yellow', 'cancelled','expired' => 'red', default => 'neutral' }">{{ $subscription->status->label() }}</x-badge></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <x-pagination :paginator="$subs" />
    @endif
</x-layouts.dashboard>