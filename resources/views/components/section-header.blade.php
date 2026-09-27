@props(['title', 'subtitle' => null, 'icon' => null])

<header class="glass-card relative mb-8 overflow-hidden p-6 sm:p-8">
    <div class="hero-gradient pointer-events-none absolute inset-0"></div>
    <div class="relative">
        <div class="flex items-center gap-4">
            @if ($icon)
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-brand text-white shadow-lg shadow-brand-magenta/20">
                    {!! $icon !!}
                </div>
            @endif
            <div>
                <h1 class="section-title mb-0 text-2xl sm:text-3xl">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-1 text-sm text-brand-muted">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @isset($actions)
            <div class="mt-4 flex flex-wrap items-center gap-2 sm:absolute sm:right-8 sm:top-1/2 sm:mt-0 sm:-translate-y-1/2">
                {{ $actions }}
            </div>
        @endisset
    </div>
</header>