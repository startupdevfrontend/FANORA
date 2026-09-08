<x-layouts.app
    title="Notificações — FANORA"
    description="Todas as suas notificações."
>

    <section class="mb-8 relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 shadow-2xl shadow-black/20">
        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-brand-magenta/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-brand-purple/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-magenta/30 bg-brand-magenta/10 px-3 py-1 text-xs font-semibold text-brand-magenta mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    @if (auth()->user()->unreadNotifications()->count() > 0)
                        {{ auth()->user()->unreadNotifications()->count() }} novas
                    @else
                        Sem novas
                    @endif
                </div>
                <h1 class="section-title text-4xl">Notificações</h1>
                <p class="mt-2 text-base text-brand-muted max-w-xl">Fique por dentro de tudo o que acontece com sua conta, suas assinaturas e interações na plataforma.</p>
            </div>
            <div class="flex gap-3 self-start sm:self-center">
                @if (auth()->user()->unreadNotifications()->exists())
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="btn-outline sm inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Marcar todas como lidas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    @php($items = $notifications->getCollection())

    @if ($items->isEmpty())
        <x-empty-state
            title="Nenhuma notificação"
            description="Assim que houver novidades, aparecerá aquí."
        />
    @else
        <div class="glass-card overflow-hidden">
            <ul class="divide-y divide-brand-border/60">
                @foreach ($items as $notification)
                    @php($data = $notification->data)
                    @php($url = $data['url'] ?? route('notifications.index'))
                    @php($isUnread = is_null($notification->read_at))

                    <li class="group relative transition-all duration-200">
                        <a href="{{ $url }}" class="flex items-start gap-4 p-5 transition-all duration-200 hover:bg-white/5 {{ $isUnread ? 'bg-brand-magenta/5' : '' }}">
                            <div class="relative mt-0.5 shrink-0">
                                <div class="grid h-11 w-11 place-items-center rounded-xl {{ $isUnread ? 'bg-gradient-to-br from-brand-magenta/20 to-brand-purple/20 ring-1 ring-brand-magenta/30' : 'bg-brand-card ring-1 ring-brand-border/60' }}">
                                    @if ($isUnread)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-magenta" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endif
                                </div>
                                @if ($isUnread)
                                    <span class="absolute -top-0.5 -right-0.5 h-3 w-3 rounded-full bg-brand-magenta ring-2 ring-brand-card"></span>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <p class="text-sm font-semibold {{ $isUnread ? 'text-white' : 'text-white/80' }}">{{ $data['title'] ?? 'Notificação' }}</p>
                                    @if ($isUnread)
                                        <x-badge color="magenta">Nova</x-badge>
                                    @else
                                        <x-badge>Lida</x-badge>
                                    @endif
                                </div>
                                @if (isset($data['message']))
                                    <p class="mt-1 text-sm text-brand-muted leading-relaxed">{{ $data['message'] }}</p>
                                @endif
                                <div class="mt-2 flex items-center gap-2 text-xs text-brand-muted/70">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <time datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
                                </div>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-2 h-4 w-4 shrink-0 text-brand-muted opacity-0 transition-all duration-200 group-hover:opacity-100 group-hover:text-brand-magenta group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-6">
            <x-pagination :paginator="$notifications" />
        </div>
    @endif
</x-layouts.app>
