<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <span class="logo-icon">🍔</span>
        <span class="brand-name">Food<span class="brand-accent">Hub</span> Hotel</span>
        <span class="badge-admin">Admin</span>
    </a>

    <button type="button" class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav class="nav" id="adminNav" aria-label="Admin navigation">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>🏠</span> Dashboard
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span>📂</span> Categories
        </a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
            <span>🍔</span> Food Items
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <span>📣</span> New Deals
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span>🛒</span> Orders
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span>👥</span> Users
        </a>
        <a href="{{ url('/') }}" class="website-btn" target="_blank" rel="noopener">
            <span>🌐</span> View Website
        </a>
        <button type="button" class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
            <span class="theme-icon">🌙</span>
        </button>
        <div style="position:relative;display:inline-flex;">
            <button type="button" id="notifToggle" onclick="toggleNotifDropdown()" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:white;cursor:pointer;font-size:18px;transition:all .25s ease;position:relative;">
                <span id="notifIcon">&#128276;</span>
                <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid #1e293b;"></span>
            </button>
            <div id="notifDropdown" style="display:none;position:absolute;top:50px;right:0;width:380px;max-height:500px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.25);z-index:10001;overflow:hidden;border:1px solid #e5e7eb;">
                <div style="padding:14px 16px;background:#111827;color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">&#128276; Notifications</strong>
                    <button onclick="clearNotifications()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:12px;">Clear All</button>
                </div>
                <div id="notifList" style="max-height:430px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#777;">No notifications yet</div>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <span>↪</span> Logout
            </button>
        </form>
    </nav>
</div>

<script>
    // Mobile menu toggle
    function toggleMobileMenu() {
        var nav = document.getElementById('adminNav');
        var btn = document.querySelector('.hamburger-btn');
        if (nav && btn) {
            nav.classList.toggle('open');
            btn.classList.toggle('active');
        }
    }

    // Close mobile menu when clicking a link
    document.querySelectorAll('#adminNav a').forEach(function(link) {
        link.addEventListener('click', function() {
            var nav = document.getElementById('adminNav');
            var btn = document.querySelector('.hamburger-btn');
            if (nav) nav.classList.remove('open');
            if (btn) btn.classList.remove('active');
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        var nav = document.getElementById('adminNav');
        var btn = document.querySelector('.hamburger-btn');
        if (nav && btn && !nav.contains(e.target) && !btn.contains(e.target)) {
            nav.classList.remove('open');
            btn.classList.remove('active');
        }
    });

    // Theme Toggle Functionality
    function toggleTheme() {
        var body = document.body;
        var icon = document.querySelector('.theme-icon');
        if (body.classList.contains('dark-theme')) {
            body.classList.remove('dark-theme');
            icon.textContent = '\u{1F319}';
            localStorage.setItem('theme', 'light');
        } else {
            body.classList.add('dark-theme');
            icon.textContent = '\u2600\uFE0F';
            localStorage.setItem('theme', 'dark');
        }
    }

    // Apply saved theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        var savedTheme = localStorage.getItem('theme');
        var icon = document.querySelector('.theme-icon');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            if (icon) icon.textContent = '\u2600\uFE0F';
        }
    });

    // Notification Dropdown
    var notifOpen = false;
    var notifItems = [];
    var knownNotifIds = {};

    function toggleNotifDropdown() {
        notifOpen = !notifOpen;
        var dd = document.getElementById('notifDropdown');
        dd.style.display = notifOpen ? 'block' : 'none';
        if (notifOpen) {
            loadNotifications();
        }
    }

    document.addEventListener('click', function(e) {
        var dd = document.getElementById('notifDropdown');
        var btn = document.getElementById('notifToggle');
        if (notifOpen && dd && !dd.contains(e.target) && !btn.contains(e.target)) {
            notifOpen = false;
            dd.style.display = 'none';
        }
    });

    function clearNotifications() {
        notifItems = [];
        knownNotifIds = {};
        renderNotifications();
        document.getElementById('notifBadge').style.display = 'none';
    }

    function renderNotifications() {
        var list = document.getElementById('notifList');
        var badge = document.getElementById('notifBadge');

        if (notifItems.length === 0) {
            list.innerHTML = '<div style="text-align:center;padding:30px;color:#777;">No notifications yet</div>';
            badge.style.display = 'none';
            return;
        }

        badge.textContent = notifItems.length;
        badge.style.display = 'flex';

        var html = '';
        notifItems.forEach(function(item) {
            var color = item.color || '#6b7280';
            var bg = item.bg || '#f3f4f6';
            var border = item.border || '#e5e7eb';
            var icon = item.icon || '';
            var title = item.title || '';
            var detail = item.detail || '';
            var link = item.link || '';
            html += '<a href="' + link + '" style="display:block;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .15s;" onmouseover="this.style.background=\'' + bg + '\';" onmouseout="this.style.background=\'white\';">';
            html += '<div style="display:flex;align-items:flex-start;gap:10px;">';
            html += '<div style="width:36px;height:36px;border-radius:10px;background:' + bg + ';display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">' + icon + '</div>';
            html += '<div style="flex:1;min-width:0;">';
            html += '<div style="font-weight:bold;font-size:13px;color:' + color + ';margin-bottom:2px;">' + title + '</div>';
            html += '<div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + detail + '</div>';
            html += '</div>';
            html += '</div>';
            html += '</a>';
        });

        list.innerHTML = html;
    }

    function loadNotifications() {
        fetch('/admin/notifications-json', {
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(res) {
            if (res.status === 401 || res.redirected) return null;
            return res.json();
        })
        .then(function(data) {
            if (!data) return;

            notifItems = [];

            // Unread customer messages
            if (data.unread_messages) {
                data.unread_messages.forEach(function(msg) {
                    var key = 'msg-' + msg.id;
                    if (!knownNotifIds[key]) {
                        knownNotifIds[key] = true;
                        var orderId = msg.order ? msg.order.id : msg.order_id;
                        notifItems.unshift({
                            icon: '\uD83D\uDCAC',
                            title: 'Message - Order #' + orderId,
                            detail: (msg.sender_name || 'Customer') + ': ' + msg.message,
                            color: '#1d4ed8',
                            bg: '#eff6ff',
                            border: '#bfdbfe',
                            link: '/admin/orders/' + orderId
                        });
                    }
                });
            }

            // Recently updated orders (customer edited their order)
            if (data.recently_updated_orders) {
                data.recently_updated_orders.forEach(function(ord) {
                    var key = 'upd-' + ord.id + '-' + ord.updated_at;
                    if (!knownNotifIds[key]) {
                        knownNotifIds[key] = true;
                        notifItems.unshift({
                            icon: '\u270F\uFE0F',
                            title: 'Order #' + ord.id + ' Updated',
                            detail: (ord.customer_name || 'Customer') + ' edited order - Rs. ' + parseFloat(ord.total_amount).toFixed(2),
                            color: '#b45309',
                            bg: '#fffbeb',
                            border: '#fde68a',
                            link: '/admin/orders/' + ord.id
                        });
                    }
                });
            }

            renderNotifications();
        })
        .catch(function() {});
    }

    // Poll notifications every 10 seconds
    setInterval(loadNotifications, 10000);
    loadNotifications();
</script>
