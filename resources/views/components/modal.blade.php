@props(['title' => null, 'size' => 'md'])

<div x-data="{ open: false }" x-cloak>
    <div @click="open = true" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition.opacity
        x-on:keydown.escape.window="open = false"
        class="fixed inset-0 z-50 bg-brand-black/70"
        style="display: none;"
        x-on:click.self="open = false"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="mx-auto my-10 w-full max-w-md rounded-2xl border border-brand-border bg-brand-card shadow-2xl"
            x-show="open"
            x-transition
            x-on:click.stop
        >
            @if ($title)
                <div class="flex items-center justify-between border-b border-brand-border px-4 py-3">
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                    <button type="button" class="btn-ghost btn-xs" x-on:click="open = false" aria-label="Cerrar">×</button>
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