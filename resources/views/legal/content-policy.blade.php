<x-layouts.app
    title="Política de Conteúdo"
    description="Reglas que determinam qué contenido está permitido y qué está estrictamente proibido en FANORA."
>
    <div class="mx-auto w-full max-w-3xl">
        <h1 class="text-3xl font-black tracking-tight">Política de Conteúdo</h1>
        <p class="mt-3 text-brand-muted">FANORA prioriza la seguridad, el consentimiento y la legalidad.</p>

        <x-alert type="error">
            <strong class="font-semibold">Estrictamente proibido:</strong>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <li>Conteúdo que involucre menores de idade.</li>
                <li>Explotación sexual o contenido sin consentimiento.</li>
                <li>Violência sexual o su promoción.</li>
                <li>Cualquier conteúdo ilegal.</li>
            </ul>
        </x-alert>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">1. Nuestro compromiso</h2>
        <p class="mt-3 text-brand-muted">
            FANORA se compromete a mantener uma plataforma segura para mayores de 18 años. Trabajamos con
            moderación activa y herramientas de denuncia para retirar rápidamente el conteúdo que viole
            esta política.
        </p>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">2. Conteúdo aceptable</h2>
        <p class="mt-3 text-brand-muted">Está permitido publicar conteúdo original, consensuado e legal, incluyendo:</p>
        <ul class="mt-3 list-disc space-y-1 pl-5 text-brand-muted">
            <li>Fotografías, vídeos e textos exclusivos creados pelo próprio creator.</li>
            <li>Conteúdo para adultos consensuado entre adultos.</li>
            <li>Conversaciones, live streams y material exclusivo para assinantes.</li>
        </ul>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">3. Conteúdo estrictamente proibido</h2>
        <p class="mt-3 text-brand-muted">
            Cualquier conteúdo que involucre menores, explotación, violência sexual o sea ilegal está
            estrictamente proibido, tenga o consentimiento o no. Esto incluye:
        </p>
        <ul class="mt-3 list-disc space-y-1 pl-5 text-brand-muted">
            <li>Material de abuso o explotación sexual de menores.</li>
            <li>Contenido que promueva la violência sexual o la agresión.</li>
            <li>Contenido obtenido sin consentimiento o que viole la intimidad de terceros.</li>
            <li>Tráfico, drogas o cualquier actividad ilegal.</li>
        </ul>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">4. Moderação</h2>
        <p class="mt-3 text-brand-muted">
            Todo el conteúdo puede ser revisado por nuestra equipo de moderação, de forma preventiva o a
            posteriori. Si se detecta una infracción, el conteúdo se retira y la cuenta queda sujeta a
            sanciones.
        </p>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">5. Denúncias</h2>
        <p class="mt-3 text-brand-muted">
            Si encuentras conteúdo que viole esta política, denuncia lo através del
            <a href="{{ route('contact') }}" class="font-medium text-brand-magenta underline hover:text-white">formulário de contato</a>
            indicando el motivo y el enlace del conteúdo. Todas las denúncias se revisan con prioridade.
        </p>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">6. Suspensión de cuenta</h2>
        <p class="mt-3 text-brand-muted">
            Dependiendo de la gravedad e reincidencia de las infracciones, la cuenta puede ser suspendida
            temporal o definitivamente, y las cobranças pendientes se gestionan conforme a los términos.
        </p>

        <h2 class="mt-10 text-xl font-bold tracking-tight text-white">7. Apelaciones</h2>
        <p class="mt-3 text-brand-muted">
            Si consideras que el retiro de tu conteúdo o la sanción fue un error, puedes presentar una apelación
            por el formulário de
            <a href="{{ route('contact') }}" class="font-medium text-brand-magenta underline hover:text-white">contato</a>
            com uma explicación detallada.
        </p>
    </div>
</x-layouts.app>