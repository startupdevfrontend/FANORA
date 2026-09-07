<x-layouts.app
    title="Verifique seu e-mail"
    description="Confirma seu e-mail para activar sua cuenta FANORA y desbloquear todo o conteúdo."
>
    <div class="mx-auto w-full max-w-md">
        <div class="card p-8 text-center">
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