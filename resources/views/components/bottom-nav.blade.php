@props(['user' => null])
<nav class="fixed bottom-0 inset-x-0 z-40 border-t border-brand-border bg-brand-surface lg:hidden" aria-label="Navegación móvil">
    <div class="grid grid-cols-5 pb-1">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'text-white' : 'text-brand-muted' }}" aria-label="Inicio" :data-turbo="false">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5 3.75 7.5 3.75 7.5 12.75 7.5 12.75 7.5 10.5 7.5 10.5 5.25 12.75 5.25 12.75 4.5 18 4.5"/></svg>
            <span class="text-[10px]">Inicio</span>
        </a>

        <a href="{{ route('explore') }}" class="bottom-nav-item {{ request()->routeIs('explore') ? 'text-white' : 'text-brand-muted' }}" aria-label="Explorar">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 6 8.25 6 8.25 6 11.25 8.25 11.25 8.25 13.5 10.5 13.5 10.5 15.75 12.75 15.75 12.75 18 18 18"/></svg>
            <span class="text-[10px]">Explorar</span>
        </a>

        @if ($user)
            <a href="{{ route('subscriptions.index') }}" class="bottom-nav-item {{ request()->routeIs('subscriptions.*') ? 'text-white' : 'text-brand-muted' }}" aria-label="Assinaturas">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75 5.25 6.75 5.25 6.75 5.25 9.75 7.5 9.75 7.5 12.75 9.75 12.75 9.75 15.75 12 15.75 12 18 15.75 18"/></svg>
                <span class="text-[10px]">Assinaturas</span>
            </a>

            <a href="{{ route('notifications.index') }}" class="relative bottom-nav-item {{ request()->routeIs('notifications.*') ? 'text-white' : 'text-brand-muted' }}" aria-label="Notificaciones">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 9.75 16.5 11.25 18 11.25 18 13.5 19.5 13.5 21.75 13.5 21.75 14.25 22.5 14.25 22.5 15 19.5 15 15 16.5 15 16.5 18 12 18 12 13.5 9.75 13.5 9.75 12 7.5 12 7.5 12 9.75 7.5 9.75 5.25 8.25 5.25 6"/></svg>
                <span class="text-[10px]">Notificações</span>
                @if (($user->unreadNotificationsCount ?? 0) > 0)
                    <span class="absolute -top-0.5 right-1/2 grid h-4 min-w-4 place-items-center rounded-full bg-brand-magenta text-[9px] font-bold">{{ $user->unreadNotificationsCount }}</span>
                @endif
            </a>

            <a href="{{ $user->isCreator() ? route('creator.dashboard') : route('profile.index') }}" class="bottom-nav-item text-brand-muted" aria-label="Perfil">
                <x-avatar :path="$user->profile?->avatar_path" :name="$user->name" size="xs" />
                <span class="text-[10px]">Perfil</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="bottom-nav-item text-brand-muted" aria-label="Entrar">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25 18.75 5.25 18.75 5.25 18.75 8.25 18.75 8.25 16.5 11.25 16.5 11.25 13.5 11.25 13.5 11.25 13.5 14.25 13.5 14.25 14.25 16.5 14.25 16.5 15.75 16.5 15.75 16.5 18.75 16.5"/></svg>
                <span class="text-[10px]">Entrar</span>
            </a>
            <a href="{{ route('register') }}" class="bottom-nav-item text-white" aria-label="Cadastro">
                <span class="grid h-6 w-6 place-items-center rounded-full bg-brand-magenta text-sm font-bold">+</span>
                <span class="text-[10px]">Cadastro</span>
            </a>
        @endif
    </div>
</nav>