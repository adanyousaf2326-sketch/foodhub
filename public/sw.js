const CACHE_NAME = 'foodhub-v1';
const urlsToCache = [
    '/',
    '/css/foodhub.css',
    '/css/modern-styles.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
];

// Install - cache essential assets
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
            .catch(function() {})
    );
    self.skipWaiting();
});

// Activate - clean old caches
self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(names) {
            return Promise.all(
                names.filter(function(name) { return name !== CACHE_NAME; })
                    .map(function(name) { return caches.delete(name); })
            );
        })
    );
    self.clients.claim();
});

// Fetch - network first, fallback to cache
self.addEventListener('fetch', function(event) {
    // Skip non-GET and API requests
    if (event.request.method !== 'GET') return;
    if (event.request.url.includes('/api/')) return;
    if (event.request.url.includes('/rider/')) return;

    event.respondWith(
        fetch(event.request)
            .then(function(response) {
                // Cache successful responses
                if (response.status === 200) {
                    var responseClone = response.clone();
                    caches.open(CACHE_NAME).then(function(cache) {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(function() {
                // Fallback to cache
                return caches.match(event.request)
                    .then(function(response) {
                        if (response) return response;
                        // Return offline page for navigation
                        if (event.request.mode === 'navigate') {
                            return caches.match('/');
                        }
                        return new Response('Offline', { status: 503 });
                    });
            })
    );
});

// Push notifications
self.addEventListener('push', function(event) {
    var data = event.data ? event.data.json() : {};
    var title = data.title || 'FoodHub';
    var body = data.body || 'You have a new notification!';
    var url = data.url || '/';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
            icon: '/icons/icon-192.png',
            badge: '/icons/icon-192.png',
            vibrate: [200, 100, 200],
            data: { url: url },
            actions: [
                { action: 'open', title: 'Open', icon: '/icons/icon-192.png' },
            ],
        })
    );
});

// Notification click
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var url = event.notification.data.url || '/';
    event.waitUntil(
        clients.openWindow(url)
    );
});
