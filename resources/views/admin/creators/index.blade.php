<x-layouts.dashboard title="Creators — FANORA" admin="true" :active="'creators'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Verificação de creators</x-page-title>
    </div>

    <div class="mb-4 flex flex-wrap gap-2">
        @php($status = request()->query('status'))
        <a href="{{ route('admin.creators') }}" class="pill {{ blank($status) ? 'active-pill' : '' }}">Todas</a>
        <a href="{{ route('admin.creators', ['status' => 'pending']) }}" class="pill {{ $status == 'pending' ? 'active-pill' : '' }}">Pendentes</a>
        <a href="{{ route('admin.creators', ['status' => 'approved']) }}" class="pill {{ $status == 'approved' ? 'active-pill' : '' }}">Aprovadas</a>
        <a href="{{ route('admin.creators', ['status' => 'rejected']) }}" class="pill {{ $status == 'rejected' ? 'active-pill' : '' }}">Rejeitadas</a>
    </div>

    @php($items = $verifications->getCollection())

    <div class="card p-5">
        @if ($items->isEmpty())
            <x-empty-state title="Sin solicitudes" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th class="hidden sm:table-cell">Documento</th>
                            <th class="hidden sm:table-cell">Solicitado el</th>
                            <th class="text-right">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $verification)
                            <tr>
                                <td>{{ $verification->id }}</td>
                                <td class="flex items-center gap-3">
                                    <x-avatar :path="$verification->user->profile?->avatar_path" :name="$verification->user->name" size="sm" />
                                    <div>
                                        <span class="block text-sm font-medium text-white">{{ $verification->user->name }}</span>
                                        <span class="text-xs text-brand-muted">{{ $verification->user->username }}</span>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell text-sm text-brand-muted">{{ $verification->document_type ?: '—' }}</td>
                                <td class="hidden sm:table-cell text-sm text-brand-muted">{{ $verification->submitted_at?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-right"><x-badge :color="match ($verification->status) { 'pending' => 'yellow', 'approved' => 'green', 'rejected' => 'red', default => 'neutral' }">{{ $verification->status->label() }}</x-badge></td>
                                <td class="text-center"><a href="{{ route('admin.creators.show', $verification) }}" class="btn-ghost sm">Revisar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$verifications" />
        @endif
    </div>
</x-layouts.dashboard>