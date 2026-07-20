// Service worker sederhana untuk KKMB Connect PWA.
// Strategi: network-first untuk navigasi, cache-first untuk aset statis.
const CACHE = 'kkmb-connect-v1';
const ASSETS = ['/manifest.json'];

self.addEventListener('install', (e) => {
    e.waitUntil(caches.open(CACHE).then((c) => c.addAll(ASSETS)));
    self.skipWaiting();
});

self.addEventListener('activate', (e) => {
    e.waitUntil(caches.keys().then((keys) =>
        Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
    ));
    self.clients.claim();
});

self.addEventListener('fetch', (e) => {
    const req = e.request;
    if (req.method !== 'GET') return;
    e.respondWith(
        fetch(req).catch(() => caches.match(req).then((r) => r || caches.match('/manifest.json')))
    );
});
