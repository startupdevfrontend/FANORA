<x-layouts.dashboard title="{{ $verification->user->name }} — Verificación — FANORA" admin="true" :active="'creators'">

    <div class="mb-6">
        <a href="{{ route('admin.creators') }}" class="text-sm text-brand-muted hover:text-white">← Verificações</a>
        <x-page-title class="mt-2">{{ $verification->user->name }}</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Solicitação #{{ $verification->id }} — {{ $verification->submitted_at?->format('d/m/Y') }}</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card p-5 lg:col-span-3">
            <h2 class="text-lg font-semibold">Estado da solicitação</h2>

            <div class="mt-2 flex items-center gap-2.5">
                <x-badge :color="match ($verification->status) { 'pending' => 'yellow', 'approved' => 'green', 'rejected' => 'red', default => 'neutral' }">{{ $verification->status->label() }}</x-badge>

                @if ($verification->rejected_reason)
                    <p class="text-sm text-red-300">Motivo do rechazo: {{ $verification->rejected_reason }}</p>
                @endif
            </div>

            @if ($verification->notes)
                <p class="mt-2 text-sm text-brand-muted">{{ $verification->notes }}</p>
            @endif
        </div>

        <div class="card p-5">
            <h2 class="text-lg font-semibold">Documento</h2>

            @if ($verification->document_path)
                <a href="{{ route('admin.creators.document', $verification) }}" class="btn-outline sm w-full" target="_blank">Descargar documento</a>
                <p class="mt-2 text-xs text-brand-muted">Tipo: {{ $verification->document_type }}</p>
            @else
                <p class="text-sm text-brand-muted">Sin documento anexo.</p>
            @endif
        </div>

        <div class="card p-5 lg:col-span-2">
            <h2 class="text-lg font-semibold">Creator</h2>

            <dl class="mt-3 grid grid-cols-2 gap-y-2 text-sm">
                <dt class="text-brand-muted">Nome</dt>
                <dd>{{ $verification->user->name }}</dd>
                <dt class="text-brand-muted">Usuario</dt>
                <dd>{{ $verification->user->username }}</dd>
                <dt class="text-brand-muted">E-mail</dt>
                <dd>{{ $verification->user->email }}</dd>
                <dt class="text-brand-muted">Categoria(s)</dt>
                <dd>{{ $verification->user->creatorProfile?->categories->pluck('name')->join(', ') ?: '—' }}</dd>
            </dl>

            @php($profile = $verification->user->creatorProfile)

            <form method="POST" action="{{ route('admin.creators.feature', $profile) }}" class="mt-5">
                @csrf
                <button class="btn-ghost sm">
                    {{ $profile?->is_featured ? 'Quitar de destaque' : 'Destacar en home' }}
                </button>
            </form>
        </div>

        <div class="card p-5 lg:col-span-3">
            <h2 class="text-lg font-semibold">Acciones de moderación</h2>

            @if (in_array($verification->status, ['pending', 'rejected']))
                <form method="POST" action="{{ route('admin.creators.approve', $verification) }}" class="mt-3" onsubmit="return confirm('¿Aprovar esta verificación? O creator podrá monetizar.');">
                    @csrf
                    <x-button variant="primary">Aprovar e habilitar monetização</x-button>
                </form>

                <form method="POST" action="{{ route('admin.creators.reject', $verification) }}" class="mt-3" onsubmit="return confirm('¿Rechazar esta verificación?');">
                    @csrf

                    <x-textarea name="reason" rows="3" label="Motivo do rechazo" placeholder="Explica por qué se rechaza…" required />

                    <x-button type="submit" variant="danger">Rechazar solicitação</x-button>
                </form>
            @else
                <p class="text-sm text-brand-muted">Esta verificação está {{ $verification->status->label() }} e já não pode ser alterada.</p>
            @endif
        </div>
    </div>
</x-layouts.dashboard>