@php
    $baseUrl = rtrim(config('app.url'), '/');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ([['/', 'hourly'], ['/explore', 'hourly'], ['/register', 'monthly'], ['/login', 'monthly'], ['/terms', 'yearly'], ['/privacy', 'yearly'], ['/content-policy', 'yearly'], ['/contact', 'yearly']] as [$path, $freq])
        <url>
            <loc>{{ $baseUrl }}{{ $path }}</loc>
            <changefreq>{{ $freq }}</changefreq>
        </url>
    @endforeach

    @foreach (\App\Models\CreatorProfile::query()
        ->where('verification_status', 'approved')
        ->whereHas('user', fn ($u) => $u->where('is_active', true))
        ->with('user')
        ->limit(1000)
        ->get() as $profile)

        @php($username = $profile->user->username)
        @if ($username)
            <url>
                <loc>{{ $baseUrl }}/creator/{{ $username }}</loc>
                <changefreq>daily</changefreq>
                <lastmod>{{ $profile->updated_at?->format('Y-m-d') }}</lastmod>
            </url>
        @endif
    @endforeach
</urlset>