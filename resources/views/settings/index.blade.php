<x-layouts.app
    title="Configurações — FANORA"
    description="Gerencie sua conta, senha e dados pessoais."
>

    <section class="mb-8 relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 shadow-2xl shadow-black/20">
        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-brand-magenta/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-brand-purple/20 blur-3xl"></div>
        <div class="relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-magenta/30 bg-brand-magenta/10 px-3 py-1 text-xs font-semibold text-brand-magenta mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Configurações da conta
                </div>
                <h1 class="section-title text-4xl">Configurações</h1>
                <p class="mt-2 text-base text-brand-muted max-w-2xl">Administre sua conta, senha, privacidade e dados pessoais. Mantenha suas informações atualizadas e seguras.</p>
            </div>
        </div>
    </section>

    <div class="space-y-6 max-w-3xl">
        <div class="glass-card p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-brand-magenta/10 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-start gap-4">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-magenta/20 to-brand-purple/20 text-brand-magenta ring-1 ring-brand-magenta/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h2 class="text-lg font-bold text-white">Conta</h2>
                                <p class="mt-1 text-sm text-brand-muted">Informações básicas da sua conta.</p>
                            </div>
                            <x-badge color="magenta">Básico</x-badge>
                        </div>

                        <dl class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="rounded-xl border border-brand-border/60 bg-brand-input/40 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-brand-muted">Nome</dt>
                                <dd class="mt-1 text-sm font-medium text-white">{{ $user->name }}</dd>
                            </div>
                            <div class="rounded-xl border border-brand-border/60 bg-brand-input/40 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-brand-muted">Usuario</dt>
                                <dd class="mt-1 text-sm font-medium text-white">@ {{ $user->username }}</dd>
                            </div>
                            <div class="rounded-xl border border-brand-border/60 bg-brand-input/40 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-brand-muted">E-mail</dt>
                                <dd class="mt-1 text-sm font-medium text-white">{{ $user->email }}</dd>
                            </div>
                            <div class="rounded-xl border border-brand-border/60 bg-brand-input/40 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-brand-muted">Rol</dt>
                                <dd class="mt-1 text-sm font-medium text-white">{{ $user->role->label() }}</dd>
                            </div>
                            <div class="rounded-xl border border-brand-border/60 bg-brand-input/40 px-4 py-3 sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-brand-muted">Membro desde</dt>
                                <dd class="mt-1 text-sm font-medium text-white flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-magenta" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-brand-purple/10 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-start gap-4">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-purple/20 to-brand-magenta/20 text-brand-purple ring-1 ring-brand-purple/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h2 class="text-lg font-bold text-white">Cambiar senha</h2>
                                <p class="mt-1 text-sm text-brand-muted">Usa uma senha segura e única para proteger sua conta.</p>
                            </div>
                            <x-badge color="purple">Segurança</x-badge>
                        </div>

                        <form method="POST" action="{{ route('settings.password') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
                            @method('PUT')
                            @csrf

                            <div class="sm:col-span-2">
                                <x-input name="current_password" type="password" label="Senha atual" autocomplete="current-password" required />
                            </div>
                            <x-input name="password" type="password" label="Nova senha" hint="Mínimo de 8 caracteres. Use letras, números e símbolos." required />
                            <x-input name="password_confirmation" type="password" label="Confirmar senha" required />

                            <div class="sm:col-span-2 flex items-center justify-between gap-3 pt-2">
                                <div class="flex items-center gap-2 text-xs text-brand-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-purple" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Nunca compartilhe sua senha com ninguém.
                                </div>
                                <x-button type="submit" variant="primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Actualizar senha
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-start gap-4">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 text-emerald-400 ring-1 ring-emerald-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h2 class="text-lg font-bold text-white">Dados e privacidade</h2>
                                <p class="mt-1 text-sm text-brand-muted">Exporte seus dados pessoais conforme LGPD / GDPR.</p>
                            </div>
                            <x-badge color="green">LGPD</x-badge>
                        </div>

                        <a href="{{ route('settings.export') }}" class="btn-outline sm mt-5 inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Exportar mis datos (LGPD)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 relative overflow-hidden border-red-500/30 bg-red-500/5">
            <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-red-500/20 blur-3xl"></div>
            <div class="relative">
                <div class="flex items-start gap-4">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-red-500/20 to-orange-500/20 text-red-300 ring-1 ring-red-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h2 class="text-lg font-bold text-red-300">Zona de perigo</h2>
                                <p class="mt-1 text-sm text-brand-muted">Apagar sua conta é permanente e não pode ser revertido. Conservaremos os dados necessários para cumprimento legal.</p>
                            </div>
                            <x-badge color="red">Irreversível</x-badge>
                        </div>

                        <x-alert type="error" class="mt-5">
                            Digite <strong class="font-mono">DELETAR</strong> na caixa abaixo para confirmar a exclusão da conta.
                        </x-alert>

                        <form method="POST" action="{{ route('settings.delete') }}" class="mt-4 max-w-xs"
                              onsubmit="return confirm('Tens segurança? Esta ação não pode ser revertida.');">
                            @csrf

                            <x-input name="confirmation" label="Confirmar eliminação" placeholder="Digite DELETAR" required />

                            <x-button type="submit" variant="danger" class="mt-4 w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Eliminar minha conta
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
