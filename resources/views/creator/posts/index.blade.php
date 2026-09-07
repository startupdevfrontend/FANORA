<x-layouts.dashboard title="Publicações — FANORA" :active="'posts'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Suas publicações</x-page-title>
        <a href="{{ route('creator.posts.create') }}" class="btn-primary sm">+ Nova publicação</a>
    </div>

    @php
        $items = $posts->getCollection();
    @endphp

    @if ($items->isEmpty())
        <x-empty-state
            title="Sem publicações ainda"
            description="Publique seu primeiro conteúdo para começar a ganhar."
        >
            <a href="{{ route('creator.posts.create') }}" class="btn-primary sm">Criar publicação</a>
        </x-empty-state>
    @else
        <div class="space-y-4">
            @foreach ($items as $post)
                <div class="card p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="line-clamp-2 text-sm text-white/90">{{ str($post->body)->limit(120) }}</p>
                            <div class="mt-2 flex items-center gap-2.5">
                                <x-badge :color="$post->isExclusive() ? 'magenta' : 'neutral'">{{ $post->isExclusive() ? 'Exclusivo' : 'Público' }}</x-badge>
                                <span class="text-xs text-brand-muted">{{ $post->published_at?->format('d/m/Y') }}</span>
                                @if ($post->media->isNotEmpty())
                                    <span class="text-xs text-brand-muted">{{ $post->media->count() }} mídias</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('creator.posts.edit', $post) }}" class="btn-outline sm">Editar</a>

                            <form method="POST" action="{{ route('creator.posts.destroy', $post) }}" onsubmit="return confirm('Eliminar esta publicación? No se podrá deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger sm">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-pagination :paginator="$posts" />
    @endif
</x-layouts.dashboard>