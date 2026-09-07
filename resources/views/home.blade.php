<x-layouts.app
    title="FANORA — Seu conteúdo. Seu público. Seu espaço."
    description="Plataforma de assinatura onde creators constróem audiência, publicam conteúdo exclusivo e monetizam diretamente."
>

    <section class="rounded-3xl border border-brand-border bg-brand-card px-6 py-16 text-center md:py-20">
        <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-border px-3 py-1 text-xs text-brand-muted">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            Plataforma para maiores de 18 anos
        </p>

        <h1 class="text-4xl font-black leading-tight tracking-tight md:text-6xl">
            Seu conteúdo.<br>
            <span class="text-gradient-brand">Seu público. Seu espaço.</span>
        </h1>

        <p class="mx-auto mt-6 max-w-2xl text-lg text-brand-muted">
            FANORA é o espaço onde creators publicam conteúdo exclusivo, constrúem audiência própria e monetizam
            diretamente com assinaturas recorrentes.
        </p>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('explore') }}" class="btn-primary btn-lg">Explorar creators</a>
            <a href="{{ route('register') }}" class="btn-outline btn-lg">Criar meu perfil</a>
        </div>

        <ul class="mt-14 grid gap-6 sm:grid-cols-3">
            <li class="text-center">
                <p class="text-3xl font-black text-brand-magenta">100%</p>
                <p class="mt-1 text-sm text-brand-muted">seu conteúdo, sua comunidade</p>
            </li>
            <li class="text-center">
                <p class="text-3xl font-black text-brand-purple">R$9,90+</p>
                <p class="mt-1 text-sm text-brand-muted">assinaturas desde preços acessíveis</p>
            </li>
            <li class="text-center">
                <p class="text-3xl font-black text-white">+18</p>
                <p class="mt-1 text-sm text-brand-muted">verificação e moderação ativas</p>
            </li>
        </ul>
    </section>

    @if ($featuredCreators->isNotEmpty())
        <section class="mt-14">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight">Creators em destaque</h2>
                <a href="{{ route('explore') }}" class="text-sm font-medium text-brand-muted hover:text-white">Ver todos →</a>
            </div>

            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredCreators as $profile)
                    <x-creator-card :creator="$profile->user" :profile="$profile" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-14">
        <h2 class="text-2xl font-bold tracking-tight">Explorar por categoria</h2>
        <div class="mt-6 flex flex-wrap gap-2.5">
            @foreach ($categories as $category)
                <a href="{{ route('explore', ['category' => $category->slug]) }}" class="pill hover:border-brand-magenta/50 hover:text-white">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="mt-14 rounded-3xl border border-brand-magenta/30 bg-brand-card p-10 text-center">
        <h2 class="text-3xl font-black tracking-tight">Cria conteúdo. Cobra por ele.</h2>
        <p class="mx-auto mt-4 max-w-xl text-brand-muted">
            Publica conteúdo exclusivo, define seu preço e ganha com sua própria audiência. Todo sem barreiras.
        </p>
        <a href="{{ route('register') }}" class="btn-accent btn-lg mt-8">Criar meu perfil de creator</a>
    </section>
</x-layouts.app>