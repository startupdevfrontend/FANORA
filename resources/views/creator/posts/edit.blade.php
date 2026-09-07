<x-layouts.dashboard title="Editar publicação — FANORA" :active="'posts'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Editar publicação</x-page-title>
        <a href="{{ route('creator.posts.index') }}" class="btn-ghost sm">Voltar</a>
    </div>

    <form method="POST" action="{{ route('creator.posts.update', $post) }}" enctype="multipart/form-data" class="card p-6">
        @method('PUT')
        @csrf

        <x-textarea name="body" rows="6" label="Texto da publicação" required :value="$post->body" />

        <x-select
            name="visibility"
            label="Visibilidade"
            :options="[
                ['value' => 'public', 'label' => 'Público'],
                ['value' => 'subscribers_only', 'label' => 'Exclusivo para assinantes'],
            ]"
            :value="$post->visibility->value ?? $post->visibility"
            required
        />

        @php($mediaItems = $post->media)

        @if ($mediaItems->isNotEmpty())
            <div class="mt-4 space-y-2">
                <p class="text-sm font-medium">Mídia anexada</p>
                <ul class="grid grid-cols-3 gap-3 sm:grid-cols-4">
                    @foreach ($mediaItems as $media)
                        <li class="group relative aspect-video">
                            <div class="h-full w-full overflow-hidden rounded-xl border border-brand-border bg-brand-surface">
                                <span class="block h-full w-full text-xs text-brand-muted">
                                    {{ $media->media_type === 'video' ? '🎬 Vídeo' : '🖼 Imagem' }}
                                </span>
                            </div>
                            <form method="POST" action="{{ route('creator.posts.media.destroy', [$post, $media]) }}"
                                  onsubmit="return confirm('Eliminar esta mídia?');"
                                  class="absolute inset-0 grid place-items-center rounded-xl bg-brand-black/60 opacity-0 transition group-hover:opacity-100">
                                @csrf
                                @method('DELETE')
                                <span class="text-xs font-medium text-white">Eliminar</span>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-input name="media" type="file" label="Adicionar mais mídia" hint="PNG/JPG/WEBP até 10 MB, MP4/Webm/MOV até 256 MB." multiple />

        <div class="mt-6 flex items-center gap-3">
            <x-button type="submit" variant="primary">Guardar cambios</x-button>
            <a href="{{ route('creator.posts.index') }}" class="btn-ghost sm">Cancelar</a>
        </div>
    </form>
</x-layouts.dashboard>