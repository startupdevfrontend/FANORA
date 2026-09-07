@props(['subscription' => null])

@php($creator = $subscription->creator)

<div class="card flex flex-col gap-3 p-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('creator.show', $creator->username) }}">
            <x-avatar :path="$creator->profile?->avatar_path" :name="$creator->name" size="md" />
        </a>
        <div class="min-w-0">
            <a href="{{ route('creator.show', $creator->username) }}" class="block text-sm font-semibold text-white hover:text-brand-magenta">{{ $creator->name }}</a>
            <p class="text-xs text-brand-muted">{{ $creator->username }}</p>
        </div>

        <x-badge :color="match ($subscription->status) {
            'active' => 'green',
            'pending' => 'yellow',
            'cancelled' => 'red',
            'expired' => 'neutral',
            default => 'neutral',
        }" class="ml-auto">{{ $subscription->status->label() }}</x-badge>
    </div>

    <div class="mt-1 grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
        <span class="text-xs text-brand-muted">Preço</span>
        <span class="text-sm font-semibold text-white">R$ {{ number_format($subscription->value_cents / 100, 2, ',', '.') }}/mês</span>

        @if ($subscription->starts_at)
            <span class="text-xs text-brand-muted">Início</span>
            <span class="text-sm text-white/80">{{ $subscription->starts_at?->format('d/m/Y') }}</span>
        @endif

        @if ($subscription->ends_at)
            <span class="text-xs text-brand-muted">Vence</span>
            <span class="text-sm text-white/80">{{ $subscription->ends_at?->format('d/m/Y') }}</span>
        @endif
    </div>

    @if ($subscription->status === 'active')
        <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}" class="mt-2"
              onsubmit="return confirm('Cancelar esta assinatura? Perderás el acceso exclusivo.');">
            @csrf
            @method('DELETE')
            <button class="btn-danger sm w-full">Cancelar assinatura</button>
        </form>
    @endif
</div>