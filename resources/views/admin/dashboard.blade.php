<x-layouts.dashboard title="Admin — FANORA" admin="true" :active="'dashboard'">

    <div class="mb-6">
        <x-page-title>Panel de administración</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Resumen do estado da plataforma.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <x-stat-card title="Usuários" :value="$usersCount" />
        <x-stat-card title="Creators" :value="$creatorsCount" />
        <x-stat-card title="Assinaturas ativas" :value="$activeSubscriptions" />
        <x-stat-card title="Receita bruta" :value="'R$ ' . number_format($grossCents / 100, 2, ',', '.')" />
        <x-stat-card title="Receita da plataforma" :value="'R$ ' . number_format($platformCents / 100, 2, ',', '.')" :accent="true" />
        <x-stat-card title="Denúncias pendentes" :value="$pendingReports" />
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Últimos usuários</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-brand-magenta">Ver todos →</a>
            </div>

            <table class="table-basic mt-3">
                <thead>
                    <tr><th>Nome</th><th>E-mail</th><th class="text-right">ID</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td class="text-brand-muted">{{ $user->email }}</td>
                            <td class="text-right">{{ $user->id }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-sm text-brand-muted">Sin dados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Creators recentes</h2>
                <a href="{{ route('admin.creators') }}" class="text-sm text-brand-magenta">Ver todos →</a>
            </div>

            <table class="table-basic mt-3">
                <thead>
                    <tr><th>Creator</th><th class="text-right">Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentCreators as $user)
                        <tr>
                            <td>{{ $user->creatorProfile?->display_name ?? $user->name }}</td>
                            <td class="text-right"><x-badge :color="$user->creatorProfile?->verification_status === 'approved' ? 'green' : 'yellow'">{{ ucfirst($user->creatorProfile?->verification_status ?? 'pending') }}</x-badge></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-sm text-brand-muted">Sin dados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Transações recentes</h2>
                <a href="{{ route('admin.transactions') }}" class="text-sm text-brand-magenta">Ver todas →</a>
            </div>

            <table class="table-basic mt-3">
                <thead>
                    <tr><th>ID</th><th class="text-right">Bruto</th><th class="text-right">Neto</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $tx)
                        <tr>
                            <td>#{{ $tx->id }}</td>
                            <td class="text-right">R$ {{ number_format($tx->gross_amount_cents / 100, 2, ',', '.') }}</td>
                            <td class="text-right">R$ {{ number_format($tx->creator_amount_cents / 100, 2, ',', '.') }}</td>
                            <td><x-badge :color="match ($tx->status) { 'paid' => 'green', 'pending' => 'yellow', 'failed','refunded' => 'red', default => 'neutral' }">{{ $tx->status->label() }}</x-badge></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-sm text-brand-muted">Sin datos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Denúncias recentes</h2>
                <a href="{{ route('admin.reports.index') }}" class="text-sm text-brand-magenta">Ver todas →</a>
            </div>

            <table class="table-basic mt-3">
                <thead>
                    <tr><th>ID</th><th>Motivo</th><th class="text-right">Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentReports as $report)
                        <tr>
                            <td>#{{ $report->id }}</td>
                            <td>{{ $report->reason->label() }}</td>
                            <td class="text-right"><x-badge :color="match ($report->status) { 'pending' => 'yellow', 'reviewing' => 'neutral', 'resolved' => 'green', 'rejected' => 'red', default => 'neutral' }">{{ $report->status->label() }}</x-badge></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-sm text-brand-muted">Sin datos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>