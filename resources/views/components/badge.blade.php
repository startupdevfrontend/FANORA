@props(['label' => null, 'color' => 'neutral'])

@php
    $classes = match ($color) {
        'green' => 'bg-emerald-950/15 text-emerald-300 border-emerald-500/30',
        'red' => 'bg-red-950/15 text-red-300 border-red-500/30',
        'yellow' => 'bg-amber-950/15 text-amber-300 border-amber-500/30',
        'purple' => 'bg-brand-purple/15 text-violet-300 border-brand-purple/30',
        'magenta' => 'bg-brand-magenta/15 text-rose-300 border-brand-magenta/30',
        default => 'bg-brand-card/60 text-brand-muted border-brand-border',
    };
@endphp

<span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $slot }}
</span>