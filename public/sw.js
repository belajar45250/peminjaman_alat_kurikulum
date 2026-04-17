// public/sw.js

const CACHE_NAME     = 'pinjam-alat-v1';
const OFFLINE_URL    = '/offline';

// Asset yang di-cache saat install
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js',
];

// ── Install: Cache static assets ──
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// ── Activate: Hapus cache lama ──
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Fetch: Strategi cache ──
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET dan request ke API/admin
    if (request.method !== 'GET') return;
    if (url.pathname.startsWith('/admin')) return;
    if (url.pathname.startsWith('/login')) return;
    if (url.pathname.startsWith('/logout')) return;

    // Strategi untuk halaman HTML: Network First
    if (request.headers.get('Accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    // Cache halaman publik
                    if (
                        response.ok &&
                        (url.pathname === '/' || url.pathname.startsWith('/pinjam'))
                    ) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    }
                    return response;
                })
                .catch(() => {
                    // Jika offline, coba dari cache
                    return caches.match(request)
                        .then(cached => cached || caches.match(OFFLINE_URL));
                })
        );
        return;
    }

    // Strategi untuk asset statis (CSS, JS, font): Cache First
    if (
        request.destination === 'style'  ||
        request.destination === 'script' ||
        request.destination === 'font'   ||
        request.destination === 'image'
    ) {
        event.respondWith(
            caches.match(request).then(cached => {
                if (cached) return cached;
                return fetch(request).then(response => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    }
                    return response;
                });
            })
        );
        return;
    }
});

// ── Push Notification (opsional untuk masa depan) ──
self.addEventListener('push', event => {
    if (!event.data) return;
    const data = event.data.json();
    event.waitUntil(
        self.registration.showNotification(data.title || 'PinjamAlat', {
            body: data.body || '',
            icon: '/icons/icon-192x192.png',
            badge: '/icons/icon-72x72.png',
        })
    );
});