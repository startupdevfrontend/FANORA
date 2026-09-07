@props(['variant' => 'primary', 'size' => 'md', 'icon' => null, 'href' => null, 'type' => 'button', 'block' => false, 'data' => []])

@php
    $classes = 'btn base';
    $classes .= match ($variant) {
        'primary' => ' btn-primary',
        'accent' => ' btn-accent',
        'outline' => ' btn-outline',
        'ghost' => ' btn-ghost',
        'danger' => ' btn-danger',
        default => ' btn-primary',
    };
    $classes .= match ($size) {
        'xs' => ' btn-xs',
        'sm' => ' btn-sm',
        'md' => '',
        'lg' => ' btn-lg',
        default => '',
    };
    $classes .= $block ? ' w-full' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $classes }}" @foreach ($data as $k => $v) data-{{ $k }}="{{ $v }}" @endforeach>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" @foreach ($data as $k => $v) data-{{ $k }}="{{ $v }}" @endforeach>
        {{ $slot }}
    </button>
@endif