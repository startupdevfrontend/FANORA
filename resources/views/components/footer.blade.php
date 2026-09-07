<footer class="border-t border-brand-border bg-brand-black">
    <div class="mx-auto max-w-5xl px-4 py-10">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-brand text-sm font-bold">F</span>
                    <span class="text-lg font-black tracking-tight">FANORA</span>
                </div>
                <p class="mt-3 text-sm text-brand-muted">Seu conteúdo. Seu público. Seu espaço.</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Plataforma</h3>
                <ul class="mt-3 space-y-2 text-sm text-brand-muted">
                    <li><a class="hover:text-white" href="{{ route('home') }}">Início</a></li>
                    <li><a class="hover:text-white" href="{{ route('explore') }}">Explorar</a></li>
                    <li><a class="hover:text-white" href="{{ route('register') }}">Criar meu perfil</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Legal</h3>
                <ul class="mt-3 space-y-2 text-sm text-brand-muted">
                    <li><a class="hover:text-white" href="{{ route('terms') }}">Termos de Uso</a></li>
                    <li><a class="hover:text-white" href="{{ route('privacy') }}">Política de Privacidad</a></li>
                    <li><a class="hover:text-white" href="{{ route('content-policy') }}">Política de Conteúdo</a></li>
                    <li><a class="hover:text-white" href="{{ route('cookies') }}">Cookies</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Ajuda</h3>
                <ul class="mt-3 space-y-2 text-sm text-brand-muted">
                    <li><a class="hover:text-white" href="{{ route('contact') }}">Contato</a></li>
                    <li><a class="hover:text-white" href="https://opencode.ai" rel="nofollow noopener">Suporte</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-between gap-3 border-t border-brand-border pt-4 text-xs text-brand-muted">
            <p>© {{ date('Y') }} FANORA. Todos os direitos reservados.</p>
            <p class="flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Plataforma para maiores de 18 anos
            </p>
        </div>
    </div>
</footer>