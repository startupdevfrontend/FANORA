@props(['active' => null])
<aside class="hidden w-60 shrink-0 lg:sticky lg:top-14 lg:block lg:self-start xl:block">
    <nav class="space-y-1">
        <p class="px-3 pt-2 text-xs font-semibold uppercase tracking-wide text-brand-muted">Administración</p>

        <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ $active === 'dashboard' ? 'sidebar-item-active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ $active === 'users' ? 'sidebar-item-active' : '' }}">Usuários</a>
        <a href="{{ route('admin.creators') }}" class="sidebar-item {{ $active === 'creators' ? 'sidebar-item-active' : '' }}">Creators</a>
        <a href="{{ route('admin.posts') }}" class="sidebar-item {{ $active === 'posts' ? 'sidebar-item-active' : '' }}">Publicaciones</a>
        <a href="{{ route('admin.subscriptions') }}" class="sidebar-item {{ $active === 'subscriptions' ? 'sidebar-item-active' : '' }}">Assinaturas</a>
        <a href="{{ route('admin.transactions') }}" class="sidebar-item {{ $active === 'transactions' ? 'sidebar-item-active' : '' }}">Transacciones</a>

        <a href="{{ route('admin.reports.index') }}" class="relative sidebar-item {{ $active === 'reports' ? 'sidebar-item-active' : '' }}">
            Denúncias
            @if (($pendingReportsCount ?? 0) > 0)
                <span class="ml-auto grid h-5 min-w-5 place-items-center rounded-full bg-brand-magenta px-1.5 text-[10px] font-bold">{{ $pendingReportsCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.categories') }}" class="sidebar-item {{ $active === 'categories' ? 'sidebar-item-active' : '' }}">Categorias</a>
    </nav>
</aside>

<nav class="lg:hidden">
    <select class="sidebar-mobile-select" onchange="location.href=this.value" aria-label="Navegación admin">
        <option value="{{ route('admin.dashboard') }}" {{ $active === 'dashboard' ? 'selected' : '' }}>Dashboard</option>
        <option value="{{ route('admin.users.index') }}" {{ $active === 'users' ? 'selected' : '' }}>Usuários</option>
        <option value="{{ route('admin.creators') }}" {{ $active === 'creators' ? 'selected' : '' }}>Creators</option>
        <option value="{{ route('admin.posts') }}" {{ $active === 'posts' ? 'selected' : '' }}>Publicaciones</option>
        <option value="{{ route('admin.subscriptions') }}" {{ $active === 'subscriptions' ? 'selected' : '' }}>Assinaturas</option>
        <option value="{{ route('admin.transactions') }}" {{ $active === 'transactions' ? 'selected' : '' }}>Transacciones</option>
        <option value="{{ route('admin.reports.index') }}" {{ $active === 'reports' ? 'selected' : '' }}>Denúncias</option>
        <option value="{{ route('admin.categories') }}" {{ $active === 'categories' ? 'selected' : '' }}>Categorias</option>
    </select>
</nav>