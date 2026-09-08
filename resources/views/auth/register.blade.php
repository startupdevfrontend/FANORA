<x-layouts.app
    title="Criar conta"
    description="Cria sua conta FANORA e descubre conteúdo exclusivo de creators. Plataforma para mayores de 18 años."
>
    <div class="relative mx-auto w-full max-w-md py-12">
        <div class="hero-gradient absolute inset-0 z-[-1]"></div>
        <div class="card glass-card p-8 shadow-2xl shadow-brand-magenta/5">
            <img src="{{ asset('img/logo-text-dark.png') }}" alt="FANORA" class="mx-auto h-20 w-auto mb-2 drop-shadow-lg">
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

                <div class="relative overflow-hidden rounded-xl border-2 border-amber-500/70 bg-gradient-to-r from-amber-500/15 via-orange-500/10 to-amber-500/15 p-4 backdrop-blur-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-amber-500/20 text-2xl ring-1 ring-amber-500/40">
                            🔞
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-amber-300">Conteúdo para maiores de 18 años</p>
                            <p class="mt-1 text-xs leading-relaxed text-amber-200/90">Somente maiores de 18 años. Ao crear uma conta você confirma sua maioridade legal.</p>
                        </div>
                    </div>
                    <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-amber-500/10 blur-2xl"></div>
                </div>

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