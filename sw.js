const CACHE = 'tm-v8';

const PRECACHE = [
  '/',
  '/index.php',
  '/shop.php',
  '/insurance.php',
  '/support.php',
  '/plans.php',
  '/tablet-games.php',
  '/about.php',
  '/business-conferences.php',
  '/healthcare-hospitals.php',
  '/schools.php',
  '/privacy.php',
  '/terms.php',
  '/sms-terms.php',
  '/assets/css/style.css',
  '/assets/images/tabletmasters-logo-nav.jpg',
  '/assets/images/favicon-32.png',
  '/assets/images/apple-touch-icon.png',
];

self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE).then(c => c.addAll(PRECACHE))
  );
  self.skipWaiting();
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k.startsWith('tm-') && k !== CACHE).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Network first, fall back to cache
self.addEventListener('fetch', e => {
  if (e.request.method !== 'GET') return;
  const url = new URL(e.request.url);
  if (url.origin !== self.location.origin) return;
  // Never retain receipts, registrations, admin pages, or user-specific queries.
  const publicPage = PRECACHE.includes(url.pathname) && !url.search;
  const staticAsset = url.pathname.startsWith('/assets/')
    && /\.(?:css|js|png|jpe?g|gif|webp|avif|svg|ico|woff2?|ttf)$/i.test(url.pathname);
  if (!publicPage && !staticAsset) return;
  e.respondWith(
    fetch(e.request)
      .then(res => {
        if (res.ok && res.type === 'basic' && !/private|no-store/i.test(res.headers.get('cache-control') || '')) {
          const clone = res.clone();
          e.waitUntil(caches.open(CACHE).then(c => c.put(e.request, clone)));
        }
        return res;
      })
      .catch(async () => (await caches.match(e.request)) || new Response(
        'You are offline. Reconnect to load this page.',
        { status: 503, headers: { 'Content-Type': 'text/plain; charset=utf-8' } }
      ))
  );
});
