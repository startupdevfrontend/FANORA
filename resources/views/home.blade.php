<x-layouts.app
    title="FANORA — Seu conteúdo. Seu público. Seu espaço."
    description="Plataforma de assinatura onde creators constróem audiência, publicam conteúdo exclusivo e monetizam diretamente."
>

    <section class="relative overflow-hidden rounded-[2rem] border border-brand-border/50 p-8 md:p-14">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/20 via-brand-purple/15 to-transparent"></div>
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-brand-magenta/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-brand-purple/20 blur-3xl"></div>

        <div class="relative z-10 grid items-center gap-10 lg:grid-cols-2">
            <div class="text-center lg:text-left">
                <p class="mb-6 inline-flex items-center gap-2 rounded-full border border-brand-magenta/30 bg-brand-magenta/10 px-4 py-1.5 text-xs font-medium text-brand-magenta backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Plataforma para maiores de 18 anos
                </p>

                <h1 class="text-5xl font-black leading-[0.95] tracking-tighter md:text-6xl lg:text-7xl">
                    Seu conteúdo.<br>
                    <span class="mt-2 inline-block bg-gradient-to-r from-brand-magenta via-pink-400 to-brand-purple bg-clip-text text-transparent">
                        Seu público. Seu espaço.
                    </span>
                </h1>

                <p class="mx-auto mt-8 max-w-xl text-lg leading-relaxed text-brand-muted lg:mx-0">
                    FANORA é o espaço onde creators publicam conteúdo exclusivo, constróem audiência própria e monetizam diretamente com assinaturas recorrentes.
                </p>

                <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-center lg:justify-start">
                    <a href="{{ route('explore') }}" class="btn-primary btn-lg shadow-lg shadow-brand-magenta/25 hover:shadow-xl hover:shadow-brand-magenta/40">
                        Explorar creators
                    </a>
                    <a href="{{ route('register') }}" class="btn-outline btn-lg border-brand-border/80 bg-white/5 backdrop-blur-sm hover:border-brand-magenta/60 hover:bg-white/10">
                        Criar meu perfil
                    </a>
                </div>
            </div>

            <div class="relative flex items-center justify-center">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="h-72 w-72 rounded-full bg-gradient-to-br from-brand-magenta/40 to-brand-purple/40 blur-3xl"></div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-brand-magenta/30 to-brand-purple/30 blur-2xl"></div>
                    <div class="relative rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-brand-magenta/10 backdrop-blur-xl">
                        <img src="{{ asset('img/logo-circular.png') }}" alt="FANORA" class="h-56 w-56 drop-shadow-2xl md:h-72 md:w-72">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-16">
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/50 p-6 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-magenta/40 hover:shadow-2xl hover:shadow-brand-magenta/10">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-brand-magenta/10 blur-2xl transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="stat-icon h-12 w-12 rounded-xl bg-gradient-to-br from-brand-magenta/20 to-brand-magenta/10 text-brand-magenta ring-1 ring-brand-magenta/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-4xl font-black tracking-tight text-brand-magenta">100%</p>
                    <p class="mt-2 text-sm font-medium text-brand-muted">seu conteúdo, sua comunidade</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/50 p-6 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-purple/40 hover:shadow-2xl hover:shadow-brand-purple/10">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-brand-purple/10 blur-2xl transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="stat-icon h-12 w-12 rounded-xl bg-gradient-to-br from-brand-purple/20 to-brand-purple/10 text-brand-purple ring-1 ring-brand-purple/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-4xl font-black tracking-tight text-brand-purple">R$9,90+</p>
                    <p class="mt-2 text-sm font-medium text-brand-muted">assinaturas desde preços acessíveis</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/50 p-6 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/20 hover:shadow-2xl hover:shadow-white/5">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5 blur-2xl transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="stat-icon h-12 w-12 rounded-xl bg-gradient-to-br from-white/15 to-white/5 text-white ring-1 ring-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-4xl font-black tracking-tight text-white">+18</p>
                    <p class="mt-2 text-sm font-medium text-brand-muted">verificação e moderação ativas</p>
                </div>
            </div>
        </div>
    </section>

    @if ($featuredCreators->isNotEmpty())
        <section class="mt-16">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight md:text-3xl">Creators em destaque</h2>
                <a href="{{ route('explore') }}" class="text-sm font-medium text-brand-muted transition hover:text-white">Ver todos →</a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredCreators as $profile)
                    <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/80 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-magenta/40 hover:shadow-2xl hover:shadow-brand-magenta/15">
                        <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-brand-magenta/5 blur-2xl opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="relative">
                            <x-creator-card :creator="$profile->user" :profile="$profile" />
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-16">
        <h2 class="text-2xl font-bold tracking-tight md:text-3xl">Explorar por categoria</h2>
        <div class="mt-8 flex flex-wrap gap-3">
            @foreach ($categories as $category)
                <a href="{{ route('explore', ['category' => $category->slug]) }}" class="group relative overflow-hidden rounded-full border border-brand-border/60 bg-brand-card/70 px-5 py-2 text-sm font-medium text-brand-muted shadow-lg shadow-black/10 backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-brand-magenta/50 hover:bg-brand-magenta/10 hover:text-white hover:shadow-xl hover:shadow-brand-magenta/10">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="mt-20 relative overflow-hidden rounded-[2rem] border p-10 md:p-16">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/25 via-brand-purple/20 to-transparent"></div>
        <div class="absolute -top-32 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-brand-magenta/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-20 h-80 w-80 rounded-full bg-brand-purple/20 blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-3xl text-center">
            <div class="mb-8 inline-flex items-center justify-center">
                <div class="absolute h-20 w-20 rounded-full bg-gradient-to-br from-brand-magenta to-brand-purple opacity-30 blur-xl"></div>
                <div class="relative grid h-16 w-16 place-items-center rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <h2 class="text-4xl font-black leading-tight tracking-tighter md:text-5xl">
                Cria conteúdo.<br>
                <span class="bg-gradient-to-r from-brand-magenta via-pink-400 to-brand-purple bg-clip-text text-transparent">
                    Cobra por ele.
                </span>
            </h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-brand-muted/90">
                Publica conteúdo exclusivo, define seu preço e ganha com sua própria audiência. Todo sem barreiras.
            </p>

            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="btn-accent btn-lg shadow-2xl shadow-brand-purple/30 hover:shadow-brand-purple/50 ring-2 ring-brand-purple/30 ring-offset-2 ring-offset-brand-black">
                    Criar meu perfil de creator
                </a>
                <a href="{{ route('explore') }}" class="btn-outline btn-lg border-white/10 bg-white/5 backdrop-blur-sm hover:border-white/30 hover:bg-white/10">
                    Ver creators
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
