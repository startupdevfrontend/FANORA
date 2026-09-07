<x-layouts.dashboard title="Painel do creator — FANORA" :active="'dashboard'">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <x-page-title>Painel do creator</x-page-title>
            <p class="mt-1 text-sm text-brand-muted">Bem-vindo de volta, {{ auth()->user()->name }}.</p>
        </div>
        <a href="{{ route('creator.posts.create') }}" class="btn-primary sm">+ Nova publicação</a>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <x-stat-card title="Assinantes" :value="$subscribersTotal" />
        <x-stat-card title="Assinantes ativos" :value="$activeSubscribers" />
        <x-stat-card title="Publicações" :value="$postsCount" />
        <x-stat-card title="Receita bruta" :value="'R$ ' . number_format($grossCents / 100, 2, ',', '.')" />
        <x-stat-card title="Receita líquida" :value="'R$ ' . number_format($netCents / 100, 2, ',', '.')" :accent="true" />
        <x-stat-card title="Crescimento" :value="((int) $activeSubscribers)" :hint="($activeSubscribers ?? 0) . ' assinantes ativos'" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="card p-5">
            <h2 class="text-lg font-semibold">Últimas assinaturas</h2>

            @forelse ($lastSubscriptions as $subscription)
                <div class="mt-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-avatar :path="$subscription->user->profile?->avatar_path" :name="$subscription->user->name" size="sm" />
                        <div>
                            <span class="block text-sm font-medium text-white">{{ $subscription->user->name }}</span>
                            <span class="text-xs text-brand-muted">{{ $subscription->user->username }}</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <x-badge :color="match ($subscription->status) {
                            'active' => 'green',
                            'pending' => 'yellow',
                            'cancelled', 'expired' => 'red',
                            default => 'neutral',
                        }">{{ $subscription->status->label() }}</x-badge>
                        <p class="mt-1 text-xs text-brand-muted">{{ $subscription->starts_at?->format('d/m/Y') }}</p>
                    </div>
                </div>
            @empty
                <p class="mt-2 text-sm text-brand-muted">Ainda não há assinaturas.</p>
            @endforelse
        </div>

        <div class="card p-5">
            <h2 class="text-lg font-semibold">Últimas publicações</h2>

            @forelse ($lastPosts as $post)
                <a href="{{ route('creator.posts.edit', $post) }}" class="mt-3 flex items-center gap-3 text-left">
                    <span class="grid h-12 w-12 place-items-center rounded-lg bg-brand-surface font-bold text-brand-magenta">{{ $post->media->count() }}</span>
                    <div class="min-w-0">
                        <p class="line-clamp-1 text-sm text-white/90">{{ str($post->body)->limit(60) }}</p>
                        <span class="text-xs text-brand-muted">{{ $post->published_at->format('d/m/Y') }}</span>
                    </div>
                </a>
            @empty
                <p class="mt-2 text-sm text-brand-muted">Nenhuma publicação.</p>
            @endforelse
        </div>

        <div class="card p-5 sm:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Últimas transações</h2>
                <a href="{{ route('creator.earnings') }}" class="text-sm text-brand-magenta">Ver todas →</a>
            </div>

            <div class="mt-3 overflow-x-auto">
                @if ($lastTransactions->isEmpty())
                    <p class="text-sm text-brand-muted">Sem transações ainda.</p>
                @else
                    <table class="table-basic">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Bruto</th>
                                <th>Comissão</th>
                                <th class="text-right">Neto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lastTransactions as $tx)
                                <tr>
                                    <td>{{ $tx->created_at->format('d/m/Y') }}</td>
                                    <td>R$ {{ number_format($tx->gross_amount_cents / 100, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($tx->commission_cents / 100, 2, ',', '.') }}</td>
                                    <td class="text-right">R$ {{ number_format($tx->creator_amount_cents / 100, 2, ',', '.') }}</td>
                                    <td><x-badge :color="match ($tx->status) { 'paid' => 'green', 'pending' => 'yellow', 'failed','refunded' => 'red', default => 'neutral' }">{{ $tx->status->label() }}</x-badge></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>