@props([
    'post' => null,
    'canView' => false,
    'mediaUrls' => [],
    'closable' => false,
])

<article class="post-card">
    <header class="flex items-center gap-3">
        <a href="{{ route('creator.show', $post->user->username) }}">
            <x-avatar :path="$post->user->profile?->avatar_path" :name="$post->user->name" size="sm" />
        </a>

        <div class="min-w-0 flex-1">
            <a href="{{ route('creator.show', $post->user->username) }}" class="text-sm font-semibold text-white hover:text-brand-magenta">
                {{ $post->user->name }}
            </a>
            <time datetime="{{ $post->published_at?->toIso8601String() }}" class="block text-xs text-brand-muted">{{ $post->published_at?->diffForHumans() }}</time>
        </div>

        @if ($post->isExclusive())
            <x-badge color="magenta">Exclusivo</x-badge>
        @endif
    </header>

    @if ($canView || ! $post->isExclusive())
        <div class="mt-3 whitespace-pre-line text-[15px] leading-relaxed text-white/90">{{ $post->body }}</div>
    @endif

    @if ($canView)
        @foreach ($post->media as $media)
            <figure class="mt-4 overflow-hidden rounded-2xl border border-brand-border bg-brand-card">
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
            <div class="mt-4 overflow-hidden rounded-2xl border border-brand-border bg-brand-card">
                <div class="aspect-video bg-brand-card"></div>
            </div>
        @endif

        <div class="mt-4 rounded-2xl border border-brand-magenta/40 bg-brand-card p-5 text-center">
            <p class="text-2xl">🔒</p>
            <h3 class="mt-1 text-lg font-semibold text-white">Conteúdo exclusivo</h3>
            <p class="mt-1 text-sm text-brand-muted">Assine para desbloquear este conteúdo.</p>
            <a href="{{ route('creator.show', $post->user->username) }}#subscribe" class="btn-primary mt-4 w-full">Assinar</a>
        </div>
    @endif
</article>