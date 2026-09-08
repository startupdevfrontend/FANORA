@props(['creator' => null, 'profile' => null])
@php($profile ??= $creator->creatorProfile)

<div class="creator-card transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-magenta/10">
    <div class="flex items-center gap-4">
        <x-avatar :path="$creator->profile?->avatar_path" :name="$creator->name" size="lg" />

        <div class="min-w-0">
            <a href="{{ route('creator.show', $creator->username) }}" class="truncate text-base font-semibold text-white hover:text-brand-magenta">
                {{ $profile?->display_name ?? $creator->name }}
            </a>
            <p class="text-sm text-brand-muted">{{ $creator->username }}</p>
            <div class="mt-1 flex flex-wrap gap-1.5">
                @foreach ($profile?->categories ?? [] as $category)
                    <x-badge>{{ $category->name }}</x-badge>
                @endforeach
            </div>
        </div>
    </div>

    @if (filled($profile?->tagline))
        <p class="mt-3 line-clamp-2 text-sm text-brand-muted">{{ $profile->tagline }}</p>
    @endif

    <div class="mt-4 flex items-center justify-between gap-3">
        @if ($profile?->subscription_price_cents)
            <x-badge color="magenta">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                R$ {{ number_format($profile->subscription_price_cents / 100, 2, ',', '.') }}<span class="opacity-70">/mês</span>
            </x-badge>
        @else
            <x-badge color="green">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Grátis
            </x-badge>
        @endif

        <a href="{{ route('creator.show', $creator->username) }}" class="btn-outline sm">Ver perfil</a>
    </div>
</div>