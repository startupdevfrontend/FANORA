<x-layouts.app
    title="Exportação de dados — FANORA"
    description="Dados pessoais registrados na sua conta FANORA."
>

    <div class="mb-6">
        <x-page-title>Exportação de dados (LGPD)</x-page-title>
        <p class="mt-1 text-sm text-brand-muted">Abaixo están os dados que retemos sobre sua conta. Você pode solicitar a eliminação a qualquer momento em Configurações.</p>
    </div>

    <div class="space-y-8 max-w-4xl">
        <div class="card p-6">
            <h2 class="text-lg font-semibold">Dados de cadastro</h2>
            <dl class="mt-3 grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                <dt class="text-brand-muted">Nome</dt>
                <dd>{{ $user->name }}</dd>

                <dt class="text-brand-muted">Usuario</dt>
                <dd>{{ $user->username }}</dd>

                <dt class="text-brand-muted">E-mail</dt>
                <dd>{{ $user->email }}</dd>

                <dt class="text-brand-muted">Data de nascimento</dt>
                <dd>{{ $user->birth_date?->format('d/m/Y') ?? '—' }}</dd>

                <dt class="text-brand-muted">Cuenta creada el</dt>
                <dd>{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="text-brand-muted">Última atualização da conta</dt>
                <dd>{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold">Consentimientos</h2>
            <p class="mt-1 text-sm text-brand-muted">Consentimentos registrados conforme a nossa Política de Privacidade.</p>

            <table class="table-basic mt-4">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Versão</th>
                        <th>Aceptado el</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user->consents as $consent)
                        <tr>
                            <td>{{ $consent->type->label() }}</td>
                            <td>{{ $consent->version }}</td>
                            <td>{{ $consent->approved_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $consent->source_ip ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-sm text-brand-muted">Sin consentimientos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold">Publicaciones ({{ $user->posts_count ?? $user->posts->count() }})</h2>
            <p class="mt-1 text-sm text-brand-muted">Conteúdo publicado por você. As mídias são armazenadas de forma protegida.</p>

            <table class="table-basic mt-4">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Conteúdo</th>
                        <th>Visibilidade</th>
                        <th>Mídia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user->posts as $post)
                        <tr>
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            <td class="max-w-sm truncate">{{ \Illuminate\Support\Str::limit($post->body, 80) }}</td>
                            <td><x-badge :color="$post->isExclusive() ? 'magenta' : 'neutral'">{{ $post->isExclusive() ? 'Exclusivo' : 'Público' }}</x-badge></td>
                            <td class="text-brand-muted">{{ $post->media_count ?? $post->media->count() }} arquivos</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-sm text-brand-muted">Sin publicaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-alert type="info">
            Solicita a exportação completa dos seus dados contactando-nos através de <a class="underline" href="{{ route('contact') }}">este formulário</a>. Cumprirás o teu dereito de portabilidade segundo o GDPR/LGPD.
        </x-alert>
    </div>
</x-layouts.app>