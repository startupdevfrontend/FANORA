<x-layouts.app
    title="Redefinir senha"
    description="Establece uma nuova senha para sua cuenta FANORA."
>
    <div class="relative mx-auto w-full max-w-md py-12">
        <div class="hero-gradient absolute inset-0 z-[-1]"></div>
        <div class="card glass-card p-8 shadow-2xl shadow-brand-magenta/5">
            <img src="{{ asset('img/logo-text-dark.png') }}" alt="FANORA" class="mx-auto h-20 w-auto mb-2 drop-shadow-lg">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500/20 to-green-500/20 ring-1 ring-emerald-500/30">
                <svg class="h-8 w-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Redefinir senha</h1>
            <p class="mt-2 text-sm text-brand-muted">Escolha una nuova senha segura para sua conta.</p>

            <form method="post" action="{{ route('password.store') }}" class="mt-6 space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $request->query('token') }}">
                <x-input name="email" label="E-mail" type="email" placeholder="seu@email.com" required />
                <x-input name="password" label="Nuova senha" type="password" autocomplete="new-password" required />
                <x-input name="password_confirmation" label="Confirmar nuova senha" type="password" autocomplete="new-password" required />

                <x-button type="submit" block>Redefinir senha</x-button>
            </form>
        </div>
    </div>
</x-layouts.app>