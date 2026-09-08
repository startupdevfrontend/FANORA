<x-layouts.app
    title="Contacto"
    description="Contáctanos: dudas, denúncias, soporte e pedidos de privacidade."
>
    <div class="mx-auto w-full max-w-prose">
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-magenta/15 via-transparent to-brand-purple/15 p-8 backdrop-blur-sm">
            <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-brand-magenta/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-brand-purple/20 blur-3xl"></div>
            <div class="relative">
                <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Contacto</h1>
                <p class="mt-3 text-sm text-brand-muted sm:text-base">
                    Temos dúvidas, sugerências ou precisa reportar um problema? Envía un mensaje y te responderemos
                    lo antes possível.
                </p>
            </div>
        </div>

        <div class="card mt-6 p-6 sm:p-8">
            @if (session('status'))
                <div class="mb-6">
                    <x-alert type="success">{{ session('status') }}</x-alert>
                </div>
            @endif

            <form method="post" action="{{ route('contact.submit') }}" class="space-y-5">
                @csrf

                <x-input name="name" label="Nome" placeholder="Seu nome" required />
                <x-input name="email" label="E-mail" type="email" placeholder="seu@email.com" required />
                <x-input name="subject" label="Assunto" placeholder="Sobre qué nos escribes?" required />
                <x-textarea name="message" label="Mensagem" rows="6" placeholder="Escribe tu mensaje…" :maxlength="3000" required />

                <x-button type="submit" block>Enviar mensaje</x-button>
            </form>
        </div>
    </div>
</x-layouts.app>
