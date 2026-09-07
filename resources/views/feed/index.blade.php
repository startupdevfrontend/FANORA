<x-layouts.app
    title="Feed — FANORA"
    description="Novidades dos creators que você segue."
>

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Feed</x-page-title>

        @if (auth()->user()->isCreator())
            <a href="{{ route('creator.posts.create') }}" class="btn-primary sm">+ Nova publicação</a>
        @endif
    </div>

    @php
        $items = $posts->getCollection();
    @endphp

    @if ($items->isEmpty())
        <x-empty-state
            title="Seu feed está vazio"
            description="Siga creators para ver suas publicações aqui."
        >
            <a href="{{ route('explore') }}" class="btn-primary sm">Explorar creators</a>
        </x-empty-state>
    @else
        <div class="space-y-5">
            @foreach ($items as $post)
                @php
                    $item = $access[$post->id] ?? ['can_view' => false, 'media_urls' => []];
                @endphp

                <x-post-card
                    :post="$post"
                    :can-view="$item['can_view']"
                    :media-urls="$item['media_urls']"
                />
            @endforeach
        </div>

        <x-pagination :paginator="$posts" />
    @endif
</x-layouts.app>