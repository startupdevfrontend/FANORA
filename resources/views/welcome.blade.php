<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FANORA — Seu conteúdo. Seu público. Seu espaço.</title>
        <meta name="description" content="Plataforma de assinatura onde creators constróem audiência, publicam conteúdo exclusivo e monetizam diretamente.">

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                :root{--font-sans:"Instrument Sans", ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";--color-brand-black:#080808;--color-brand-surface:#111111;--color-brand-card:#171717;--color-brand-border:#262626;--color-brand-input:#1f1f1f;--color-brand-magenta:#e91e63;--color-brand-magenta-dark:#c2185b;--color-brand-purple:#7c3aed;--color-brand-purple-dark:#6d28d9;--color-brand-muted:#a3a3a3;}
                *,:after,:before{box-sizing:border-box;border:0;margin:0;padding:0}
                html{-webkit-text-size-adjust:100%;tab-size:4;line-height:1.5;font-family:var(--font-sans);-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
                body{background:#080808;color:#fff;min-height:100vh}
                img,svg,video{display:block;max-width:100%;height:auto}
                a{color:inherit;text-decoration:none}
            </style>
        @endif
    </head>
    <body class="bg-brand-black text-white font-sans antialiased min-h-screen flex flex-col">
        @if (Route::has('login'))
            <nav class="w-full px-6 py-5 flex justify-end items-center gap-3">
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl font-semibold px-4 py-2.5 text-sm bg-brand-magenta text-white shadow-sm shadow-brand-magenta/25 hover:bg-brand-magenta-dark active:scale-[.98] transition"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl font-semibold px-5 py-2.5 text-sm text-white border border-brand-border bg-transparent hover:border-brand-magenta/60 hover:bg-brand-card transition"
                    >
                        Entrar
                    </a>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl font-semibold px-5 py-2.5 text-sm bg-brand-magenta text-white shadow-lg shadow-brand-magenta/25 hover:bg-brand-magenta-dark hover:shadow-xl hover:shadow-brand-magenta/40 active:scale-[.98] transition"
                        >
                            Criar conta
                        </a>
                    @endif
                @endauth
            </nav>
        @endif

        <main class="flex-1 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/20 via-transparent to-brand-purple/20"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 h-[500px] w-[900px] rounded-full bg-brand-magenta/15 blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 h-[400px] w-[600px] rounded-full bg-brand-purple/20 blur-[120px]"></div>
            <div class="absolute -bottom-20 -left-20 h-[350px] w-[350px] rounded-full bg-brand-magenta/10 blur-[100px]"></div>

            <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 md:py-20 lg:py-24">
                <div class="flex flex-col items-center text-center">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-magenta/30 bg-brand-magenta/10 px-4 py-1.5 text-xs font-medium text-brand-magenta backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Plataforma para maiores de 18 anos
                    </div>

                    <div class="relative mb-10 mt-6">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-48 w-48 md:h-64 md:w-64 rounded-full bg-gradient-to-br from-brand-magenta/40 to-brand-purple/40 blur-3xl"></div>
                        </div>
                        <div class="relative">
                            <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-brand-magenta/25 to-brand-purple/25 blur-2xl"></div>
                            <div class="relative rounded-3xl border border-white/10 bg-white/5 p-8 md:p-12 shadow-2xl shadow-brand-magenta/10 backdrop-blur-xl">
                                <img src="{{ asset('img/logo-full-dark.png') }}" alt="FANORA" class="h-20 md:h-28 w-auto drop-shadow-2xl mx-auto">
                            </div>
                        </div>
                    </div>

                    <h1 class="text-4xl font-black leading-[0.95] tracking-tighter md:text-6xl lg:text-7xl max-w-4xl">
                        Seu conteúdo.<br>
                        <span class="mt-2 inline-block bg-gradient-to-r from-brand-magenta via-pink-400 to-brand-purple bg-clip-text text-transparent">
                            Seu público. Seu espaço.
                        </span>
                    </h1>

                    <p class="mt-8 max-w-2xl text-lg leading-relaxed text-brand-muted md:text-xl">
                        FANORA é o espaço onde creators publicam conteúdo exclusivo, constróem audiência própria e monetizam diretamente com assinaturas recorrentes.
                    </p>

                    <div class="mt-12 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-center w-full max-w-xl">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl font-bold px-8 py-4 text-base bg-brand-magenta text-white shadow-2xl shadow-brand-magenta/30 hover:bg-brand-magenta-dark hover:shadow-brand-magenta/50 active:scale-[.98] transition ring-2 ring-brand-magenta/30 ring-offset-2 ring-offset-brand-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Criar conta
                            </a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl font-bold px-8 py-4 text-base text-white border-2 border-white/10 bg-white/5 backdrop-blur-xl hover:border-brand-magenta/50 hover:bg-white/10 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Entrar
                            </a>
                        @endif
                    </div>
                </div>

                <div class="mt-20 md:mt-28 grid gap-5 md:grid-cols-3">
                    <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/50 p-6 md:p-8 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-magenta/40 hover:shadow-2xl hover:shadow-brand-magenta/10">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-brand-magenta/10 blur-2xl opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-brand-magenta/20 to-brand-magenta/10 text-brand-magenta ring-1 ring-brand-magenta/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <p class="mt-5 text-3xl md:text-4xl font-black tracking-tight text-brand-magenta">100%</p>
                            <p class="mt-2 text-sm md:text-base font-medium text-brand-muted">seu conteúdo, sua comunidade</p>
                        </div>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/50 p-6 md:p-8 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-purple/40 hover:shadow-2xl hover:shadow-brand-purple/10">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-brand-purple/10 blur-2xl opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-brand-purple/20 to-brand-purple/10 text-brand-purple ring-1 ring-brand-purple/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="mt-5 text-3xl md:text-4xl font-black tracking-tight text-brand-purple">R$9,90+</p>
                            <p class="mt-2 text-sm md:text-base font-medium text-brand-muted">assinaturas desde preços acessíveis</p>
                        </div>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl border border-brand-border/60 bg-gradient-to-br from-brand-card to-brand-card/50 p-6 md:p-8 shadow-xl shadow-black/20 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/20 hover:shadow-2xl hover:shadow-white/5">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5 blur-2xl opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-white/15 to-white/5 text-white ring-1 ring-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <p class="mt-5 text-3xl md:text-4xl font-black tracking-tight text-white">+18</p>
                            <p class="mt-2 text-sm md:text-base font-medium text-brand-muted">verificação e moderação ativas</p>
                        </div>
                    </div>
                </div>

                <div class="mt-20 md:mt-28 relative overflow-hidden rounded-[2rem] border border-brand-magenta/20 p-8 md:p-16">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/20 via-brand-purple/15 to-transparent"></div>
                    <div class="absolute -top-32 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-brand-magenta/15 blur-3xl"></div>
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

                        <h2 class="text-3xl md:text-5xl font-black leading-tight tracking-tighter">
                            Cria conteúdo.<br>
                            <span class="bg-gradient-to-r from-brand-magenta via-pink-400 to-brand-purple bg-clip-text text-transparent">
                                Cobra por ele.
                            </span>
                        </h2>
                        <p class="mx-auto mt-6 max-w-xl text-base md:text-lg leading-relaxed text-brand-muted/90">
                            Publica conteúdo exclusivo, define seu preço e ganha com sua própria audiência. Todo sem barreiras.
                        </p>

                        @if (Route::has('register'))
                            <div class="mt-10">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl font-bold px-10 py-4 text-base bg-brand-purple text-white shadow-2xl shadow-brand-purple/30 hover:bg-brand-purple-dark hover:shadow-brand-purple/50 active:scale-[.98] transition ring-2 ring-brand-purple/30 ring-offset-2 ring-offset-brand-black">
                                    Criar meu perfil de creator
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 border-t border-brand-border/50 py-8 px-6 text-center text-sm text-brand-muted">
            <div class="flex items-center justify-center gap-3 mb-3">
                <img src="{{ asset('img/logo-circular.png') }}" alt="FANORA" class="h-7 w-7">
                <span class="font-bold text-white">FANORA</span>
            </div>
            <p>&copy; {{ date('Y') }} FANORA. Todos os direitos reservados.</p>
        </footer>
    </body>
</html>
