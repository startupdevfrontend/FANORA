<x-layouts.app
    title="Cookies"
    description="Política de Cookies de FANORA: qué cookies utilizamos, para qué y cómo gestionarlas."
>
    <div class="mx-auto w-full max-w-prose">
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-magenta/15 via-transparent to-brand-purple/15 p-8 backdrop-blur-sm">
            <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-brand-magenta/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-brand-purple/20 blur-3xl"></div>
            <div class="relative">
                <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Política de Cookies</h1>
                <p class="mt-3 text-sm text-brand-muted sm:text-base">Última atualização: 1 de janeiro de 2026.</p>
            </div>
        </div>

        <div class="card mt-6 p-6 sm:p-8">
            <h2 class="text-xl font-bold tracking-tight text-white sm:text-2xl">1. Qué son las cookies</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Las cookies son pequeños archivos de texto que se almacenan en tu navegador cuando visitas um
                sitio. Utilizamos cookies para que la plataforma funcione correctamente y para recordar tus
                preferencias.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">2. Tipos de cookies que utilizamos</h2>
            <ul class="mt-4 list-disc space-y-3 pl-6 leading-relaxed text-brand-muted">
                <li>
                    <strong class="font-semibold text-white">Esenciales de sesión</strong>: mantienen tu sesión
                    iniciada y protegen el acesso a tu cuenta. Sin ellas, la plataforma no puede funcionar.
                </li>
                <li>
                    <strong class="font-semibold text-white">Preferencias</strong>: recuerdan tus configuraciónes
                    (tema, idioma, vistas) para mejorar la experiencia.
                </li>
                <li>
                    <strong class="font-semibold text-white">Analíticas</strong>: en el futuro podemos utilizarlas
                    de forma agregada y anónima para melhorar la plataforma, siempre sin rastreo indebido de los
                    usuarios.
                </li>
            </ul>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">3. Cómo gestionar las cookies</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Puedes aceptar, bloquear o eliminar las cookies desde la configuración de tu navegador. Ten en
                cuenta que bloquear las cookies esenciales impedirá el inicio de sesión y el acceso a contenido
                exclusivo.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">4. Consentimiento</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Utilizamos únicamente cookies esenciales para operar el servicio. Cuando introduzcamos cookies de
                preferencia ou analíticas, solicitaremos tu consentimiento expreso antes de activarlas, conforme a
                la normativa aplicable.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">5. Privacidade</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Para conocer cómo tratamos el resto de tus dados pessoais, consulta nuestra
                <a href="{{ route('privacy') }}" class="font-medium text-brand-magenta underline decoration-brand-magenta/40 underline-offset-2 hover:text-white hover:decoration-white">Política de Privacidade</a>.
            </p>
        </div>
    </div>
</x-layouts.app>