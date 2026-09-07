<x-layouts.dashboard title="Categorias — FANORA" admin="true" :active="'categories'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Categorias</x-page-title>
    </div>

    <div class="card p-5">
        <h2 class="text-lg font-semibold">Nova categoria</h2>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-3 grid gap-3 sm:grid-cols-2">
            @csrf

            <x-input name="name" label="Nome" placeholder="Ej. Lifestyle" required />
            <x-input name="description" label="Descripción (opcional)" :value="''" />
            <div class="flex items-end">
                <x-button type="submit" variant="primary" block>Criar categoria</x-button>
            </div>
        </form>
    </div>

    @php($items = $categories->getCollection())

    <div class="card p-5 mt-6">
        @if ($items->isEmpty())
            <x-empty-state title="Sin categorias" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Slug</th>
                            <th class="hidden sm:table-cell">Descripción</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Creators</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $category)
                            <tr>
                                <td class="align-top">{{ $category->id }}</td>
                                <td class="align-top">
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex flex-col gap-1">
                                        @csrf
                                        @method('PUT')

                                        <div class="flex items-center gap-2">
                                            <div class="w-64">
                                                <x-input name="name" label="" placeholder="Nome" :value="$category->name" inputClass="input-base" />
                                            </div>
                                            <button class="btn-ghost sm mt-5">Guardar</button>
                                        </div>

                                        <div class="w-64">
                                            <x-input name="description" label="" placeholder="Descripción (opcional)" :value="$category->description" inputClass="input-base" />
                                        </div>

                                        <label class="mt-1 flex items-center gap-2 text-sm">
                                            <input type="checkbox" name="is_active" value="1" @checked($category->is_active) class="h-4 w-4 accent-brand-magenta">
                                            Activo
                                        </label>
                                    </form>
                                </td>
                                <td class="hidden align-top text-sm text-brand-muted sm:table-cell">{{ $category->slug }}</td>
                                <td class="hidden align-top sm:table-cell">
                                    <span class="text-sm text-brand-muted">{{ str($category->description)->limit(40) ?: '—' }}</span>
                                </td>
                                <td class="align-top text-center">
                                    <x-badge :color="$category->is_active ? 'green' : 'red'">{{ $category->is_active ? 'Activo' : 'Desactivado' }}</x-badge>
                                </td>
                                <td class="align-top text-center">{{ $category->creators_count ?? $category->creators?->count() ?? 0 }}</td>
                                <td class="align-top text-right">
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Eliminar esta categoria?');" class="flex justify-end">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$categories" />
        @endif
    </div>
</x-layouts.dashboard>