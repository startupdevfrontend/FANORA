<x-layouts.app
    title="Recuperar senha"
    description="Ingresa seu e-mail e enviamos um link para redefinir a senha de sua conta FANORA."
>
    <div class="mx-auto w-full max-w-md">
        <div class="card p-8">
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