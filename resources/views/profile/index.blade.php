<x-layouts.app
    title="Meu perfil — FANORA"
    description="Edite seu perfil público."
>

    <section class="mb-8 relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 shadow-2xl shadow-black/20">
        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-brand-magenta/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-brand-purple/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-magenta/30 bg-brand-magenta/10 px-3 py-1 text-xs font-semibold text-brand-magenta mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Perfil público
                </div>
                <h1 class="section-title text-4xl">Meu perfil</h1>
                <p class="mt-2 text-base text-brand-muted max-w-xl">Personalize seu perfil público, adicione uma foto de perfil, capa e informações sobre você para seus seguidores conhecerem melhor.</p>
            </div>
            <a href="{{ route('creator.dashboard') }}" class="btn-outline sm self-start sm:self-center whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Painel de creator
            </a>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="glass-card p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-r from-brand-magenta/20 via-brand-purple/20 to-transparent"></div>
                <div class="relative pt-6 flex flex-col items-center text-center">
                    <div class="mb-4">
                        <x-avatar :path="$user->profile?->avatar_path" :name="$user->name" size="xl" glow />
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-brand-muted">@ {{ $user->username }}</p>
                    @if (filled($user->profile?->bio))
                        <p class="mt-3 text-sm text-white/80 leading-relaxed">{{ $user->profile?->bio }}</p>
                    @endif
                    <div class="mt-5 w-full space-y-2 text-left">
                        @if (filled($user->profile?->location))
                            <div class="flex items-center gap-2 text-sm text-brand-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-magenta" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $user->profile?->location }}
                            </div>
                        @endif
                        @if (filled($user->profile?->website))
                            <div class="flex items-center gap-2 text-sm text-brand-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-purple" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                <span class="truncate">{{ $user->profile?->website }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="grid h-10 w-10 place-items-center rounded-xl bg-brand-magenta/15 text-brand-magenta">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Editar informações</h2>
                        <p class="text-sm text-brand-muted">Atualize seus dados pessoais e de perfil.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="grid w-full gap-4 sm:grid-cols-2">
                        <x-input name="name" label="Nome" required :value="$user->name" />
                        <x-input name="username" label="Usuário" hint="Letras minúsculas, sem espaços." required :value="$user->username" />

                        <div class="sm:col-span-2">
                            <x-textarea name="bio" rows="4" label="Biografia" hint="Conte um pouco sobre você." :value="$user->profile?->bio" />
                        </div>

                        <x-input name="location" label="Localização" placeholder="Cidade, país" :value="$user->profile?->location" />
                        <x-input name="website" label="Sitio web" type="url" placeholder="https://exemplo.com" :value="$user->profile?->website" />

                        <x-input name="avatar" type="file" label="Foto de perfil (PNG/JPG/WEBP, até 5 MB)" />
                        <x-input name="cover" type="file" label="Capa (PNG/JPG/WEBP, até 10 MB)" />
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <x-button type="submit" variant="primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Guardar cambios
                        </x-button>
                        @if (session('status'))
                            <x-badge color="green">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                {{ session('status') }}
                            </x-badge>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
