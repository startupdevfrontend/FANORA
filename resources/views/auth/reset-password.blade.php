<x-layouts.app
    title="Redefinir senha"
    description="Establece uma nuova senha para sua cuenta FANORA."
>
    <div class="mx-auto w-full max-w-md">
        <div class="card p-8">
            <h1 class="text-2xl font-bold tracking-tight">Redefinir senha</h1>
            <p class="mt-2 text-sm text-brand-muted">Escolha uma nuova senha segura para sua conta.</p>

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