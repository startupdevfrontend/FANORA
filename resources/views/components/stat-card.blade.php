@props(['title', 'value', 'icon' => null, 'hint' => null, 'accent' => false])

<div class="stat-card {{ $accent ? 'border-brand-magenta/40 bg-gradient-brand/10' : '' }}">
    @if ($icon)
        <div class="stat-icon">{{ $icon }}</div>
    @endif
    <p class="text-sm font-medium text-brand-muted">{{ $title }}</p>
    <p class="stat-value {{ $accent ? 'text-white' : 'text-white' }}">{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif
</div>