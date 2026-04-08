const CACHE = 'tm-v2';

const PRECACHE = [
  '/',
  '/index.php',
  '/shop.php',
  '/insurance.php',
  '/support.php',
  '/plans.php',
  '/about.php',
  '/business-conferences.php',
  '/healthcare-hospitals.php',
  '/schools.php',
  '/privacy.php',
  '/terms.php',
  '/assets/css/style.css',
  '/assets/images/tabletmasters-logo.png',
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
      Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Network first, fall back to cache
self.addEventListener('fetch', e => {
  if (e.request.method !== 'GET') return;
  e.respondWith(
    fetch(e.request)
      .then(res => {
        const clone = res.clone();
        caches.open(CACHE).then(c => c.put(e.request, clone));
        return res;
      })
      .catch(() => caches.match(e.request))
  );
});
