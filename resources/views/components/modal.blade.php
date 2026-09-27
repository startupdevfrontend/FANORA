@props(['title' => null, 'size' => 'md', 'closeLabel' => 'Fechar'])

@php
    $sizeClass = match ($size) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-3xl',
        'xl' => 'max-w-5xl',
        default => 'max-w-md',
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
        class="fixed inset-0 z-50 overflow-y-auto bg-brand-black/70 p-4"
        x-cloak
        x-on:click.self="open = false"
        role="dialog"
        aria-modal="true"
        :aria-label="title ?? 'Diálogo'"
    >
        <div
            class="mx-auto my-10 w-full {{ $sizeClass }} rounded-2xl border border-brand-border bg-brand-card shadow-2xl"
            x-show="open"
            x-transition
            x-on:click.stop
        >
            @if ($title)
                <div class="flex items-center justify-between border-b border-brand-border px-4 py-3">
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                    <button type="button" class="btn-ghost btn-sm px-2" x-on:click="open = false" aria-label="{{ $closeLabel }}">×</button>
                </div>
            @endif

            <div class="space-y-4 p-4">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex justify-end gap-2 border-t border-brand-border px-4 py-3">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>