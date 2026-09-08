<x-layouts.app
    title="Minhas assinaturas — FANORA"
    description="Gerencie as assinaturas que você possui. Cancele quando quiser."
>

    <section class="mb-8 relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 shadow-2xl shadow-black/20">
        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-brand-magenta/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-brand-purple/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-purple/30 bg-brand-purple/10 px-3 py-1 text-xs font-semibold text-brand-purple mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    @if ($subscriptions->where('status', 'active')->count() > 0)
                        {{ $subscriptions->where('status', 'active')->count() }} ativa(s)
                    @else
                        Nenhuma ativa
                    @endif
                </div>
                <h1 class="section-title text-4xl">Minhas assinaturas</h1>
                <p class="mt-2 text-base text-brand-muted max-w-xl">Acesse e gerencie todas as suas assinaturas de creators. Você pode cancelar quando quiser, sem burocracia.</p>
            </div>
            <div class="self-start sm:self-center">
                <a href="{{ route('explore') }}" class="btn-outline sm inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Explorar creators
                </a>
            </div>
        </div>

        @php
            $activeCount = $subscriptions->where('status', 'active')->count();
            $items = $subscriptions->getCollection();
        @endphp

        @if ($activeCount > 0)
            <div class="relative z-10 mt-6 grid gap-4 sm:grid-cols-3">
                <div class="glass-card p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 h-20 w-20 rounded-full bg-brand-magenta/10 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 text-brand-magenta mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-xs font-semibold uppercase tracking-wide">Ativas</span>
                        </div>
                        <p class="text-3xl font-black text-white">{{ $activeCount }}</p>
                    </div>
                </div>
                <div class="glass-card p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 h-20 w-20 rounded-full bg-brand-purple/10 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 text-brand-purple mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-xs font-semibold uppercase tracking-wide">Gasto/mês</span>
                        </div>
                        <p class="text-3xl font-black text-white">R$ {{ number_format($subscriptions->where('status', 'active')->sum('value_cents') / 100, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="glass-card p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 h-20 w-20 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 text-brand-muted mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            <span class="text-xs font-semibold uppercase tracking-wide">Total de creators</span>
                        </div>
                        <p class="text-3xl font-black text-white">{{ $items->count() }}</p>
                    </div>
                </div>
            </div>
        @endif
    </section>

    @if ($subscriptions->where('status', 'active')->isEmpty() && $items->isEmpty())
        <x-empty-state
            title="Ainda não há assinaturas"
            description="Assine um creator para acessar conteúdo exclusivo."
        >
            <a href="{{ route('explore') }}" class="btn-primary sm inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                Explorar creators
            </a>
        </x-empty-state>
    @else
        <div class="space-y-5">
            @foreach ($items as $subscription)
                <div class="transition-all duration-200 ease-out hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-brand-magenta/10 rounded-2xl">
                    <x-subscription-card :subscription="$subscription" />
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <x-pagination :paginator="$subscriptions" />
        </div>
    @endif
</x-layouts.app>
