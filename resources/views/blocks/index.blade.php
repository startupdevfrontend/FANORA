<x-layouts.app
    title="Usuários bloqueados — FANORA"
    description="Lista de usuários que você bloqueou."
>

    <div class="mb-6">
        <x-page-title>Bloqueados</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Usuários que você bloqueou não poderão interagir com você.</p>
    </div>

    @php($items = $blocks->getCollection())

    @if ($items->isEmpty())
        <x-empty-state
            title="Nenhum bloqueio"
            description="Quando bloquear alguém, aparecerá aquí."
        />
    @else
        <div class="card p-5">
            <table class="table-basic">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>bloqueado el</th>
                        <th class="text-right">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $block)
                        <tr>
                            <td class="flex items-center gap-3">
                                <x-avatar :path="$block->blocked->profile?->avatar_path" :name="$block->blocked->name" size="sm" />
                                <div class="min-w-0">
                                    <span class="block text-sm font-semibold text-white">{{ $block->blocked->name }}</span>
                                    <span class="text-xs text-brand-muted">{{ $block->blocked->username }}</span>
                                </div>
                            </td>
                            <td>{{ $block->created_at->format('d/m/Y') }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('blocks.destroy', $block) }}" onsubmit="return confirm('Desbloquear este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-outline sm">Desbloquear</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-pagination :paginator="$blocks" />
    @endif
</x-layouts.app>