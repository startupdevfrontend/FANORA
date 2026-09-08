<x-layouts.app
    title="Política de Conteúdo"
    description="Reglas que determinam qué contenido está permitido y qué está estrictamente proibido en FANORA."
>
    <div class="mx-auto w-full max-w-prose">
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-magenta/15 via-transparent to-brand-purple/15 p-8 backdrop-blur-sm">
            <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-brand-magenta/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-brand-purple/20 blur-3xl"></div>
            <div class="relative">
                <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Política de Conteúdo</h1>
                <p class="mt-3 text-sm text-brand-muted sm:text-base">FANORA prioriza la seguridad, el consentimiento y la legalidad.</p>
            </div>
        </div>

        <div class="card mt-6 p-6 sm:p-8">
            <x-alert type="error">
                <strong class="font-semibold">Estrictamente proibido:</strong>
                <ul class="mt-2 list-disc space-y-1.5 pl-5">
                    <li>Conteúdo que involucre menores de idade.</li>
                    <li>Explotación sexual o conteúdo sin consentimiento.</li>
                    <li>Violência sexual o su promoción.</li>
                    <li>Cualquier contenido ilegal.</li>
                </ul>
            </x-alert>

            <h2 class="mt-8 text-xl font-bold tracking-tight text-white sm:text-2xl">1. Nuestro compromiso</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                FANORA se compromete a mantener uma plataforma segura para mayores de 18 años. Trabajamos con
                moderación activa y herramientas de denuncia para retirar rápidamente el contenido que viole
                esta política.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">2. Conteúdo aceptable</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">Está permitido publicar conteúdo original, consensuado e legal, incluyendo:</p>
            <ul class="mt-4 list-disc space-y-2 pl-6 leading-relaxed text-brand-muted">
                <li>Fotografías, vídeos e textos exclusivos creados pelo próprio creator.</li>
                <li>Conteúdo para adultos consensuado entre adultos.</li>
                <li>Conversaciones, live streams y material exclusivo para assinantes.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">3. Conteúdo estrictamente proibido</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Cualquier contenido que involucre menores, explotación, violência sexual o sea ilegal está
                estrictamente proibido, tenga o consentimiento o no. Esto incluye:
            </p>
            <ul class="mt-4 list-disc space-y-2 pl-6 leading-relaxed text-brand-muted">
                <li>Material de abuso o explotación sexual de menores.</li>
                <li>Contenido que promueva la violência sexual o la agresión.</li>
                <li>Contenido obtenido sin consentimiento o que viole la intimidad de terceros.</li>
                <li>Tráfico, drogas o cualquier actividad ilegal.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">4. Moderação</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Todo el contenido puede ser revisado por nuestra equipo de moderação, de forma preventiva o a
                posteriori. Si se detecta una infracción, el contenido se retira y la cuenta queda sujeta a
                sanciones.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">5. Denúncias</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Si encuentras contenido que viole esta política, denuncia lo através del
                <a href="{{ route('contact') }}" class="font-medium text-brand-magenta underline decoration-brand-magenta/40 underline-offset-2 hover:text-white hover:decoration-white">formulário de contato</a>
                indicando el motivo y el enlace del conteúdo. Todas las denúncias se revisan con prioridade.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">6. Suspensión de cuenta</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Dependiendo de la gravedad e reincidencia de las infracciones, la cuenta puede ser suspendida
                temporal o definitivamente, y las cobranças pendientes se gestionan conforme a los términos.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">7. Apelaciones</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Si consideras que el retiro de tu conteúdo o la sanción fue un error, puedes presentar una apelación
                por el formulário de
                <a href="{{ route('contact') }}" class="font-medium text-brand-magenta underline decoration-brand-magenta/40 underline-offset-2 hover:text-white hover:decoration-white">contato</a>
                com uma explicación detallada.
            </p>
        </div>
    </div>
</x-layouts.app>
