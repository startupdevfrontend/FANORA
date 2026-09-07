<x-layouts.app
    title="Contacto"
    description="Contáctanos: dudas, denúncias, soporte e pedidos de privacidade."
>
    <div class="mx-auto w-full max-w-3xl">
        <h1 class="text-3xl font-black tracking-tight">Contacto</h1>
        <p class="mt-3 text-brand-muted">
            Temos dúvidas, sugerências ou precisa reportar um problema? Envía un mensaje y te responderemos
            lo antes possível.
        </p>

        @if (session('status'))
            <div class="mt-4">
                <x-alert type="success">{{ session('status') }}</x-alert>
            </div>
        @endif

        <form method="post" action="{{ route('contact.submit') }}" class="mt-8 space-y-5">
            @csrf

            <x-input name="name" label="Nome" placeholder="Seu nome" required />
            <x-input name="email" label="E-mail" type="email" placeholder="seu@email.com" required />
            <x-input name="subject" label="Assunto" placeholder="Sobre qué nos escribes?" required />
            <x-textarea name="message" label="Mensagem" rows="6" placeholder="Escribe tu mensaje…" :maxlength="3000" required />

            <x-button type="submit" block>Enviar mensaje</x-button>
        </form>
    </div>
</x-layouts.app>