<x-layouts.dashboard title="Perfil de creator — FANORA" :active="'profile'">

    <div class="mb-6 flex items-center justify-between">
        <x-page-title>Perfil de creator</x-page-title>
    </div>

    @php($creator = auth()->user())
    @php($profile = auth()->user()->creatorProfile ?? new \App\Models\CreatorProfile())
    @php($selectedCategories = collect($profile->categories ?? [])->pluck('id')->toArray())
    @php($priceList = collect([990, 1990, 2990])
    ->merge(collect($allowedPrices ?? []))
    ->unique()
    ->map(fn ($c) => ['value' => (string) $c, 'label' => 'R$ '.number_format($c / 100, 2, ',', '.')])
    ->values()
    ->all())

    @if ($profile->isPending() && $profile->id)
        <x-alert type="warning">
            Tu solicitud de verificación está <strong>pendiente</strong>. Configura tudo; a monetização se activa após a aprovação.
        </x-alert>
    @endif

    @unless ($profile->isApproved() && $profile->id)
        <x-alert type="warning" class="mt-4">
            Tu cuenta ainda não está verificada. <a class="underline" href="{{ route('creator.verification') }}">Solicita a verificação</a> para poder monetizar.
        </x-alert>
    @endunless

    <form method="POST" action="{{ route('creator.profile.update') }}" class="mt-6 grid gap-5 lg:grid-cols-3">
        @method('PUT')
        @csrf

        <div class="space-y-4 lg:col-span-2">
            <x-input name="display_name" label="Nome público" hint="Nome como aparecerá publicamente." :value="$profile->display_name ?? $creator->name" />
            <x-textarea name="tagline" rows="2" label="Frase de efeito" hint="Curta y enganadora." :value="$profile->tagline" />

            <x-select
                name="subscription_price_cents"
                label="Preço da assinatura"
                :options="$priceList"
                :value="$profile->subscription_price_cents"
                placeholder="Seleccionar preço"
            />

            <fieldset class="space-y-1.5">
                <legend class="mb-1.5 block text-sm font-medium text-white">Categorias</legend>
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($categories as $category)
                        @php($id = $category->slug)
                        <label class="flex items-center gap-2 rounded-xl border border-brand-border bg-brand-surface px-3 py-2 text-sm hover:bg-brand-card cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                @checked(in_array($category->id, $selectedCategories))
                                class="h-4 w-4 accent-brand-magenta">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="instagram" label="Instagram" placeholder="@usuario" :value="$profile->instagram" />
                <x-input name="tiktok" label="TikTok" placeholder="@usuario" :value="$profile->tiktok" />
                <x-input name="twitter" label="Twitter / X" placeholder="@usuario" :value="$profile->twitter" />
                <x-input name="youtube" label="YouTube" placeholder="@usuario" :value="$profile->youtube" />
            </div>
        </div>

        <div>
            <p class="text-sm font-medium">Tu perfil público</p>
            <p class="mt-1 text-sm text-brand-muted">URL do teu perfil:</p>
            <a href="{{ route('creator.show', $creator->username) }}" class="mt-1 block break-all text-sm text-brand-magenta">
                {{ route('creator.show', $creator->username) }}
            </a>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <x-button type="submit" variant="primary">Guardar perfil</x-button>

        @if ($profile->isPending() && $profile->id)
            <x-badge color="yellow">Pendente de aprovação</x-badge>
        @elseif ($profile->isApproved() && $profile->id)
            <x-badge color="green">Verificado — monetização activa</x-badge>
        @endif
    </div>
</form>

</x-layouts.dashboard>