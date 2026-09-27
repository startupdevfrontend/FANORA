@props(['title' => null, 'confirmLabel' => 'Confirmar', 'cancelLabel' => 'Cancelar', 'variant' => 'primary', 'icon' => null])

@php
    $btnClass = match ($variant) {
        'primary' => 'btn-primary',
        'accent' => 'btn-accent',
        'danger' => 'btn-danger',
        'outline' => 'btn-outline',
        default => 'btn-primary',
    };
@endphp

<div x-data="{ open: false }">
    <div @click="open = true" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition.opacity
        x-on:keydown.escape.window="open = false"
        x-on:click.outside="open = false"
        class="fixed inset-0 z-50 grid place-items-center bg-brand-black/70 p-4"
        x-cloak
        x-on:click.self="open = false"
        role="alertdialog"
        aria-modal="true"
    >
        <div class="w-full max-w-md rounded-2xl border border-brand-border bg-brand-card p-6 shadow-2xl">
            @if ($title)
                <h2 class="text-lg font-semibold text-white">{{ $title }}</h2>
            @endif

            <div class="mt-2 text-sm text-brand-muted">
                {{ $slot }}
            </div>

            <div class="mt-6 flex items-center justify-end gap-2">
                <button type="button" class="btn btn-ghost" x-on:click="open = false">{{ $cancelLabel }}</button>
                <button type="button" class="btn {{ $btnClass }}" @isset($action) x-on:click="{{ $action }}; open = false" @endisset>{{ $confirmLabel }}</button>
            </div>
        </div>
    </div>
</div>