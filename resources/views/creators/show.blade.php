<x-layouts.app
    :title="$creator->name . ' — FANORA'"
    :description="$creator->creatorProfile?->tagline ?? ('Perfil de ' . $creator->name . ' em FANORA.')"
    :canonical="route('creator.show', $creator->username)"
    :og-type="'profile'"
    :robots="'index, follow'"
>
    <x-slot:seo>
        <meta property="og:profile:username" content="{{ $creator->username }}">
    </x-slot:seo>

    <section class="relative overflow-hidden rounded-[2rem] border border-brand-border bg-brand-card">
        <div class="hero-gradient absolute inset-0"></div>

        <div class="relative aspect-[16/5] w-full overflow-hidden border-b border-white/10">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/20 via-brand-purple/15 to-brand-surface"></div>
            @if ($creator->profile?->cover_path)
                <img src="{{ app(\App\Services\MediaService::class)->avatarUrl($creator->profile->cover_path) }}" alt="" class="h-full w-full object-cover opacity-90 mix-blend-luminosity">
                <div class="absolute inset-0 bg-gradient-to-t from-brand-card via-brand-card/60 to-transparent"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/30 via-brand-purple/20 to-brand-card"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-brand-magenta/20 via-transparent to-transparent"></div>
            @endif
        </div>

        <div class="glass relative mx-4 -mt-16 mb-4 rounded-2xl border-white/10 p-6 shadow-2xl shadow-black/30 sm:mx-8 sm:-mt-20 sm:p-8">
            <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start sm:gap-8">
                <div class="relative -mt-20 sm:-mt-24">
                    <x-avatar :path="$creator->profile?->avatar_path" :name="$creator->name" size="xl" :glow="true" />
                    @if ($creator->creatorProfile?->isApproved())
                        <span class="absolute -bottom-1 -right-1 inline-flex h-8 w-8 items-center justify-center rounded-full border-[3px] border-brand-card bg-gradient-to-br from-brand-magenta to-brand-purple text-sm text-white shadow-lg shadow-brand-magenta/40" title="Verificado">✓</span>
                    @endif
                </div>

                <div class="min-w-0 flex-1 text-center sm:text-left">
                    <h1 class="flex items-center justify-center gap-2 text-3xl font-black tracking-tight sm:justify-start sm:text-4xl">
                        <span class="bg-gradient-to-r from-white via-white to-white/80 bg-clip-text text-transparent">
                            {{ $creator->creatorProfile->display_name ?? $creator->name }}
                        </span>
                    </h1>
                    <p class="mt-1 text-base font-medium text-brand-muted">@<span class="text-brand-magenta">{{ $creator->username }}</span></p>

                    @if (filled($creator->creatorProfile?->tagline))
                        <p class="mt-3 text-base italic text-white/80">“{{ $creator->creatorProfile->tagline }}”</p>
                    @endif

                    @if ($creator->creatorProfile->categories->isNotEmpty())
                        <div class="mt-4 flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                            @foreach ($creator->creatorProfile->categories as $category)
                                <x-badge color="purple" class="px-3 py-1 text-xs shadow-lg shadow-brand-purple/10">{{ $category->name }}</x-badge>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                    @if (request()->user())
                        <form method="POST" action="{{ route('creator.follow', $creator->username) }}" class="w-full sm:w-auto">
                            @csrf
                            <button class="btn-outline sm w-full">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                {{ $isFollowing ? 'Seguindo ✓' : 'Seguir' }}
                            </button>
                        </form>
                    @endif

                    @if (! $hasActiveSubscription)
                        <a href="#subscribe" class="btn-primary sm flex w-full items-center justify-center sm:w-auto shadow-glow-magenta">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Assinar
                            @if ($creator->creatorProfile?->subscription_price_cents)
                                <span class="font-bold">R$ {{ number_format($creator->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}</span>
                                <span class="text-xs font-normal opacity-80">/mês</span>
                            @endif
                        </a>
                    @else
                        <div class="flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-2.5 text-sm font-semibold text-emerald-400 shadow-lg shadow-emerald-500/10">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Assinante ativo
                        </div>
                    @endif
                </div>
            </div>

            @if (request()->user() && request()->user()->id !== $creator->id)
                <div class="mt-6 flex items-center justify-center gap-4 border-t border-white/10 pt-5 sm:justify-end">
                    <x-modal title="Denunciar">
                        <x-slot:trigger>
                            <button type="button" class="text-xs font-medium text-brand-muted underline underline-offset-2 transition hover:text-danger">Denunciar perfil</button>
                        </x-slot:trigger>

                        <form method="POST" action="{{ route('reports.store') }}">
                            @csrf
                            <input type="hidden" name="reportable_type" value="user">
                            <input type="hidden" name="reportable_id" value="{{ $creator->id }}">

                            <x-select
                                name="reason"
                                label="Motivo"
                                :options="[
                                    ['value' => 'illegal_content', 'label' => 'Conteúdo ilegal'],
                                    ['value' => 'spam', 'label' => 'Spam'],
                                    ['value' => 'fraud', 'label' => 'Fraude'],
                                    ['value' => 'harassment', 'label' => 'Assédio'],
                                    ['value' => 'copyright', 'label' => 'Violação de direitos autorais'],
                                    ['value' => 'minor', 'label' => 'Conteúdo envolvendo menor'],
                                    ['value' => 'other', 'label' => 'Outro'],
                                ]"
                                placeholder="Selecionar motivo"
                                required
                            />
                            <x-textarea name="description" label="Detalles (opcional)" rows="3" placeholder="Explica o que aconteceu…" />

                            <x-button type="submit" variant="danger" block>Enviar denúncia</x-button>
                        </form>
                    </x-modal>

                    @if (! $isBlocked)
                        <form method="POST" action="{{ route('blocks.store', $creator->username) }}" class="inline"
                              onsubmit="return confirm('Bloquear este usuário? Não voltará a aparecer suas publicações.');">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-brand-muted underline underline-offset-2 transition hover:text-danger">Bloquear</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <section class="mt-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card
                title="Assinantes"
                :value="number_format($creator->creatorProfile->subscriber_count, 0, ',', '.')"
                accent
            >
                <x-slot:icon>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </x-slot:icon>
                <x-slot:hint>Total de seguidores</x-slot:hint>
            </x-stat-card>

            <x-stat-card
                title="Publicações"
                :value="number_format($visiblePosts->total(), 0, ',', '.')"
            >
                <x-slot:icon>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </x-slot:icon>
                <x-slot:hint>Conteúdos publicados</x-slot:hint>
            </x-stat-card>

            <x-stat-card
                title="Exclusivos"
                :value="number_format($creator->posts()->where('visibility', 'exclusive')->count(), 0, ',', '.')"
            >
                <x-slot:icon>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </x-slot:icon>
                <x-slot:hint>Conteúdo para assinantes</x-slot:hint>
            </x-stat-card>

            @if ($creator->creatorProfile?->subscription_price_cents)
                <x-stat-card
                    title="Plano"
                    :value="'R$ ' . number_format($creator->creatorProfile->subscription_price_cents / 100, 2, ',', '.')"
                    accent
                >
                    <x-slot:icon>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-slot:icon>
                    <x-slot:hint>Por mês</x-slot:hint>
                </x-stat-card>
            @else
                <x-stat-card
                    title="Gratuito"
                    value="—"
                >
                    <x-slot:icon>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </x-slot:icon>
                    <x-slot:hint>Acesso livre</x-slot:hint>
                </x-stat-card>
            @endif
        </div>
    </section>

    @if (! $hasActiveSubscription && $creator->creatorProfile?->subscription_price_cents)
        <section id="subscribe" class="mt-10 relative overflow-hidden rounded-[2rem] border border-brand-magenta/40 shadow-2xl shadow-brand-magenta/10">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/15 via-brand-purple/10 to-brand-card"></div>
            <div class="hero-gradient absolute inset-0"></div>
            <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-brand-magenta/20 blur-[100px]"></div>
            <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-brand-purple/20 blur-[100px]"></div>

            <div class="glass relative rounded-[2rem] border-white/10 p-8 sm:p-12">
                <div class="grid items-center gap-10 lg:grid-cols-2">
                    <div class="text-center lg:text-left">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-magenta to-brand-purple shadow-xl shadow-brand-magenta/30 lg:mx-0">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h2 class="text-3xl font-black tracking-tight sm:text-4xl">
                            <span class="text-gradient-brand">Desbloqueie</span> o conteúdo exclusivo
                        </h2>
                        <p class="mt-3 text-base text-brand-muted sm:text-lg">
                            Assine para acessar publicações exclusivas, mídias privadas e conteúdos premium de <strong class="text-white">{{ $creator->name }}</strong>.
                        </p>

                        <ul class="mt-6 space-y-3 text-sm text-white/80">
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Acesso a todas as publicações exclusivas
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Download de mídias em alta qualidade
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Cancele quando quiser, sem multas
                            </li>
                        </ul>
                    </div>

                    <div class="relative">
                        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-1">
                            <div class="rounded-[22px] bg-gradient-to-br from-brand-card via-brand-card to-brand-surface p-7">
                                <div class="mb-6 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-brand-muted">Plano mensal</span>
                                    <x-badge color="magenta" class="shadow-glow-magenta">Mais popular</x-badge>
                                </div>

                                <div class="mb-8">
                                    <div class="flex items-end gap-1">
                                        <span class="text-5xl font-black tracking-tight text-white">
                                            R$ {{ number_format($creator->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}
                                        </span>
                                        <span class="mb-2 text-lg font-medium text-brand-muted">/mês</span>
                                    </div>
                                    <p class="mt-1 text-xs text-brand-muted">Cobrado mensalmente</p>
                                </div>

                                @if (request()->user())
                                    <form method="POST" action="{{ route('subscriptions.store', $creator->username) }}">
                                        @csrf
                                        <button class="btn-primary btn-lg w-full shadow-glow-magenta">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            Assinar com cartão
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn-primary btn-lg w-full shadow-glow-magenta">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                        Entrar para assinar
                                    </a>
                                @endif

                                <p class="mt-4 text-center text-xs text-brand-muted">
                                    🔒 Pagamento seguro via cartão de crédito. Cancelamento com 1 clique.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="mt-12">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <h2 class="section-title">Publicações</h2>
                <p class="text-sm text-brand-muted">
                    @if ($hasActiveSubscription)
                        Você tem acesso completo a todo o conteúdo.
                    @else
                        Veja as publicações públicas e assine para desbloquear as exclusivas.
                    @endif
                </p>
            </div>
        </div>

        @php
            $posts = $visiblePosts->getCollection();
        @endphp

        @if ($posts->isEmpty())
            <x-empty-state title="Sin publicaciones todavía" description="Este creator ainda não publicou nada." />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @php
                        $canViewThis = $post->visibility === 'public' || $hasActiveSubscription;
                    @endphp
                    <article class="card group overflow-hidden rounded-2xl border-brand-border/80">
                        <a href="{{ route('posts.show', [$creator->username, $post]) }}" class="block">
                            @if ($canViewThis && $post->media->isNotEmpty())
                                @php($firstMedia = $post->media->first())
                                <div class="aspect-video overflow-hidden bg-gradient-to-br from-brand-card to-brand-surface">
                                    <img src="{{ $mediaUrls[$firstMedia->id] ?? '' }}" alt="" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-105">
                                </div>
                            @else
                                <div class="aspect-video overflow-hidden bg-gradient-to-br from-brand-card via-brand-surface to-brand-card">
                                    <div class="grid h-full w-full place-items-center">
                                        @if ($post->isExclusive())
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-brand-magenta/30 bg-brand-magenta/10">
                                                    <svg class="h-8 w-8 text-brand-magenta" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                </div>
                                                <span class="text-xs font-semibold text-brand-magenta">Conteúdo exclusivo</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-brand-muted">Sem imagem</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </a>

                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                @if ($post->isExclusive())
                                    <x-badge color="magenta" class="shadow-md shadow-brand-magenta/10">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Exclusivo
                                    </x-badge>
                                @else
                                    <x-badge>
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Público
                                    </x-badge>
                                @endif
                                <time datetime="{{ $post->published_at?->toIso8601String() }}" class="text-xs text-brand-muted">{{ $post->published_at?->diffForHumans() }}</time>
                            </div>

                            <a href="{{ route('posts.show', [$creator->username, $post]) }}">
                                <h3 class="mt-3 line-clamp-2 text-[15px] leading-snug font-semibold text-white/90 transition group-hover:text-white">{{ $post->body }}</h3>
                            </a>

                            @if (! $canViewThis)
                                <div class="mt-4 rounded-xl border border-brand-magenta/30 bg-brand-magenta/5 p-3">
                                    <a href="#subscribe" class="flex items-center justify-between text-xs font-semibold text-brand-magenta">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            Assine para desbloquear
                                        </span>
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                <x-pagination :paginator="$visiblePosts" />
            </div>
        @endif
    </section>
</x-layouts.app>
