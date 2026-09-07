@props(['creator' => null, 'profile' => null])
@php($profile ??= $creator->creatorProfile)

<div class="creator-card">
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
        <p class="text-sm font-semibold text-white">
            @if ($profile?->subscription_price_cents)
                R$ {{ number_format($profile->subscription_price_cents / 100, 2, ',', '.') }}<span class="text-brand-muted">/mês</span>
            @else
                <span class="text-brand-muted">Gratis</span>
            @endif
        </p>

        <a href="{{ route('creator.show', $creator->username) }}" class="btn-outline sm">Ver perfil</a>
    </div>
</div>