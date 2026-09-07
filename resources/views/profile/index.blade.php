<x-layouts.app
    title="Meu perfil — FANORA"
    description="Edite seu perfil público."
>

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Meu perfil</x-page-title>
        <a href="{{ route('creator.dashboard') }}" class="btn-outline sm">Painel de creator</a>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="flex items-start gap-6">
                <x-avatar :path="$user->profile?->avatar_path" :name="$user->name" size="xl" />

                <div class="grid w-full gap-4 sm:grid-cols-2">
                    <x-input name="name" label="Nome" required :value="$user->name" />
                    <x-input name="username" label="Usuário" hint="Letras minúsculas, sem espaços." required :value="$user->username" />

                    <x-textarea name="bio" rows="4" label="Biografia" hint="Conte um pouco sobre você." :value="$user->profile?->bio" />
                    <x-input name="location" label="Localização" placeholder="Cidade, país" :value="$user->profile?->location" />
                    <x-input name="website" label="Sitio web" type="url" placeholder="https://exemplo.com" :value="$user->profile?->website" />

                    <x-input name="avatar" type="file" label="Foto de perfil (PNG/JPG/WEBP, até 5 MB)" />
                    <x-input name="cover" type="file" label="Capa (PNG/JPG/WEBP, até 10 MB)" />
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <x-button type="submit" variant="primary">Guardar cambios</x-button>
@if (session('status'))
                    <x-badge color="green">{{ session('status') }}</x-badge>
                @endif
            </div>
        </form>
    </div>
</x-layouts.app>