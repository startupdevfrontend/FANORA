<x-layouts.app
    :title="$post->user->name . ' — Publicação — FANORA'"
    :description="$post->isExclusive() && ! $canView ? 'Publicação exclusiva — assine para desbloquear no FANORA.' : substr($post->body, 0, 160)"
    :canonical="route('posts.show', [$post->user->username, $post])"
    :robots="$post->isExclusive() ? 'noindex, nofollow' : 'index, follow'"
>
    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('creator.show', $post->user->username) }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-muted transition hover:text-white">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar ao perfil de {{ $post->user->name }}
        </a>
    </div>

    <section class="relative mb-8 overflow-hidden rounded-[2rem] border border-brand-border bg-brand-card">
        <div class="hero-gradient absolute inset-0 opacity-60"></div>
        <div class="glass relative rounded-[2rem] border-white/10 p-5 sm:p-6">
            <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:gap-6">
                <a href="{{ route('creator.show', $post->user->username) }}" class="shrink-0">
                    <x-avatar :path="$post->user->profile?->avatar_path" :name="$post->user->name" size="lg" :glow="true" />
                </a>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('creator.show', $post->user->username) }}" class="text-xl font-bold tracking-tight text-white transition hover:text-brand-magenta">
                            {{ $post->user->creatorProfile->display_name ?? $post->user->name }}
                        </a>
                        @if ($post->user->creatorProfile?->isApproved())
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-brand-magenta to-brand-purple text-[10px] font-bold text-white shadow-md shadow-brand-magenta/30" title="Verificado">✓</span>
                        @endif
                    </div>
                    <p class="mt-0.5 text-sm font-medium text-brand-muted">@<span class="text-brand-magenta">{{ $post->user->username }}</span></p>
                    @if (filled($post->user->creatorProfile?->tagline))
                        <p class="mt-1.5 line-clamp-1 text-xs text-white/60">“{{ $post->user->creatorProfile->tagline }}”</p>
                    @endif
                </div>

                <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                    @if (request()->user() && request()->user()->id !== $post->user->id)
                        <form method="POST" action="{{ route('creator.follow', $post->user->username) }}">
                            @csrf
                            <button class="btn-outline sm w-full">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                {{ isset($isFollowing) && $isFollowing ? 'Seguindo' : 'Seguir' }}
                            </button>
                        </form>
                    @endif

                    @if (isset($hasActiveSubscription) && ! $hasActiveSubscription && $post->user->creatorProfile?->subscription_price_cents)
                        <a href="{{ route('creator.show', $post->user->username) }}#subscribe" class="btn-primary sm flex w-full items-center justify-center shadow-glow-magenta">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Assinar — R$ {{ number_format($post->user->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}
                        </a>
                    @elseif (isset($hasActiveSubscription) && $hasActiveSubscription)
                        <div class="flex items-center justify-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-2.5 text-sm font-semibold text-emerald-400 shadow-lg shadow-emerald-500/10 sm:justify-start">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Assinante
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <article class="relative overflow-hidden rounded-[2rem] border border-brand-border bg-brand-card shadow-2xl shadow-black/30">
        <div class="relative">
            <header class="flex items-center gap-3 border-b border-white/5 p-5 sm:p-6">
                <a href="{{ route('creator.show', $post->user->username) }}" class="shrink-0">
                    <x-avatar :path="$post->user->profile?->avatar_path" :name="$post->user->name" size="md" />
                </a>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <a href="{{ route('creator.show', $post->user->username) }}" class="text-sm font-bold text-white transition hover:text-brand-magenta">
                            {{ $post->user->name }}
                        </a>
                        <svg class="h-4 w-4 shrink-0 text-brand-magenta" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                        <span class="text-xs text-brand-muted">·</span>
                        <time datetime="{{ $post->published_at?->toIso8601String() }}" class="text-xs font-medium text-brand-muted">{{ $post->published_at?->diffForHumans() }}</time>
                    </div>
                </div>

                @if ($post->isExclusive())
                    <x-badge color="magenta" class="shadow-md shadow-brand-magenta/20">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Exclusivo
                    </x-badge>
                @else
                    <x-badge>
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Público
                    </x-badge>
                @endif
            </header>

            @if ($canView || ! $post->isExclusive())
                <div class="px-5 pb-5 pt-6 sm:px-7 sm:pb-7">
                    <div class="whitespace-pre-line text-[16px] leading-relaxed text-white/95 sm:text-lg">
                        {{ $post->body }}
                    </div>
                </div>
            @endif

            @if ($canView)
                @foreach ($post->media as $media)
                    <div class="px-5 pb-5 sm:px-7 sm:pb-7">
                        <figure class="group relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-card to-brand-surface shadow-xl shadow-black/20">
                            @if ($media->isVideo())
                                <video class="w-full" controls preload="metadata" poster="{{ $mediaUrls[$media->id] ?? '' }}">
                                    <source src="{{ $mediaUrls[$media->id] ?? '' }}" type="{{ $media->mime_type }}">
                                </video>
                            @else
                                <img src="{{ $mediaUrls[$media->id] ?? '' }}" alt="" loading="lazy" class="w-full transition duration-500 ease-out group-hover:scale-[1.01]">
                            @endif
                        </figure>
                    </div>
                @endforeach
            @else
                @if ($post->media->isNotEmpty())
                    <div class="px-5 pb-5 sm:px-7 sm:pb-7">
                        <div class="relative overflow-hidden rounded-2xl border border-brand-border bg-brand-card">
                            <div class="aspect-video bg-gradient-to-br from-brand-magenta/20 via-brand-purple/10 to-brand-card flex items-center justify-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl border border-brand-magenta/30 bg-brand-magenta/10 backdrop-blur-sm">
                                        <svg class="h-10 w-10 text-brand-magenta/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-brand-muted">Mídia bloqueada</span>
                                </div>
                            </div>
                            <div class="absolute inset-0 backdrop-blur-[3px]"></div>
                        </div>
                    </div>
                @endif

                <div class="mx-5 mb-7 sm:mx-7">
                    <div class="relative overflow-hidden rounded-2xl border border-brand-magenta/40 bg-gradient-to-br from-brand-magenta/10 via-brand-card to-brand-purple/10 p-7 text-center shadow-xl shadow-brand-magenta/10">
                        <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-brand-magenta/20 blur-3xl"></div>
                        <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-brand-purple/20 blur-3xl"></div>
                        <div class="relative">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-magenta to-brand-purple shadow-xl shadow-brand-magenta/40">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h3 class="mt-5 text-xl font-black tracking-tight text-white sm:text-2xl">Conteúdo exclusivo para assinantes</h3>
                            <p class="mt-2 text-sm text-brand-muted sm:text-base">Desbloqueie este post completo, todas as mídias e o conteúdo exclusivo do creator assinando o plano mensal.</p>

                            @if ($post->user->creatorProfile?->subscription_price_cents)
                                <div class="mt-5 inline-flex items-baseline gap-1 rounded-xl border border-white/10 bg-white/5 px-4 py-2 backdrop-blur-sm">
                                    <span class="text-2xl font-black text-white">R$ {{ number_format($post->user->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}</span>
                                    <span class="text-sm font-medium text-brand-muted">/mês</span>
                                </div>
                            @endif

                            <a href="{{ route('creator.show', $post->user->username) }}#subscribe" class="btn-primary btn-lg mt-6 w-full shadow-xl shadow-brand-magenta/30 hover:shadow-glow-magenta sm:w-auto">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Assinar e desbloquear
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </article>

    @if ($post->isExclusive() && ! $canView)
        <div class="mt-10 relative overflow-hidden rounded-[2rem] border border-brand-magenta/40 shadow-2xl shadow-brand-magenta/10">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-magenta/15 via-brand-purple/10 to-brand-card"></div>
            <div class="hero-gradient absolute inset-0"></div>
            <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-brand-magenta/20 blur-[100px]"></div>
            <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-brand-purple/20 blur-[100px]"></div>

            <div class="glass relative rounded-[2rem] border-white/10 p-8 sm:p-10">
                <div class="grid items-center gap-10 lg:grid-cols-2">
                    <div class="text-center lg:text-left">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-magenta to-brand-purple shadow-xl shadow-brand-magenta/30 lg:mx-0">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h2 class="text-3xl font-black tracking-tight sm:text-4xl">
                            <span class="text-gradient-brand">Desbloqueie</span> agora
                        </h2>
                        <p class="mt-3 text-base text-brand-muted sm:text-lg">
                            Assine o plano de <strong class="text-white">{{ $post->user->name }}</strong> e tenha acesso completo a este post e a todas as publicações exclusivas.
                        </p>

                        <ul class="mt-6 space-y-3 text-sm text-white/80">
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Este post completo com todas as mídias
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Todo o arquivo de conteúdo exclusivo
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Cancele quando quiser
                            </li>
                        </ul>
                    </div>

                    <div class="relative">
                        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-1">
                            <div class="rounded-[22px] bg-gradient-to-br from-brand-card via-brand-card to-brand-surface p-7">
                                <div class="mb-6 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-brand-muted">Plano mensal</span>
                                    <x-badge color="magenta" class="shadow-glow-magenta">Acesso total</x-badge>
                                </div>

                                @if ($post->user->creatorProfile?->subscription_price_cents)
                                    <div class="mb-8">
                                        <div class="flex items-end gap-1">
                                            <span class="text-5xl font-black tracking-tight text-white">
                                                R$ {{ number_format($post->user->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}
                                            </span>
                                            <span class="mb-2 text-lg font-medium text-brand-muted">/mês</span>
                                        </div>
                                        <p class="mt-1 text-xs text-brand-muted">Cancele com um clique</p>
                                    </div>
                                @endif

                                <a href="{{ route('creator.show', $post->user->username) }}#subscribe" class="btn-primary btn-lg w-full shadow-glow-magenta">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    Assinar com cartão
                                </a>

                                <p class="mt-4 text-center text-xs text-brand-muted">
                                    🔒 Pagamento 100% seguro. Sem fidelidade.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-10 rounded-[2rem] border border-brand-border bg-brand-card p-6 sm:p-7 shadow-xl shadow-black/20">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-white">Reportar esta publicação</h2>
                <p class="mt-1 text-sm text-brand-muted">Algo não parece correto? Denuncie este conteúdo para a equipe.</p>
            </div>

            <div class="mt-2 sm:mt-0 sm:shrink-0">
                @if (request()->user())
                    <x-modal title="Denunciar publicação">
                        <x-slot:trigger>
                            <button type="button" class="btn-outline sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                Denunciar
                            </button>
                        </x-slot:trigger>

                        <form method="POST" action="{{ route('reports.store') }}">
                            @csrf
                            <input type="hidden" name="reportable_type" value="post">
                            <input type="hidden" name="reportable_id" value="{{ $post->id }}">

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
                            <x-textarea name="description" label="Detalles (opcional)" rows="3" />

                            <x-button type="submit" variant="danger" block>Enviar denúncia</x-button>
                        </form>
                    </x-modal>
                @else
                    <a href="{{ route('login') }}" class="btn-outline sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Entrar para denunciar
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
