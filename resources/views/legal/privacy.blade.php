<x-layouts.app
    title="Privacidade"
    description="Política de Privacidade de FANORA: cómo recopilamos, tratamos e protegemos sus datos pessoais."
>
    <div class="mx-auto w-full max-w-prose">
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-magenta/15 via-transparent to-brand-purple/15 p-8 backdrop-blur-sm">
            <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-brand-magenta/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-brand-purple/20 blur-3xl"></div>
            <div class="relative">
                <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Política de Privacidade</h1>
                <p class="mt-3 text-sm text-brand-muted sm:text-base">Última atualização: 1 de janeiro de 2026.</p>
            </div>
        </div>

        <div class="card mt-6 p-6 sm:p-8">
            <h2 class="text-xl font-bold tracking-tight text-white sm:text-2xl">1. Controlador de datos</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                FANORA es el controlador de los datos pessoais tratados a través de esta plataforma. Para qualquer
                questão relacionada com el tratamiento de tus datos, puedes contactar com nós en
                <a href="{{ route('contact') }}" class="font-medium text-brand-magenta underline decoration-brand-magenta/40 underline-offset-2 hover:text-white hover:decoration-white">contacto</a>.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">2. Datos que recopilamos</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">Recopilamos únicamente los datos necesários para el funcionamento del servicio:</p>
            <ul class="mt-4 list-disc space-y-2 pl-6 leading-relaxed text-brand-muted">
                <li>Datos de registro: nome, usuário, e-mail, fecha de nascimento y senha encriptada.</li>
                <li>Datos de perfil: biografía, avatar, conteúdo publicado e configuración.</li>
                <li>Datos de pago: gestionados por el gateway de pago, FANORA no almacena números de tarjeta.</li>
                <li>Datos técnicos: dirección IP, navegador y uso básico de la plataforma.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">3. Finalidad y base legal</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Tratamos tus datos conforme a la Ley General de Protección de Datos (LGPD) con las siguientes bases
                legales: ejecución del contrato (crear la cuenta, gestionar assinaturas), consentimiento expreso
                (tratamiento de datos opcionales) e obligación legal (verificação de mayoría de edad y prevención
                de fraude).
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">4. Consentimiento</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                FANORA mantiene un registro de consentimentos: cuándo se concedió, su finalidad y su versión.
                Puedes retirar tu consentimiento em cualquier momento, salvo cuando el tratamiento sea necesario
                para obligations legales o contractuales.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">5. Datos sensíveis y documentos de verificação</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Los documentos enviados para la verificação de identity (DNI, passaporte o similar) se tratan como
                datos sensibles. Se almacenan com acesso restringido durante el tiempo estrictamente necessário
                para la verificação y luego se eliminan, aplicando el principio de retenção mínima.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">6. Cookies</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Utilizamos cookies esenciales para el funcionamiento de la sesión y, en el futuro, cookies de
                preferencias. Consulte nuestra
                <a href="{{ route('cookies') }}" class="font-medium text-brand-magenta underline decoration-brand-magenta/40 underline-offset-2 hover:text-white hover:decoration-white">política de cookies</a>
                para más detalles.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">7. Comunicación con terceros</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Para procesar pagos utilizamos un gateway de pago que actúa como encargado de tratamiento. Solo se
                comparte la información necessária para completar la transacción; FANORA nunca recibe ni almacena
                datos completos de tarjetas.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">8. Derechos del titular</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">Como titular de los datos, tienes derecho a:</p>
            <ul class="mt-4 list-disc space-y-2 pl-6 leading-relaxed text-brand-muted">
                <li><strong class="font-semibold text-white">Acceso</strong>: solicitar una copia de tus datos.</li>
                <li><strong class="font-semibold text-white">Rectificación</strong>: corregir datos inexactos.</li>
                <li><strong class="font-semibold text-white">Supresión</strong>: solicitar la eliminación de tus datos.</li>
                <li><strong class="font-semibold text-white">Portabilidad</strong>: recibir tus datos em formato legível.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">9. Eliminación de cuenta</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Puedes solicitar la eliminación de tu cuenta desde los ajustes. Una vez eliminada, tu perfil y tu
                conteúdo público dejarán de ser visibles y tus datos pessoais se borrarán, salvo conservación
                obligatoria por ley.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">10. Seguridad</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Aplicamos medidas técnicas e organizativas para proteger tus datos: encriptación de senhas,
                conexión segura (HTTPS), controle de acesso basado en roles y auditorías periódicas.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">11. Conservación de datos</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Conservamos los datos solo durante el tempo necessário: datos de la cuenta mientras esté activa,
                datos de pago según la legislación fiscal y registros técnicos por um período limitado.
            </p>

            <h2 class="mt-10 text-xl font-bold tracking-tight text-white sm:text-2xl">12. Contacto</h2>
            <p class="mt-4 leading-relaxed text-brand-muted">
                Para ejercer tus derechos o resolver cualquier questão de privacidade, utiliza el
                <a href="{{ route('contact') }}" class="font-medium text-brand-magenta underline decoration-brand-magenta/40 underline-offset-2 hover:text-white hover:decoration-white">formulário de contato</a>.
            </p>
        </div>
    </div>
</x-layouts.app>