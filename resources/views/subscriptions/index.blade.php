<x-layouts.app
    title="Minhas assinaturas — FANORA"
    description="Gerencie as assinaturas que você possui. Cancele quando quiser."
>

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Minhas assinaturas</x-page-title>
    </div>

    @php
        $items = $subscriptions->getCollection();
    @endphp

    @if ($subscriptions->where('status', 'active')->isEmpty() && $items->isEmpty())
        <x-empty-state
            title="Ainda não há assinaturas"
            description="Assine um creator para acessar conteúdo exclusivo."
        >
            <a href="{{ route('explore') }}" class="btn-primary sm">Explorar creators</a>
        </x-empty-state>
    @else
        <div class="space-y-5">
            @foreach ($items as $subscription)
                <x-subscription-card :subscription="$subscription" />
            @endforeach
        </div>

        <x-pagination :paginator="$subscriptions" />
    @endif
</x-layouts.app>