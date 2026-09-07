@props(['icon' => 'inbox', 'title' => 'Nada por aquí', 'description' => null, 'action' => null])

<div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
    <span class="grid h-16 w-16 place-items-center rounded-2xl bg-brand-card border border-brand-border">
        <svg class="h-8 w-8 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5 3.75 7.5 3.75 7.5 12.75 7.5 12.75 7.5 10.5 7.5 10.5 5.25 12.75 5.25 12.75 4.5 18 4.5"/></svg>
    </span>
    <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
    @if ($description)
        <p class="max-w-md text-center text-sm text-brand-muted">{{ $description }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div>{{ $slot }}</div>
    @elseif ($action)
        <div>{!! $action !!}</div>
    @endif
</div>