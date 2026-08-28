// Service Worker for FoodHub Push Notifications

self.addEventListener('install', function(event) {
    console.log('Service Worker installed');
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    console.log('Service Worker activated');
    event.waitUntil(clients.claim());
});

// Listen for push notifications
self.addEventListener('push', function(event) {
    console.log('Push notification received:', event.data);
    
    var data = { title: 'FoodHub', body: 'You have a new notification', icon: '/images/foodhub-icon.png' };
    
    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data.body = event.data.text();
        }
    }
    
    var options = {
        body: data.body,
        icon: data.icon || '/images/foodhub-icon.png',
        badge: '/images/foodhub-badge.png',
        vibrate: [200, 100, 200],
        tag: data.tag || 'foodhub-notification',
        renotify: true,
        data: {
            url: data.url || '/',
            orderId: data.orderId || null,
            timestamp: Date.now()
        },
        actions: [
            { action: 'view', title: 'View Order', icon: '/images/foodhub-icon.png' },
            { action: 'dismiss', title: 'Dismiss', icon: '/images/foodhub-icon.png' }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Listen for notification clicks
self.addEventListener('notificationclick', function(event) {
    console.log('Notification clicked:', event.notification.tag);
    
    event.notification.close();
    
    var urlToOpen = event.notification.data.url || '/';
    
    if (event.action === 'view' && event.notification.data.orderId) {
        urlToOpen = '/admin/orders/' + event.notification.data.orderId;
    } else if (event.action === 'dismiss') {
        return;
    }
    
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            // Check if a window is already open
            for (var i = 0; i < clientList.length; i++) {
                var client = clientList[i];
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(urlToOpen);
                    return client.focus();
                }
            }
            // Open new window
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

// Listen for messages from the main page
self.addEventListener('message', function(event) {
    console.log('Service Worker received message:', event.data);
    
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
