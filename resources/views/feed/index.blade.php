<x-layouts.app
    title="Feed — FANORA"
    description="Novidades dos creators que você segue."
>

    <section class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-brand-500/8 via-purple-500/8 to-pink-500/8 p-6 sm:p-8 backdrop-blur-sm border border-white/10">
        <div class="absolute -top-20 -right-20 h-56 w-56 rounded-full bg-brand-500/15 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 h-56 w-56 rounded-full bg-purple-500/15 blur-3xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div>
                <x-page-title class="!mb-0">Feed</x-page-title>
                <p class="mt-2 text-sm sm:text-base text-brand-muted">
                    Novidades e publicações recentes dos creators que você segue.
                </p>
            </div>

            @if (auth()->user()->isCreator())
                <a href="{{ route('creator.posts.create') }}" class="btn-primary sm inline-flex items-center justify-center gap-2 py-2.5 px-5 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nova publicação
                </a>
            @endif
        </div>
    </section>

    @php
        $items = $posts->getCollection();
    @endphp

    @if ($items->isEmpty())
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-12 backdrop-blur-sm">
            <div class="absolute top-0 right-0 h-40 w-40 rounded-full bg-brand-500/10 blur-3xl"></div>
            <div class="relative">
                <div class="flex justify-center mb-4">
                    <span class="text-6xl">📭</span>
                </div>
                <x-empty-state
                    title="Seu feed está vazio"
                    description="Siga creators para ver suas publicações aqui."
                >
                    <a href="{{ route('explore') }}" class="btn-primary sm">Explorar creators</a>
                </x-empty-state>
            </div>
        </div>
    @else
        <div class="relative">
            <div class="absolute -top-6 right-10 h-44 w-44 rounded-full bg-purple-500/10 blur-3xl pointer-events-none"></div>

            <div class="relative space-y-6">
                @foreach ($items as $post)
                    @php
                        $item = $access[$post->id] ?? ['can_view' => false, 'media_urls' => []];
                    @endphp

                    <div class="group relative rounded-2xl shadow-xl shadow-black/10 hover:shadow-2xl hover:shadow-brand-500/5 transition-all duration-500 hover:-translate-y-0.5">
                        <div class="absolute -inset-0.5 rounded-2xl bg-gradient-to-r from-brand-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-brand-500/10 group-hover:via-purple-500/10 group-hover:to-pink-500/10 transition-all duration-500 blur opacity-0 group-hover:opacity-100 -z-10"></div>
                        <x-post-card
                            :post="$post"
                            :can-view="$item['can_view']"
                            :media-urls="$item['media_urls']"
                        />
                    </div>
                @endforeach
            </div>
        </div>

        <x-pagination :paginator="$posts" />
    @endif
</x-layouts.app>
