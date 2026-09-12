<x-layouts.app
    title="Explorar creators"
    description="Descubra creators de todas as categorias, busque por nome ou usuário e encontre seu próximo conteúdo exclusivo."
>

    <section class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-brand-500/10 via-purple-500/10 to-pink-500/10 p-6 sm:p-8 backdrop-blur-sm border border-white/10">
        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-purple-500/20 blur-3xl"></div>

        <div class="relative">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                <div>
                    <x-page-title class="!mb-0">Explorar creators</x-page-title>
                    <p class="mt-2 text-sm sm:text-base text-brand-muted max-w-2xl">
                        Descubra creators incríveis de todas as categorias, busque por nome ou usuário e encontre seu próximo conteúdo exclusivo favorito.
                    </p>
                </div>
            </div>

            <form method="get" action="{{ route('explore') }}" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
                        <path d="M16.5 11.5 21 11.5 16.5 8 21 8 21 15 16.5 15" stroke-linecap="round"></path>
                    </svg>
                    <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Buscar por nome o usuario…"
                        class="input-base pl-12 pr-4 py-3 !text-base bg-white/5 border-white/10 focus:border-brand-400 focus:ring-2 focus:ring-brand-400/30 placeholder:text-brand-muted/60 shadow-lg shadow-black/5">
                </div>

                <x-select
                    name="sort"
                    label="Ordenar"
                    :options="[['value' => 'recent', 'label' => 'Recentes'], ['value' => 'popular', 'label' => 'Populares']]"
                    :value="request()->query('sort')"
                />

                <button type="submit" class="btn-primary sm inline-flex items-center justify-center gap-2 py-3 px-6 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
                        <path d="M16.5 11.5 21 11.5 16.5 8 21 8 21 15 16.5 15" stroke-linecap="round"></path>
                    </svg>
                    Buscar
                </button>
            </form>
        </div>
    </section>

    <div class="mb-8 flex flex-wrap gap-3">
        <a href="{{ route('explore') }}" class="pill px-5 py-2.5 shadow-md shadow-black/5 hover:shadow-lg hover:shadow-black/10 transition-all duration-300 {{ request()->has('category') ? '' : 'active-pill !bg-gradient-to-r !from-brand-600 !via-purple-600 !to-pink-600 !shadow-lg !shadow-brand-500/30' }}">Todas</a>
        @foreach ($categories as $category)
            <a href="{{ route('explore', ['category' => $category->slug]) }}" class="pill px-5 py-2.5 shadow-md shadow-black/5 hover:shadow-lg hover:shadow-black/10 transition-all duration-300 {{ request()->query('category') == $category->slug ? 'active-pill !bg-gradient-to-r !from-brand-600 !via-purple-600 !to-pink-600 !shadow-lg !shadow-brand-500/30' : '' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    @php
        $actualCreators = $creators->getCollection();
    @endphp

    @if ($actualCreators->isEmpty())
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-12 backdrop-blur-sm">
            <div class="absolute top-0 right-0 h-40 w-40 rounded-full bg-brand-500/10 blur-3xl"></div>
            <x-empty-state
                title="Nenhum creator encontrado"
                description="Tente outro termo de busca ou outra categoria."
            >
                <a href="{{ route('explore') }}" class="btn-primary sm">Limpar filtros</a>
            </x-empty-state>
        </div>
    @else
        <div class="relative">
            <div class="absolute -top-10 -right-10 h-52 w-52 rounded-full bg-purple-500/15 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-1/3 -left-10 h-52 w-52 rounded-full bg-pink-500/15 blur-3xl pointer-events-none"></div>

            <div class="relative grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($actualCreators as $profile)
                    <div class="group relative rounded-2xl shadow-xl shadow-black/10 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-500 hover:-translate-y-1">
                        <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-brand-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-brand-500/20 group-hover:via-purple-500/20 group-hover:to-pink-500/20 transition-all duration-500 blur opacity-0 group-hover:opacity-100 -z-10"></div>
                        <x-creator-card :creator="$profile->user" :profile="$profile" />
                    </div>
                @endforeach
            </div>
        </div>

        <x-pagination :paginator="$creators" />
    @endif
</x-layouts.app>
