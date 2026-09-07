<x-layouts.app
    title="Criar conta"
    description="Cria sua conta FANORA e descubre conteúdo exclusivo de creators. Plataforma para mayores de 18 años."
>
    <div class="mx-auto w-full max-w-md">
        <div class="card p-8">
            <h1 class="text-2xl font-bold tracking-tight">Criar conta</h1>
            <p class="mt-2 text-sm text-brand-muted">Leva menos de um minuto. Solo maiores de 18 años.</p>

            <form method="post" action="{{ route('register') }}" class="mt-6 space-y-5">
                @csrf

                <x-input name="name" label="Nome" placeholder="Nome completo" required />
                <x-input name="username" label="Usuário" hint="letras minúsculas, sin espaços" required />
                <x-input name="email" label="E-mail" type="email" placeholder="seu@email.com" required />
                <x-input name="birth_date" label="Data de nascimento" type="date" hint="Você deve ter pelo menos 18 años" required />
                <x-input name="password" label="Senha" type="password" autocomplete="new-password" required />
                <x-input name="password_confirmation" label="Confirmar senha" type="password" autocomplete="new-password" required />

                <x-alert type="warning">Somente maiores de 18 años. Ao crear uma conta você confirma sua maioridade legal.</x-alert>

                <div class="flex items-start gap-2.5">
                    <input id="terms" type="checkbox" name="terms" value="1" class="mt-1 h-4 w-4 accent-brand-magenta">
                    <label for="terms" class="text-sm text-brand-muted">
                        Acepto os <a href="{{ route('terms') }}" target="_blank" class="font-medium text-brand-magenta underline hover:text-white">Termos de Uso</a>
                    </label>
                </div>
                @if ($errors->has('terms'))
                    <p class="mt-1 text-xs text-red-400">{{ $errors->first('terms') }}</p>
                @endif

                <div class="flex items-start gap-2.5">
                    <input id="privacy" type="checkbox" name="privacy" value="1" class="mt-1 h-4 w-4 accent-brand-magenta">
                    <label for="privacy" class="text-sm text-brand-muted">
                        Acepto a <a href="{{ route('privacy') }}" target="_blank" class="font-medium text-brand-magenta underline hover:text-white">Política de Privacidade</a>
                    </label>
                </div>
                @if ($errors->has('privacy'))
                    <p class="mt-1 text-xs text-red-400">{{ $errors->first('privacy') }}</p>
                @endif

                <x-button type="submit" block>Crear conta</x-button>
            </form>

            <p class="mt-6 text-sm text-brand-muted">
                Já tem conta? <a href="{{ route('login') }}" class="font-medium text-brand-magenta hover:text-white">Entrar</a>
            </p>
        </div>
    </div>
</x-layouts.app>