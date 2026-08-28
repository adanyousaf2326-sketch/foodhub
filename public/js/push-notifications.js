// Push Notification Client for FoodHub
// Uses Browser Notification API (no external library needed)

var PushNotification = {
    isSupported: 'Notification' in window,
    permission: 'default',
    
    init: function() {
        if (!this.isSupported) {
            console.log('Push notifications not supported');
            return false;
        }
        this.permission = Notification.permission;
        return true;
    },
    
    requestPermission: function() {
        var self = this;
        return new Promise(function(resolve) {
            if (!self.isSupported) {
                resolve('denied');
                return;
            }
            
            if (self.permission === 'granted') {
                resolve('granted');
                return;
            }
            
            Notification.requestPermission().then(function(permission) {
                self.permission = permission;
                resolve(permission);
            });
        });
    },
    
    showNotification: function(title, options) {
        if (this.permission !== 'granted') {
            console.log('Notification permission not granted');
            return;
        }
        
        var defaults = {
            body: '',
            icon: '/images/foodhub-icon.png',
            badge: '/images/foodhub-badge.png',
            vibrate: [200, 100, 200],
            tag: 'foodhub-notification',
            renotify: true,
            requireInteraction: false,
            silent: false
        };
        
        var options = Object.assign({}, defaults, options || {});
        
        try {
            var notification = new Notification(title, options);
            
            notification.onclick = function() {
                window.focus();
                if (options.url) {
                    window.location.href = options.url;
                }
                notification.close();
            };
            
            // Auto-close after 8 seconds
            setTimeout(function() {
                notification.close();
            }, 8000);
            
            return notification;
        } catch (e) {
            console.log('Notification error:', e);
        }
    },
    
    showOrderNotification: function(order) {
        var title = '🔔 New Order #' + order.id;
        var body = order.customer_name + ' — Rs. ' + Number(order.total_amount).toLocaleString('en-PK', {minimumFractionDigits: 2}) + ' (' + order.order_type + ')';
        
        return this.showNotification(title, {
            body: body,
            tag: 'order-' + order.id,
            url: '/admin/orders/' + order.id,
            requireInteraction: true
        });
    },
    
    showStatusNotification: function(order) {
        var statusMessages = {
            'Pending': '⏳ Order #' + order.id + ' is pending',
            'Preparing': '👨‍🍳 Order #' + order.id + ' is being prepared',
            'Out for Delivery': '🛵 Order #' + order.id + ' is on the way!',
            'Delivered': '✅ Order #' + order.id + ' has been delivered!',
            'Completed': '✅ Order #' + order.id + ' is completed!',
            'Cancelled': '❌ Order #' + order.id + ' has been cancelled'
        };
        
        var title = statusMessages[order.status] || '📋 Order #' + order.id + ' status updated';
        
        return this.showNotification(title, {
            tag: 'status-' + order.id,
            url: '/admin/orders/' + order.id
        });
    }
};

// Auto-init
document.addEventListener('DOMContentLoaded', function() {
    PushNotification.init();
});

// Export for global use
window.PushNotification = PushNotification;
