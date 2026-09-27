{!! 'User-agent: *' !!}
Disallow: /admin
Disallow: /feed
Disallow: /subscriptions
Disallow: /notifications
Disallow: /profile
Disallow: /settings
Disallow: /blocks
Disallow: /creator/dashboard
Disallow: /creator/posts
Disallow: /creator/subscribers
Disallow: /creator/earnings
Disallow: /creator/verification

# Adult content classification notice for search engines.
User-agent: Googlebot
Disallow: /admin
Disallow: /feed
Disallow: /subscriptions
Disallow: /notifications
Disallow: /profile
Disallow: /settings
Disallow: /blocks

Sitemap: {{ url('/sitemap.xml') }}