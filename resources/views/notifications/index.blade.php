<x-layouts.app
    title="Notificações — FANORA"
    description="Todas as suas notificações."
>

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Notificações</x-page-title>

        @if (auth()->user()->unreadNotifications()->exists())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn-ghost sm">Marcar todas como lidas</button>
            </form>
        @endif
    </div>

    @php($items = $notifications->getCollection())

    @if ($items->isEmpty())
        <x-empty-state
            title="Nenhuma notificação"
            description="Assim que houver novidades, aparecerá aquí."
        />
    @else
        <ul class="space-y-3">
            @foreach ($items as $notification)
                @php($data = $notification->data)
                @php($url = $data['url'] ?? route('notifications.index'))

                <li>
                    <a href="{{ $url }}" class="flex items-start gap-3 rounded-xl border border-brand-border p-4 hover:bg-brand-surface">
                        <span class="mt-0.5 grid h-2 w-2 place-items-center rounded-full {{ $notification->read_at ? 'bg-brand-muted/50' : 'bg-brand-magenta' }}"></span>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold {{ $notification->read_at ? 'text-brand-muted' : 'text-white' }}">{{ $data['title'] ?? 'Notificação' }}</p>
                            @if (isset($data['message']))
                                <p class="mt-0.5 text-sm text-brand-muted">{{ $data['message'] }}</p>
                            @endif
                            <time datetime="{{ $notification->created_at->toIso8601String() }}" class="mt-1 block text-xs text-brand-muted/70">{{ $notification->created_at->diffForHumans() }}</time>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <x-pagination :paginator="$notifications" />
    @endif
</x-layouts.app>