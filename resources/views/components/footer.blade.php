<footer class="border-t border-brand-border bg-gradient-to-b from-brand-black to-black/95">
    <div class="mx-auto max-w-5xl px-4 py-14">
        <div class="grid grid-cols-1 gap-12 md:grid-cols-4 md:gap-10">
            <div class="md:col-span-1">
                <img src="{{ asset('img/logo-icon.png') }}" alt="FANORA" class="h-8 w-auto rounded-lg">
                <img src="{{ asset('img/design-01.png') }}" alt="FANORA" class="mt-2 h-8 w-auto rounded-lg">
                <p class="mt-4 text-sm font-bold tracking-widest text-brand-magenta/90 uppercase">SEU CONTEÚDO. SEU PÚBLICO. SEU ESPAÇO.</p>
                <p class="mt-3 text-sm text-brand-muted/80">A plataforma para criadores que valorizam seu trabalho e seu público.</p>
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white/90">Plataforma</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('home') }}">Início</a></li>
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('explore') }}">Explorar</a></li>
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('register') }}">Criar meu perfil</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white/90">Legal</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('terms') }}">Termos de Uso</a></li>
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('privacy') }}">Política de Privacidad</a></li>
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('content-policy') }}">Política de Conteúdo</a></li>
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('cookies') }}">Cookies</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-white/90">Ajuda</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="{{ route('contact') }}">Contato</a></li>
                    <li><a class="text-brand-muted transition-colors hover:text-brand-magenta" href="https://opencode.ai" rel="nofollow noopener">Suporte</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-wrap items-center justify-between gap-4 border-t border-brand-border/60 pt-6 text-xs text-brand-muted/70">
            <p>© {{ date('Y') }} FANORA. Todos os direitos reservados.</p>
            <p class="flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                Plataforma para maiores de 18 anos
            </p>
        </div>
    </div>
</footer>