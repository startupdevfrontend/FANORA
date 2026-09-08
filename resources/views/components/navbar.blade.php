@props(['user' => null])
<header class="glass sticky top-0 z-40 border-b border-brand-magenta/20 shadow-lg shadow-black/10">
    <div class="mx-auto flex max-w-5xl items-center gap-4 px-4 py-3">
        <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="FANORA — Início">
            <img src="{{ asset('img/design-01.png') }}" alt="FANORA" class="h-9 w-auto rounded-xl shadow-lg shadow-brand-magenta/20">
        </a>

        <div class="hidden items-center gap-6 text-sm font-medium text-brand-muted md:flex">
            <a href="{{ route('home') }}" class="hover:text-white">Início</a>
            <a href="{{ route('explore') }}" class="hover:text-white">Explorar</a>
            @if ($user)
                <a href="{{ route('feed') }}" class="hover:text-white">Feed</a>
            @endif
        </div>

        <div class="ml-auto flex items-center gap-2">
            @if ($user)
                <a href="{{ route('notifications.index') }}" class="relative grid h-10 w-10 place-items-center rounded-full hover:bg-brand-card" aria-label="Notificações">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 9.75 16.5 11.25 18 11.25 18 13.5 19.5 13.5 21.75 13.5 21.75 14.25 22.5 14.25 22.5 15 19.5 15 15 16.5 15 16.5 18 12 18 12 13.5 9.75 13.5 9.75 12 7.5 12 7.5 12 9.75 7.5 9.75 5.25 8.25 5.25 6"></path>
                    </svg>
                    @if (($user->unreadNotificationsCount ?? 0) > 0)
                        <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-brand-magenta px-1 text-[10px] font-bold">{{ $user->unreadNotificationsCount }}</span>
                    @endif
                </a>

                @if ($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-outline sm">Admin</a>
                @endif

                <x-dropdown align="right">
                    <x-slot:trigger>
                        <x-avatar :path="$user->profile?->avatar_path" :name="$user->name" size="md" />
                    </x-slot:trigger>

                    <div class="px-3 py-1.5 text-xs text-brand-muted">{{ $user->username }}</div>
                    <a href="{{ route('profile.index') }}" class="dropdown-item">Meu perfil</a>

                    @if ($user->isCreator())
                        <a href="{{ route('creator.dashboard') }}" class="dropdown-item">Dashboard creator</a>
                    @endif

                    <a href="{{ route('settings.index') }}" class="dropdown-item">Configurações</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger w-full text-left">Sair</button>
                    </form>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="btn-outline sm">Entrar</a>
                <a href="{{ route('register') }}" class="btn-primary sm">Cadastrar-se</a>
            @endif
        </div>
    </div>
</header>
<x-bottom-nav :user="$user" />