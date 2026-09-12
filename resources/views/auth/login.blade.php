<x-layouts.app
    title="Entrar"
    description="Acesse sua conta FANORA para ver conteúdo exclusivo de seus creators favoritos."
>
    <div class="relative mx-auto w-full max-w-md py-12">
        <div class="hero-gradient absolute inset-0 z-[-1]"></div>
        <div class="card glass-card p-8 shadow-2xl shadow-brand-magenta/5">
            <img src="{{ asset('img/logo-text-dark.png') }}" alt="FANORA" class="mx-auto h-20 w-auto mb-2 drop-shadow-lg">
            <h1 class="text-2xl font-bold tracking-tight">Entrar</h1>
            <p class="mt-2 text-sm text-brand-muted">Acesse sua conta para não perder nenhum conteúdo exclusivo.</p>

            <form method="post" action="{{ route('login') }}" class="mt-6 space-y-5">
                @csrf

                <x-input name="email" label="E-mail" type="email" placeholder="seu@email.com" required />
                <x-input name="password" label="Senha" type="password" autocomplete="current-password" placeholder="••••••••" required />

                <div class="flex items-center gap-2.5">
                    <input id="remember" type="checkbox" name="remember" value="1" class="h-4 w-4 accent-brand-magenta">
                    <label for="remember" class="text-sm text-brand-muted">Lembrar de mim</label>
                </div>

                <x-button type="submit" block>Entrar</x-button>
            </form>

            <div class="mt-6 flex flex-col gap-2 text-sm sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('password.request') }}" class="font-medium text-brand-muted hover:text-white">Esqueceu sua senha?</a>
                <a href="{{ route('register') }}" class="font-medium text-brand-magenta hover:text-white">Criar conta →</a>
            </div>
        </div>
    </div>
</x-layouts.app>