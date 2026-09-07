<x-layouts.app
    title="Explorar creators"
    description="Descubre creadores de todas las categorías, busca por nombre o usuario y encuentra tu próximo contenido exclusivo."
>

    <div class="mb-6">
        <x-page-title>Explorar creators</x-page-title>

        <form method="get" action="{{ route('explore') }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="relative w-full">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
                    <path d="M16.5 11.5 21 11.5 16.5 8 21 8 21 15 16.5 15" stroke-linecap="round"></path>
                </svg>
                <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Buscar por nome o usuario…"
                    class="input-base pl-10">
            </div>

            <x-select
                name="sort"
                label="Ordenar"
                :options="[['value' => 'recent', 'label' => 'Recentes'], ['value' => 'popular', 'label' => 'Populares']]"
                :value="request()->query('sort')"
            />
        </form>
    </div>

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('explore') }}" class="pill {{ request()->has('category') ? '' : 'active-pill' }}">Todas</a>
        @foreach ($categories as $category)
            <a href="{{ route('explore', ['category' => $category->slug]) }}" class="pill {{ request()->query('category') == $category->slug ? 'active-pill' : '' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    @php
        $actualCreators = $creators->getCollection();
    @endphp

    @if ($actualCreators->isEmpty())
        <x-empty-state
            title="Nenhum creator encontrado"
            description="Tente outro termo de busca ou outra categoria."
        >
            <a href="{{ route('explore') }}" class="btn-primary sm">Limpar filtros</a>
        </x-empty-state>
    @else
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($actualCreators as $profile)
                <x-creator-card :creator="$profile->user" :profile="$profile" />
            @endforeach
        </div>

        <x-pagination :paginator="$creators" />
    @endif
</x-layouts.app>