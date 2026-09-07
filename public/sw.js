// FANORA service worker — v2 (network-first)
//
// Safety rules:
//  - Only public, idempotent GET requests hit the cache.
//  - Private content URLs (media streams, signed URLs, /admin, /api, ...)
//    are NEVER cached.
//  - Navigation is network-first: the live site always wins; the cache only
//    acts as an OFFLINE fallback. A stale cache can therefore never break the
//    site (previous releases cached first-visit shells and could show
//    ERR_FAILED after rebuilds).
//  - On activation every non-current cache from older SW versions is purged.

const CACHE = 'fanora-v2';

const PRIVATE_PATTERNS = [/\/media\//, /[?&](token|signature)=/, /\/admin\//, /\/creator\/dashboard/, /\/subscriptions/, /\/settings/, /\/profile/, /\/notifications/, /\/feed/, /\/api\//];

self.addEventListener('install', function () {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches
            .keys()
            .then((names) =>
                Promise.all(names.map((name) => (name !== CACHE ? caches.delete(name) : Promise.resolve())))
            )
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', function (event) {
    const req = event.request;

    if (req.method !== 'GET') {
        return;
    }

    const url = new URL(req.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    // Never touch private/signed URLs or API calls.
    if (PRIVATE_PATTERNS.some((re) => re.test(req.url))) {
        return;
    }

    // Navigation: network-first, cached HTML as offline fallback.
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req)
                .then((response) => {
                    if (response.ok && (response.headers.get('content-type') || '').includes('text/html')) {
                        const clone = response.clone();
                        caches.open(CACHE).then((cache) => cache.put(req, clone));
                    }

                    return response;
                })
                .catch(() =>
                    caches
                        .open(CACHE)
                        .then((cache) => cache.match(req))
                        .then((cached) => cached || Response.error())
                )
        );

        return;
    }

    // Other GETs: network-first, cache successful responses, cache as fallback when offline.
    event.respondWith(
        fetch(req)
            .then((response) => {
                if (response.ok) {
                    const clone = response.clone();
                    caches.open(CACHE).then((cache) => cache.put(req, clone));
                }

                return response;
            })
            .catch(() => caches.open(CACHE).then((cache) => cache.match(req)))
    );
});

self.addEventListener('message', function (event) {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});