@props(['active' => null])
<aside class="hidden w-60 shrink-0 lg:sticky lg:top-14 lg:block lg:self-start xl:block">
    <nav class="space-y-1">
        <p class="px-3 pt-2 text-xs font-semibold uppercase tracking-wide text-brand-muted">Creator</p>

        <a href="{{ route('creator.dashboard') }}" class="sidebar-item {{ $active === 'dashboard' ? 'sidebar-item-active' : '' }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h1.5 3.75 3.75 4.5 3.75 4.5 12.75 4.5 12.75 7.5 10.5 7.5 10.5 9.5-3.75 19.5"/></svg>
            Visão geral
        </a>
        <a href="{{ route('creator.profile.edit') }}" class="sidebar-item {{ $active === 'profile' ? 'sidebar-item-active' : '' }}">Perfil público</a>
        <a href="{{ route('creator.posts.index') }}" class="sidebar-item {{ $active === 'posts' ? 'sidebar-item-active' : '' }}">Publicações</a>
        <a href="{{ route('creator.posts.create') }}" class="sidebar-item">+ Nova publicação</a>
        <a href="{{ route('creator.subscribers') }}" class="sidebar-item {{ $active === 'subscribers' ? 'sidebar-item-active' : '' }}">Assinantes</a>
        <a href="{{ route('creator.earnings') }}" class="sidebar-item {{ $active === 'earnings' ? 'sidebar-item-active' : '' }}">Ganancias</a>
        <a href="{{ route('creator.verification') }}" class="sidebar-item {{ $active === 'verification' ? 'sidebar-item-active' : '' }}">Verificação</a>

        <p class="px-3 pt-3 text-xs font-semibold uppercase tracking-wide text-brand-muted">Cuenta</p>
        <a href="{{ route('settings.index') }}" class="sidebar-item">Configurações</a>
        <a href="{{ route('feed') }}" class="sidebar-item">Feed público</a>
    </nav>
</aside>

<nav class="lg:hidden">
    <select class="sidebar-mobile-select" onchange="location.href=this.value" aria-label="Navegación creator">
        <option value="{{ route('creator.dashboard') }}" {{ $active === 'dashboard' ? 'selected' : '' }}>Visão geral</option>
        <option value="{{ route('creator.profile.edit') }}" {{ $active === 'profile' ? 'selected' : '' }}>Perfil público</option>
        <option value="{{ route('creator.posts.index') }}" {{ $active === 'posts' ? 'selected' : '' }}>Publicaciones</option>
        <option value="{{ route('creator.subscribers') }}" {{ $active === 'subscribers' ? 'selected' : '' }}>Assinantes</option>
        <option value="{{ route('creator.earnings') }}" {{ $active === 'earnings' ? 'selected' : '' }}>Ganancias</option>
        <option value="{{ route('creator.verification') }}" {{ $active === 'verification' ? 'selected' : '' }}>Verificação</option>
    </select>
</nav>