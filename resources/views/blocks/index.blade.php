<x-layouts.app
    title="Usuários bloqueados — FANORA"
    description="Lista de usuários que você bloqueou."
>

    <section class="mb-8 relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 shadow-2xl shadow-black/20">
        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-red-500/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-brand-purple/20 blur-3xl"></div>
        <div class="relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-300 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    @php($totalBlocks = $blocks->total())
                    {{ $totalBlocks }} bloqueado(s)
                </div>
                <h1 class="section-title text-4xl">Bloqueados</h1>
                <p class="mt-2 text-base text-brand-muted max-w-xl">Usuários que você bloqueou não poderão interagir com você, visualizar suas publicações ou enviar mensagens.</p>
            </div>
        </div>
    </section>

    @php($items = $blocks->getCollection())

    @if ($items->isEmpty())
        <x-empty-state
            title="Nenhum bloqueio"
            description="Quando bloquear alguém, aparecerá aquí."
        />
    @else
        <div class="glass-card overflow-hidden">
            <ul class="divide-y divide-brand-border/60">
                @foreach ($items as $block)
                    <li class="group p-5 transition-all duration-200 hover:bg-white/5">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="relative shrink-0">
                                    <div class="rounded-full p-[2px] bg-gradient-to-br from-red-500/50 to-red-600/50">
                                        <x-avatar :path="$block->blocked->profile?->avatar_path" :name="$block->blocked->name" size="md" />
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 grid h-6 w-6 place-items-center rounded-full bg-red-500/90 ring-2 ring-brand-card">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start sm:items-center flex-wrap gap-2">
                                        <div class="min-w-0">
                                            <span class="block text-base font-bold text-white truncate">{{ $block->blocked->name }}</span>
                                            <span class="text-sm text-brand-muted">@ {{ $block->blocked->username }}</span>
                                        </div>
                                        <x-badge color="red">Bloqueado</x-badge>
                                    </div>
                                    <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-brand-muted/80">
                                        <div class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <span>Bloqueado em: <strong class="font-medium text-white/70">{{ $block->created_at->format('d/m/Y') }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 sm:justify-end">
                                <form method="POST" action="{{ route('blocks.destroy', $block) }}" onsubmit="return confirm('Desbloquear este usuário? Ele poderá interagir com você novamente.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-outline sm inline-flex items-center gap-2 group/btn hover:border-emerald-500/50 hover:text-emerald-400 hover:shadow-md hover:shadow-emerald-500/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Desbloquear
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-6">
            <x-pagination :paginator="$blocks" />
        </div>
    @endif
</x-layouts.app>
