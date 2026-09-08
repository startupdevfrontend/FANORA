@props(['label' => null, 'color' => 'neutral'])

@php
    $classes = match ($color) {
        'green' => 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-200 border-emerald-400/50 shadow-sm shadow-emerald-500/10',
        'red' => 'bg-gradient-to-r from-red-500/20 to-rose-500/20 text-red-200 border-red-400/50 shadow-sm shadow-red-500/10',
        'yellow' => 'bg-gradient-to-r from-amber-500/25 to-yellow-500/20 text-amber-100 border-amber-400/60 shadow-sm shadow-amber-500/15',
        'purple' => 'bg-gradient-to-r from-violet-500/25 to-brand-purple/20 text-violet-100 border-violet-400/50 shadow-sm shadow-violet-500/15',
        'magenta' => 'bg-gradient-to-r from-brand-magenta/25 to-pink-500/20 text-pink-100 border-brand-magenta/50 shadow-sm shadow-brand-magenta/15',
        default => 'bg-brand-card/60 text-brand-muted border-brand-border',
    };
@endphp

<span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-semibold tracking-wide {{ $classes }}">
    {{ $slot }}
</span>