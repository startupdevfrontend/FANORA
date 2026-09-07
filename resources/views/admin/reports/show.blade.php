<x-layouts.dashboard title="Denúncia #{{ $report->id }} — FANORA" admin="true" :active="'reports'">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-brand-muted hover:text-white">← Denúncias</a>
            <x-page-title class="mt-1">Denúncia #{{ $report->id }}</x-page-title>
        </div>
        <x-badge :color="match ($report->status) { 'pending' => 'yellow', 'reviewing' => 'neutral', 'resolved' => 'green', 'rejected' => 'red', default => 'neutral' }">{{ $report->status->label() }}</x-badge>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card p-5 lg:col-span-2">
            <h2 class="text-lg font-semibold">Detalhes da denúncia</h2>

            <dl class="mt-3 grid grid-cols-2 gap-y-2 text-sm">
                <dt class="text-brand-muted">Conteúdo reportado</dt>
                <dd>{{ str(class_basename($report->reportable_type))->headline() }} #{{ $report->reportable_id }}</dd>

                <dt class="text-brand-muted">Motivo</dt>
                <dd>{{ $report->reason->label() }}</dd>

                <dt class="text-brand-muted">Reporte por</dt>
                <dd>{{ $report->reporter?->name ?? '—' }} ({{ $report->reporter?->username ?? '—' }})</dd>

                <dt class="text-brand-muted">Enviado el</dt>
                <dd>{{ $report->created_at->format('d/m/Y H:i') }}</dd>
            </dl>

            @if ($report->description)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-brand-muted">Descrição</h3>
                    <p class="mt-1 text-sm text-brand-muted">{{ $report->description }}</p>
                </div>
            @endif

            @if (isset($report->reportable->body))
                <div class="mt-4 rounded-xl border border-brand-border bg-brand-surface p-3 text-sm text-brand-muted">
                    {{ str($report->reportable->body)->limit(200) }}
                </div>
            @endif

            @php($profileLink = null)
            @if ($report->reportable_type === 'post')
                @php($profileLink = $report->reportable->user?->username)
            @elseif ($report->reportable_type === 'user')
                @php($profileLink = $report->reportable->username)
            @elseif ($report->reportable_type === 'creator')
                @php($profileLink = $report->reportable->user?->username)
            @endif

            @if ($profileLink)
                <a href="{{ route('creator.show', $profileLink) }}" class="mt-4 inline-block text-sm text-brand-magenta underline">Ver perfil do contenido →</a>
            @endif
        </div>

        <div class="card p-5">
            <h2 class="text-lg font-semibold">Análise da denúncia</h2>

            <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="mt-3 space-y-3">
                @method('PUT')
                @csrf

                <x-select
                    name="status"
                    label="Novo estado"
                    :options="[
                        ['value' => 'pending', 'label' => 'Pendente'],
                        ['value' => 'reviewing', 'label' => 'Em análise'],
                        ['value' => 'resolved', 'label' => 'Resolvida'],
                        ['value' => 'rejected', 'label' => 'Rechazada'],
                    ]"
                    :value="$report->status->value"
                    required
                />

                <x-textarea name="moderator_note" rows="3" label="Observación (visíbil para o denunciante)" placeholder="Detalhes do que foi feito…" :value="$report->moderator_note" />

                <div class="pt-2">
                    <x-button type="submit" variant="primary" block>Actualizar denúncia</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>