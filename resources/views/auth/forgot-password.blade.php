<x-layouts.app
    title="Recuperar senha"
    description="Ingresa seu e-mail e enviamos um link para redefinir a senha de sua conta FANORA."
>
    <div class="relative mx-auto w-full max-w-md py-12">
        <div class="hero-gradient absolute inset-0 z-[-1]"></div>
        <div class="card glass-card p-8 shadow-2xl shadow-brand-magenta/5">
            <img src="{{ asset('img/logo-text-dark.png') }}" alt="FANORA" class="mx-auto h-20 w-auto mb-2 drop-shadow-lg">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 ring-1 ring-blue-500/30">
                <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Recuperar senha</h1>
            <p class="mt-2 text-sm text-brand-muted">
                Dinos o e-mail associado à sua conta e te enviaremos um link de redefinición. Você poderá
                estabelecer uma nuova senha em poucos minutos.
            </p>

            @if (session('status'))
                <div class="mt-4">
                    <x-alert type="success">{{ session('status') }}</x-alert>
                </div>
            @endif

            <form method="post" action="{{ route('password.email') }}" class="mt-6 space-y-5">
                @csrf

                <x-input name="email" label="E-mail" type="email" placeholder="seu@email.com" required />

                <x-button type="submit" block>Enviar link de redefinición</x-button>
            </form>

            <p class="mt-6 text-sm text-brand-muted">
                Lembras a senha? <a href="{{ route('login') }}" class="font-medium text-brand-magenta hover:text-white">Entrar</a>
            </p>
        </div>
    </div>
</x-layouts.app>