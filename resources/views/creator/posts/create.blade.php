<x-layouts.dashboard title="Nueva publicación — FANORA" :active="'posts'">

    <div class="mb-6">
        <x-page-title>Nova publicação</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Compartilha texto, imágenes ou videos. O contenido exclusivo só aparece para tus assinantes.</p>
    </div>

    <form method="POST" action="{{ route('creator.posts.store') }}" enctype="multipart/form-data" class="card p-6">
        @csrf

        <x-textarea name="body" rows="6" label="Texto da publicação" hint="Cuéntale a la gente algo." required />

        <x-select
            name="visibility"
            label="Visibilidade"
            :options="[
                ['value' => 'public', 'label' => 'Público — visível para todos'],
                ['value' => 'subscribers_only', 'label' => 'Exclusivo — assinantes sólo'],
            ]"
            placeholder="Seleccionar visibilidade"
            required
        />

        <x-input name="media" type="file" label="Mídia (imágenes ou vídeos)" hint="PNG/JPG/WEBP até 10 MB, MP4/WebM/MOV até 256 MB." multiple />

        <x-alert type="warning">
            ¡Importante! Os vídeos não são convertidos na MVP; usa imagens ou GIFs para pré-visualização.
        </x-alert>

        <div class="mt-6 flex items-center gap-3">
            <x-button type="submit" variant="primary">Publicar</x-button>
            <a href="{{ route('creator.posts.index') }}" class="btn-ghost sm">Cancelar</a>
        </div>
    </form>
</x-layouts.dashboard>