@props(['type' => 'success'])

@php
    $classes = match ($type) {
        'success' => 'border-emerald-500/30 bg-emerald-950/10 text-emerald-200',
        'error' => 'border-red-500/30 bg-red-950/10 text-red-200',
        'warning' => 'border-amber-500/30 bg-amber-950/10 text-amber-200',
        'info' => 'border-sky-500/30 bg-sky-950/10 text-sky-200',
        default => 'border-brand-border bg-brand-card text-brand-muted',
    };
@endphp

<div role="alert" class="rounded-xl border px-4 py-3 text-sm {{ $classes }}">
    {{ $slot }}
</div>