<x-layouts.app
    :title="$post->user->name . ' — Publicação — FANORA'"
    :description="$post->isExclusive() && ! $canView ? 'Publicação exclusiva — assine para desbloquear no FANORA.' : substr($post->body, 0, 160)"
    :canonical="route('posts.show', [$post->user->username, $post])"
    :robots="$post->isExclusive() ? 'noindex, nofollow' : 'index, follow'"
>
    <div class="mb-6">
        <a href="{{ route('creator.show', $post->user->username) }}" class="text-sm text-brand-muted hover:text-white">← Voltar ao perfil</a>
    </div>

    <x-post-card
        :post="$post"
        :can-view="$canView"
        :media-urls="$mediaUrls"
    />

    @if ($post->isExclusive() && ! $canView)
        <div class="mt-8 rounded-3xl border border-brand-magenta/40 bg-brand-card p-8 text-center">
            <p class="text-3xl">🔒</p>
            <h2 class="mt-2 text-xl font-bold">Conteúdo exclusivo</h2>
            <p class="mt-2 text-brand-muted">Assine para desbloquear esta publicação completa.</p>
            <a href="{{ route('creator.show', $post->user->username) }}#subscribe" class="btn-primary btn-lg mt-6">Assinar</a>
        </div>
    @endif

    <div class="mt-8 rounded-3xl border border-brand-border bg-brand-card p-6">
        <h2 class="text-lg font-semibold">Reportar esta publicação</h2>
        <p class="mt-1 text-sm text-brand-muted">Algo não parece correto? Denuncia este contenido.</p>

        @if (request()->user())
            <x-modal title="Denunciar publicação">
                <x-slot:trigger>
                    <button type="button" class="btn-outline sm mt-4">Denunciar</button>
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
            <a href="{{ route('login') }}" class="btn-outline sm mt-4">Entrar para denunciar</a>
        @endif
    </div>
</x-layouts.app>