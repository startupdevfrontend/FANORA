<x-layouts.dashboard title="Verificação de creator — FANORA" :active="'verification'">

    <div class="mb-6">
        <x-page-title>Verificação de creator</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Para monetizar, precisas de uma conta verificada.</p>
    </div>

    @php($verification = $user->creatorVerifications()->latest()->first())

    @if ($verification && $verification->isApproved())
        <div class="card p-5">
            <div class="flex items-center gap-3">
                <x-badge color="green">Aprovado</x-badge>
                <span class="text-sm text-brand-muted">Verificado em {{ $verification->reviewed_at->format('d/m/Y') }}</span>
            </div>
            <p class="mt-2 text-sm text-brand-muted">Tu cuenta está verificada y puedes publicar contenido exclusivo.</p>
        </div>
    @elseif ($verification && $verification->status->value === 'rejected')
        <div class="card p-5">
            <x-badge color="red">Rechazado</x-badge>
            <p class="mt-2 text-sm text-brand-muted">Motivo: {{ $verification->rejected_reason }}</p>
            <p class="mt-1 text-sm text-brand-muted">Podes enviar una nova solicitação corrigindo o problema.</p>
        </div>
    @else
        <x-alert type="info" class="mb-4">
            A verificação permite monetizar. Os documentos são almacenados de forma segura y son eliminados tras 30 días de la revisão (LGPD).
        </x-alert>

        <form method="POST" action="{{ route('creator.verification.store') }}" enctype="multipart/form-data" class="card p-6">
            @csrf

            <x-select
                name="document_type"
                label="Tipo de documento"
                :options="[
                    ['value' => 'rg', 'label' => 'RG (Documento de identidade)'],
                    ['value' => 'cpf', 'label' => 'CPF'],
                    ['value' => 'cnpj', 'label' => 'CNPJ'],
                    ['value' => 'passport', 'label' => 'Passaporte'],
                    ['value' => 'other', 'label' => 'Outro'],
                ]"
                placeholder="Seleccionar tipo"
                required
            />

            <x-input name="document" type="file" label="Documento (PDF, JPG ou PNG — até 10 MB)" hint="Apenas para validación. Nunca compartilhamos." required />

            <x-textarea name="notes" rows="3" label="Observaciones (opcional)" placeholder="Explica algo que o moderador precise saber." />

            <x-button type="submit" variant="primary" class="mt-6">Enviar solicitação</x-button>
        </form>
    @endif
</x-layouts.dashboard>