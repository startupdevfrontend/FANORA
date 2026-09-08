@props([
    'post' => null,
    'canView' => false,
    'mediaUrls' => [],
    'closable' => false,
])

<article class="post-card rounded-2xl shadow-md shadow-black/20 transition-all duration-300 hover:shadow-lg hover:shadow-black/30">
    <header class="flex items-center gap-3 p-1">
        <a href="{{ route('creator.show', $post->user->username) }}">
            <x-avatar :path="$post->user->profile?->avatar_path" :name="$post->user->name" size="sm" />
        </a>

        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-1.5">
                <a href="{{ route('creator.show', $post->user->username) }}" class="text-sm font-semibold text-white hover:text-brand-magenta">
                    {{ $post->user->name }}
                </a>
                <svg class="h-4 w-4 text-brand-magenta" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
            </div>
            <time datetime="{{ $post->published_at?->toIso8601String() }}" class="block text-xs text-brand-muted">{{ $post->published_at?->diffForHumans() }}</time>
        </div>

        @if ($post->isExclusive())
            <x-badge color="magenta">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Exclusivo
            </x-badge>
        @endif
    </header>

    @if ($canView || ! $post->isExclusive())
        <div class="mt-3 whitespace-pre-line text-[15px] leading-relaxed text-white/90">{{ $post->body }}</div>
    @endif

    @if ($canView)
        @foreach ($post->media as $media)
            <figure class="mt-4 overflow-hidden rounded-2xl border border-brand-border bg-brand-card shadow-inner">
                @if ($media->isVideo())
                    <video class="w-full" controls preload="metadata" poster="{{ $mediaUrls[$media->id] ?? '' }}">
                        <source src="{{ $mediaUrls[$media->id] ?? '' }}" type="{{ $media->mime_type }}">
                    </video>
                @else
                    <img src="{{ $mediaUrls[$media->id] ?? '' }}" alt="" loading="lazy" class="w-full">
                @endif
            </figure>
        @endforeach
    @else
        @if ($post->media->isNotEmpty())
            <div class="mt-4 overflow-hidden rounded-2xl border border-brand-border bg-brand-card relative">
                <div class="aspect-video bg-gradient-to-br from-brand-magenta/20 via-brand-purple/10 to-brand-card flex items-center justify-center">
                    <svg class="h-20 w-20 text-brand-magenta/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="absolute inset-0 backdrop-blur-[2px]"></div>
            </div>
        @endif

        <div class="mt-4 relative overflow-hidden rounded-2xl border border-brand-magenta/40 bg-gradient-to-br from-brand-magenta/10 via-brand-card to-brand-purple/10 p-6 text-center">
            <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-brand-magenta/20 blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-brand-purple/20 blur-3xl"></div>
            <div class="relative">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-brand-magenta to-brand-purple shadow-lg shadow-brand-magenta/30">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="mt-3 text-lg font-bold text-white">Conteúdo exclusivo para assinantes</h3>
                <p class="mt-2 text-sm text-brand-muted">Desbloqueie este post e todo o conteúdo exclusivo assinando o plano.</p>
                <a href="{{ route('creator.show', $post->user->username) }}#subscribe" class="btn-primary mt-5 w-full shadow-lg shadow-brand-magenta/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Assinar agora
                </a>
            </div>
        </div>
    @endif
</article>