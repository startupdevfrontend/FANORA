<x-layouts.app
    title="Verifique seu e-mail"
    description="Confirma seu e-mail para activar sua cuenta FANORA y desbloquear todo o conteúdo."
>
    <div class="relative mx-auto w-full max-w-md py-12">
        <div class="hero-gradient absolute inset-0 z-[-1]"></div>
        <div class="card glass-card p-8 text-center shadow-2xl shadow-brand-magenta/5">
            <img src="{{ asset('img/logo-text-dark.png') }}" alt="FANORA" class="mx-auto h-20 w-auto mb-2 drop-shadow-lg">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500/20 to-purple-500/20 ring-1 ring-violet-500/30">
                <svg class="h-8 w-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Verifique seu e-mail</h1>
            <p class="mt-3 text-sm text-brand-muted">
                Enviamos um link de confirmación a <span class="font-medium text-white">{{ auth()->user()->email }}</span>.
                Clique no link que recebiu para activar sua cuenta. Se não chegou, verifique a caixa de spam.
            </p>

            @if (session('status'))
                <div class="mt-4">
                    <x-alert type="success">{{ session('status') }}</x-alert>
                </div>
            @endif

            <form method="post" action="{{ route('verification.send') }}" class="mt-6">
                @csrf

                <x-button type="submit" block>Reenviar e-mail de confirmación</x-button>
            </form>
        </div>
    </div>
</x-layouts.app>