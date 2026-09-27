@props(['align' => 'left'])

<div class="relative" x-data="{ open: false }">
    <div @click="open = !open" @keydown.escape.window="open = false" @click.outside="open = false">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition
        x-on:click.stop
        x-cloak
        class="absolute {{ $align === 'right' ? 'right-0' : 'left-0' }} z-50 mt-2 w-52 rounded-xl border border-brand-border bg-brand-card p-1 shadow-lg"
    >
        {{ $slot }}
    </div>
</div>