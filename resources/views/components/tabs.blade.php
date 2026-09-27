@props(['tabs' => [], 'active' => null, 'label' => 'Tabs de navegação'])

@php
    if (! is_array($active)) {
        $active = $active ? [$active] : [];
    }
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }} role="tablist" aria-label="{{ $label }}">
    @foreach ($tabs as $tab)
        @php
            $key = $tab['key'] ?? $tab['value'] ?? $tab['name'];
            $isActive = in_array($key, $active, true);
        @endphp
        <a
            href="{{ $tab['href'] }}"
            role="tab"
            aria-selected="{{ $isActive ? 'true' : 'false' }}"
            class="{{ $isActive ? 'active-pill' : 'pill' }}"
        >
            @if (isset($tab['label'])){{ $tab['label'] }}@endif
        </a>
    @endforeach
</div>