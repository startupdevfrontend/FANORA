// FANORA service worker — v1
//
// Safety rules:
//  - Only public, idempotent GET requests are cached.
//  - Private content URLs (media streams, signed URLs) are NEVER cached.
//  - The app shell ('/', '/explore') works offline using cache-first.

const CACHE = 'fanora-v1';
const PRIVATE_PATTERNS = [/\/media\//, /[?&](token|signature)=/, /\/admin\//, /\/creator\/dashboard/, /\/subscriptions/, /\/settings/, /\/profile/, /\/notifications/, /\/feed/, /\/api\//];

self.addEventListener('install', function () {
    self.skipWaiting();
    self.caches.delete(CACHE);
    self.registration.update();
});

self.addEventListener('activate', function () {
    self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    const req = event.request;

    if (req.method !== 'GET') {
        return;
    }

    const url = new URL(req.url);

    // Never touch private/signed URLs or API calls.
    if (PRIVATE_PATTERNS.some((re) => re.test(req.url))) {
        return;
    }

    // Offline: serve cached app shell for navigation requests.
    if (req.mode === 'navigate') {
        event.respondWith(
            caches.open(CACHE).then((cache) =>
                cache.match(req).then((cached) =>
                    cached && !shouldRevalidate(url)
                        ? cached
                        : fetch(req)
                              .then((response) => {
                                  if (response.ok && (response.headers.get('content-type') || '').includes('text/html')) {
                                      cache.put(req, response.clone());
                                  }

                                  return response;
                              })
                              .catch(() => cached || Response.error())
                )
            )
        );

        return;
    }

    // Assets: network-first with stale fallback is fine, but keep it simple:
    // try network, fall back to cache when offline.
    event.respondWith(
        fetch(req)
            .then((response) => {
                if (response.ok) {
                    const cloned = response.clone();
                    caches.open(CACHE).then((cache) => cache.put(req, cloned));
                }

                return response;
            })
            .catch(() =>
                caches.open(CACHE).then((cache) => cache.match(req))
            )
    );
});

function shouldRevalidate(url) {
    // Revalidate always fresh shell except for the public landing pages.
    return !(url.path === '/' || url.path === '/explore' || url.path.startsWith('/explore?'));
}

self.addEventListener('message', function (event) {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});