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

    <section class="overflow-hidden rounded-3xl border border-brand-border bg-brand-card">
        <div class="aspect-[3/1] w-full bg-gradient-to-r from-brand-surface via-brand-purple/20 to-brand-magenta/20">
            @if ($creator->profile?->cover_path)
                <img src="{{ app(\App\Services\MediaService::class)->avatarUrl($creator->profile->cover_path) }}" alt="" class="h-full w-full object-cover">
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-5 px-6 py-6">
            <x-avatar :path="$creator->profile?->avatar_path" :name="$creator->name" size="xl" />

            <div class="min-w-0">
                <h1 class="flex items-center gap-2 text-2xl font-black tracking-tight">
                    {{ $creator->creatorProfile->display_name ?? $creator->name }}
                    @if ($creator->creatorProfile?->isApproved())
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-purple/20 text-xs text-violet-200" title="Verificado">✓</span>
                    @endif
                </h1>
                <p class="text-base text-brand-muted">{{ $creator->username }}</p>

                @if (filled($creator->creatorProfile?->tagline))
                    <p class="mt-1 text-sm text-white/80">“{{ $creator->creatorProfile->tagline }}”</p>
                @endif
            </div>

            <div class="ml-auto flex flex-col gap-2 sm:flex-row sm:items-center">
                @if (request()->user())
                    <form method="POST" action="{{ route('creator.follow', $creator->username) }}">
                        @csrf
                        <button class="btn-outline sm w-full">
                            {{ $isFollowing ? 'Seguindo ✓' : '+ Seguir' }}
                        </button>
                    </form>
                @endif

                @if (! $hasActiveSubscription)
                    <a href="#subscribe" class="btn-primary sm">Assinar
                        @if ($creator->creatorProfile?->subscription_price_cents)
                            — R$ {{ number_format($creator->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}/mês
                        @endif
                    </a>
                @else
                    <x-badge color="green">Assinante ✓</x-badge>
                @endif
            </div>
        </div>

        <div class="border-t border-brand-border px-6 py-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-brand-muted"><strong class="text-white">{{ $creator->creatorProfile->subscriber_count }}</strong> seguidores</span>
                <span aria-hidden="true">•</span>
                @foreach ($creator->creatorProfile->categories as $category)
                    <x-badge>{{ $category->name }}</x-badge>
                @endforeach

                @if (request()->user() && request()->user()->id !== $creator->id)
                    <x-modal title="Denunciar">
                        <x-slot:trigger>
                            <button type="button" class="text-xs text-brand-muted underline hover:text-danger">Denunciar</button>
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
                            <button type="submit" class="text-xs text-brand-muted underline hover:text-danger">Bloquear</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </section>

    @if (! $hasActiveSubscription && $creator->creatorProfile?->subscription_price_cents)
        <section id="subscribe" class="mt-6 rounded-3xl border border-brand-magenta/40 bg-brand-card p-8 text-center">
            <p class="text-3xl">🔒</p>
            <h2 class="mt-2 text-2xl font-bold">Desbloquea el contenido exclusivo</h2>
            <p class="mt-2 text-brand-muted">Assine para acessar publicações exclusivas de {{ $creator->name }}.</p>

            @if (request()->user())
                <form method="POST" action="{{ route('subscriptions.store', $creator->username) }}">
                    @csrf
                    <button class="btn-primary btn-lg mt-6 w-full sm:w-auto">
                        Assinar — R$ {{ number_format($creator->creatorProfile->subscription_price_cents / 100, 2, ',', '.') }}/mês
                    </button>
                </form>
                <p class="mt-3 text-xs text-brand-muted">Cancelável quando você quiera. Renovação mensal automática.</p>
            @else
                <a href="{{ route('login') }}" class="btn-primary btn-lg mt-6">Entrar para assinar</a>
            @endif
        </section>
    @endif

    <section class="mt-10">
        <h2 class="text-xl font-bold">Publicações</h2>

@php
        $posts = $visiblePosts->getCollection();
    @endphp

    @if ($posts->isEmpty())
            <x-empty-state title="Sin publicaciones todavía" description="Este creator ainda não publicou nada." />
        @else
            <div class="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @php
                        $canViewThis = $post->visibility === 'public' || $hasActiveSubscription;
                    @endphp
                    <article class="card overflow-hidden">
                        <a href="{{ route('posts.show', [$creator->username, $post]) }}" class="block">
                            @if ($canViewThis && $post->media->isNotEmpty())
                                @php($firstMedia = $post->media->first())
                                <div class="aspect-video bg-brand-card">
                                    <img src="{{ $mediaUrls[$firstMedia->id] ?? '' }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="aspect-video bg-brand-card">
                                    <div class="grid h-full w-full place-items-center">
                                        @if ($post->isExclusive())
                                            <span class="text-3xl">🔒</span>
                                        @else
                                            <span class="text-sm text-brand-muted">Sem imagem</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </a>

                        <div class="p-4">
                            @if ($post->isExclusive())
                                <x-badge color="magenta">Exclusivo</x-badge>
                            @endif

                            <h3 class="mt-1 line-clamp-2 text-sm text-white/90">{{ $post->body }}</h3>
                            <time datetime="{{ $post->published_at?->toIso8601String() }}" class="mt-2 block text-xs text-brand-muted">{{ $post->published_at?->diffForHumans() }}</time>

                            @if (! $canViewThis)
                                <a href="#subscribe" class="mt-2 block text-xs font-semibold text-brand-magenta">Assine para desbloquear →</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <x-pagination :paginator="$visiblePosts" />
        @endif
    </section>
</x-layouts.app>