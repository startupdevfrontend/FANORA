<x-layouts.dashboard title="Usuários — FANORA" admin="true" :active="'users'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Usuários</x-page-title>

        <form method="get" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
            <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Buscar…" class="input-base sm:w-64">
        </form>
    </div>

    @php($items = $users->getCollection())

    <div class="card p-5">
        @if ($items->isEmpty())
            <x-empty-state title="Sin usuarios" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuário</th>
                            <th class="hidden sm:table-cell">E-mail</th>
                            <th class="hidden sm:table-cell">Perfil</th>
                            <th>Estado</th>
                            <th>Rol</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td class="flex items-center gap-3">
                                    <x-avatar :path="$user->profile?->avatar_path" :name="$user->name" size="sm" />
                                    <div class="min-w-0">
                                        <span class="block text-sm font-medium text-white">{{ $user->name }}</span>
                                        <span class="text-xs text-brand-muted">{{ $user->username }}</span>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell text-sm text-brand-muted">{{ str($user->email)->limit(30) }}</td>
                                <td class="hidden sm:table-cell">
                                    <x-badge :color="$user->creatorProfile ? 'magenta' : 'neutral'">{{ $user->creatorProfile ? 'Creator' : '—' }}</x-badge>
                                </td>
                                <td><x-badge :color="$user->is_active ? 'green' : 'red'">{{ $user->is_active ? 'Ativo' : 'Desativado' }}</x-badge></td>
                                <td class="capitalize">{{ $user->role->label() }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn-ghost sm">Ver</a>
                                        <span>|</span>
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                                            @csrf
                                            <button title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}" class="btn-ghost sm">{{ $user->is_active ? 'Desativar' : 'Ativar' }}</button>
                                        </form>
                                        @if ($user->id !== auth()->id())
                                            <span>|</span>
                                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" onsubmit="return confirm('Suspender esta cuenta?');" class="inline">
                                                @csrf
                                                <button title="Suspender" class="btn-danger sm">Suspender</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$users" />
        @endif
    </div>
</x-layouts.dashboard>