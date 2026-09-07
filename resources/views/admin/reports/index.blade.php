<x-layouts.dashboard title="Denúncias — FANORA" admin="true" :active="'reports'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Denúncias</x-page-title>
    </div>

    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        @php($status = request()->query('status'))
        <x-badge color="neutral">Total: {{ $counts['pending'] + $counts['reviewing'] + $counts['resolved'] + $counts['rejected'] }}</x-badge>
        <a href="{{ route('admin.reports.index') }}" class="pill {{ blank($status) ? 'active-pill' : '' }}">Todas</a>
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="pill {{ $status == 'pending' ? 'active-pill' : '' }}">Pendentes ({{ $counts['pending'] }})</a>
        <a href="{{ route('admin.reports.index', ['status' => 'reviewing']) }}" class="pill {{ $status == 'reviewing' ? 'active-pill' : '' }}">En análise ({{ $counts['reviewing'] }})</a>
        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" class="pill {{ $status == 'resolved' ? 'active-pill' : '' }}">Resolvidas ({{ $counts['resolved'] }})</a>
        <a href="{{ route('admin.reports.index', ['status' => 'rejected']) }}" class="pill {{ $status == 'rejected' ? 'active-pill' : '' }}">Rechazadas ({{ $counts['rejected'] }})</a>
    </div>

    @php($items = $reports->getCollection())

    <div class="card p-5">
        @if ($items->isEmpty())
            <x-empty-state title="Sin denúncias" />
        @else
            <div class="overflow-x-auto">
                <table class="table-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Contenido reportado</th>
                            <th>Motivo</th>
                            <th class="hidden md:table-cell">Reporte por</th>
                            <th>Estado</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td class="text-sm text-brand-muted">{{ str(strtolower(class_basename($report->reportable_type)))->ucfirst() }} #{{ $report->reportable_id }}</td>
                                <td>{{ $report->reason->label() }}</td>
                                <td class="hidden md:table-cell">{{ $report->reporter?->username ?? '—' }}</td>
                                <td><x-badge :color="match ($report->status) { 'pending' => 'yellow', 'reviewing' => 'neutral', 'resolved' => 'green', 'rejected' => 'red', default => 'neutral' }">{{ $report->status->label() }}</x-badge></td>
                                <td class="text-right"><a href="{{ route('admin.reports.show', $report) }}" class="btn-ghost sm">Revisar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$reports" />
        @endif
    </div>
</x-layouts.dashboard>