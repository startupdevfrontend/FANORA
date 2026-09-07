<x-layouts.dashboard title="{{ $user->name }} — FANORA" admin="true" :active="'users'">

    <div class="mb-6">
        <x-page-title>{{ $user->name }}</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Detalhes e ações administrativas sobre este usuário.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="flex gap-6 lg:col-span-3 card p-6">
            <x-avatar :path="$user->profile?->avatar_path" :name="$user->name" size="xl" />
            <div class="min-w-0">
                <h1 class="text-xl font-bold">{{ $user->name }}</h1>
                <p class="text-brand-muted">{{ $user->username }} — {{ $user->email }}</p>
                <div class="mt-2 flex gap-2">
                    <x-badge :color="$user->creatorProfile ? 'magenta' : 'neutral'">{{ $user->creatorProfile ? 'Creator' : 'Usuário' }}</x-badge>
                    <x-badge :color="$user->isAdmin() ? 'purple' : 'neutral'">{{ $user->role->label() }}</x-badge>
                    <x-badge :color="$user->is_active ? 'green' : 'red'">{{ $user->is_active ? 'Ativo' : 'Desativado' }}</x-badge>
                </div>
            </div>
        </div>

        @if ($user->creatorProfile)
            <div class="card p-5 lg:col-span-2">
                <h2 class="text-lg font-semibold">Perfil de creator</h2>
                <dl class="mt-3 grid grid-cols-2 gap-y-2 text-sm">
                    <dt class="text-brand-muted">Nome público</dt>
                    <dd>{{ $user->creatorProfile->display_name ?? 'Sin nombre' }}</dd>
                    <dt class="text-brand-muted">Prezo assinatura</dt>
                    <dd>{{ $user->creatorProfile->priceReais() ?: '—' }} </dd>
                    <dt class="text-brand-muted">Verificação</dt>
                    <dd><x-badge :color="$user->creatorProfile->verification_status === 'approved' ? 'green' : 'yellow'">{{ $user->creatorProfile->verification_status }}</x-badge></dd>
                    <dt class="text-brand-muted">Seguidores</dt>
                    <dd>{{ $user->creatorProfile->subscriber_count }}</dd>
                    <dt class="text-brand-muted">Categorias</dt>
                    <dd>{{ $user->creatorProfile->categories->pluck('name')->join(', ') ?: '—' }}</dd>
                </dl>
            </div>
        @endif

        <div class="card p-5 lg:col-span-3">
            <h2 class="text-lg font-semibold">Consentimientos</h2>

            <table class="table-basic mt-3">
                <thead>
                    <tr><th>Tipo</th><th>Versão</th><th>Aceptado el</th><th>IP origem</th></tr>
                </thead>
                <tbody>
                    @forelse ($user->consents as $consent)
                        <tr>
                            <td>{{ $consent->type->label() }}</td>
                            <td>{{ $consent->version }}</td>
                            <td>{{ $consent->approved_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $consent->source_ip ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-sm text-brand-muted">Sin consentimientos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card p-5 lg:col-span-3">
            <h2 class="text-lg font-semibold text-red-300">Acciones</h2>

            <div class="mt-3 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                    @csrf
                    <x-button variant="{{ $user->is_active ? 'danger' : 'primary' }}">{{ $user->is_active ? 'Desativar' : 'Ativar' }} conta</x-button>
                </form>

                @if (! $user->isAdmin())
                    <form method="POST" action="{{ route('admin.users.promote', $user) }}">
                        @csrf
                        <x-button variant="outline">Promover a admin</x-button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.demote', $user) }}">
                        @csrf
                        <x-button variant="outline">Rebaixar</x-button>
                    </form>
                @endif

                @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}" onsubmit="return confirm('Suspender?');">
                        @csrf
                        <x-button variant="danger">Suspender</x-button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>