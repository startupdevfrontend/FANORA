<x-layouts.dashboard title="Publicações — FANORA" admin="true" :active="'posts'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Publicações</x-page-title>
    </div>

    <div class="mb-4 flex flex-wrap gap-2">
        @php($status = request()->query('status'))
        <a href="{{ route('admin.posts') }}" class="pill {{ blank($status) ? 'active-pill' : '' }}">Todas</a>
        <a href="{{ route('admin.posts', ['status' => 'published']) }}" class="pill {{ $status == 'published' ? 'active-pill' : '' }}">Publicadas</a>
        <a href="{{ route('admin.posts', ['status' => 'hidden']) }}" class="pill {{ $status == 'hidden' ? 'active-pill' : '' }}">Ocultas</a>
    </div>

    @php($items = $posts->getCollection())

    <div class="card p-5">
        @if ($items->isEmpty())
            <x-empty-state title="Sin publicaciones" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Autor</th>
                            <th class="hidden lg:table-cell">Conteúdo</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->user->username }}</td>
                                <td class="hidden max-w-xs lg:table-cell">{{ str($post->body)->limit(80) }}</td>
                                <td><x-badge :color="$post->isExclusive() ? 'magenta' : 'neutral'">{{ $post->isExclusive() ? 'Exclusivo' : 'Público' }}</x-badge></td>
                                <td><x-badge :color="$post->status === 'published' ? 'green' : 'red'">{{ $post->status === 'published' ? 'Publicada' : 'Oculta' }}</x-badge></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('posts.show', [$post->user->username, $post]) }}" class="btn-ghost sm">Ver</a>
                                        <form method="POST" action="{{ route('admin.posts.toggle', $post) }}" class="inline">
                                            @csrf
                                            <button class="btn-ghost sm">{{ $post->status === 'published' ? 'Ocultar' : 'Mostrar' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Eliminar esta publicação de forma permanente?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-danger sm">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$posts" />
        @endif
    </div>
</x-layouts.dashboard>