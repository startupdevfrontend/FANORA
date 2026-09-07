<x-layouts.app
    title="Configurações — FANORA"
    description="Gerencie sua conta, senha e dados pessoais."
>

    <div class="mb-6">
        <x-page-title>Configurações</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Administre sua conta, senha y datos pessoais.</p>
    </div>

    <div class="space-y-6 max-w-3xl">
        <div class="card p-6">
            <h2 class="text-lg font-semibold">Conta</h2>
            <p class="mt-1 text-sm text-brand-muted">Informações básicas da sua conta.</p>

            <dl class="mt-4 grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                <dt class="text-brand-muted">Nome</dt>
                <dd>{{ $user->name }}</dd>

                <dt class="text-brand-muted">Usuario</dt>
                <dd>{{ $user->username }}</dd>

                <dt class="text-brand-muted">E-mail</dt>
                <dd>{{ $user->email }}</dd>

                <dt class="text-brand-muted">Rol</dt>
                <dd>{{ $user->role->label() }}</dd>

                <dt class="text-brand-muted">Membro desde</dt>
                <dd>{{ $user->created_at->format('d/m/Y') }}</dd>
            </dl>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold">Cambiar senha</h2>
            <p class="mt-1 text-sm text-brand-muted">Usa uma senha segura e única.</p>

            <form method="POST" action="{{ route('settings.password') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
                @method('PUT')
                @csrf

                <x-input name="current_password" type="password" label="Senha atual" autocomplete="current-password" required />
                <x-input name="password" type="password" label="Nova senha" hint="Mínimo de 8 caracteres." required />
                <x-input name="password_confirmation" type="password" label="Confirmar senha" required />

                <x-button type="submit" variant="primary">Actualizar senha</x-button>
            </form>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold">Dados e privacidade</h2>

            <a href="{{ route('settings.export') }}" class="btn-outline sm mt-3">Exportar mis datos (LGPD)</a>
        </div>

        <div class="card border-red-500/30 p-6">
            <h2 class="text-lg font-semibold text-red-300">Zona de perigo</h2>
            <p class="mt-1 text-sm text-brand-muted">Apagar sua conta é permanente e não pode ser revertido. Conservaremos os dados necessários para cumprimento legal.</p>

            <x-alert type="error" class="mt-3">
                Digite <strong class="font-mono">DELETAR</strong> na caixa abaixo para confirmar.
            </x-alert>

            <form method="POST" action="{{ route('settings.delete') }}" class="mt-4 max-w-xs"
                  onsubmit="return confirm('Tens segurança? Esta ação não pode ser revertida.');">
                @csrf

                <x-input name="confirmation" label="Confirmar eliminação" placeholder="Digite DELETAR" required />

                <x-button type="submit" variant="danger" class="mt-4">Eliminar minha conta</x-button>
            </form>
        </div>
    </div>
</x-layouts.app>