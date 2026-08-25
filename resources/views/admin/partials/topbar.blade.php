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

        {{-- EDIT REQUESTS BUTTON --}}
        <div style="position:relative;display:inline-flex;">
            <button type="button" onclick="toggleEditReqDropdown()" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:white;cursor:pointer;font-size:18px;transition:all .25s ease;position:relative;" title="Edit Requests">
                ✏️
                <span id="editReqBadge" style="position:absolute;top:-4px;right:-4px;background:#f59e0b;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;padding:0 4px;border:2px solid #1e293b;"></span>
            </button>
            <div id="editReqDropdown" style="display:none;position:absolute;top:50px;right:0;width:380px;max-height:500px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.25);z-index:10001;overflow:hidden;border:1px solid #e5e7eb;">
                <div style="padding:14px 16px;background:#92400e;color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">✏️ Edit Requests</strong>
                    <button onclick="closeAllDropdowns()" style="background:none;border:none;color:#fde68a;cursor:pointer;font-size:18px;">✕</button>
                </div>
                <div id="editReqList" style="max-height:430px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#777;">No pending requests</div>
                </div>
            </div>
        </div>

        {{-- MESSAGES BUTTON --}}
        <div style="position:relative;display:inline-flex;">
            <button type="button" onclick="toggleMsgDropdown()" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:white;cursor:pointer;font-size:18px;transition:all .25s ease;position:relative;" title="Messages">
                💬
                <span id="msgBadge" style="position:absolute;top:-4px;right:-4px;background:#3b82f6;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;padding:0 4px;border:2px solid #1e293b;"></span>
            </button>
            <div id="msgDropdown" style="display:none;position:absolute;top:50px;right:0;width:380px;max-height:500px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.25);z-index:10001;overflow:hidden;border:1px solid #e5e7eb;">
                <div style="padding:14px 16px;background:#1e40af;color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">💬 Messages</strong>
                    <button onclick="closeAllDropdowns()" style="background:none;border:none;color:#bfdbfe;cursor:pointer;font-size:18px;">✕</button>
                </div>
                <div id="msgList" style="max-height:430px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#777;">No unread messages</div>
                </div>
            </div>
        </div>

        {{-- BELL NOTIFICATION BUTTON --}}
        <div style="position:relative;display:inline-flex;">
            <button type="button" onclick="toggleBellDropdown()" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:white;cursor:pointer;font-size:18px;transition:all .25s ease;position:relative;" title="Order Updates">
                🔔
                <span id="bellBadge" style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;padding:0 4px;border:2px solid #1e293b;"></span>
            </button>
            <div id="bellDropdown" style="display:none;position:absolute;top:50px;right:0;width:380px;max-height:500px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.25);z-index:10001;overflow:hidden;border:1px solid #e5e7eb;">
                <div style="padding:14px 16px;background:#991b1b;color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">🔔 Order Updates</strong>
                    <button onclick="closeAllDropdowns()" style="background:none;border:none;color:#fecaca;cursor:pointer;font-size:18px;">✕</button>
                </div>
                <div id="bellList" style="max-height:430px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#777;">No order updates</div>
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

    document.querySelectorAll('#adminNav a').forEach(function(link) {
        link.addEventListener('click', function() {
            var nav = document.getElementById('adminNav');
            var btn = document.querySelector('.hamburger-btn');
            if (nav) nav.classList.remove('open');
            if (btn) btn.classList.remove('active');
        });
    });

    document.addEventListener('click', function(e) {
        var nav = document.getElementById('adminNav');
        var btn = document.querySelector('.hamburger-btn');
        if (nav && btn && !nav.contains(e.target) && !btn.contains(e.target)) {
            nav.classList.remove('open');
            btn.classList.remove('active');
        }
    });

    // Theme Toggle
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

    document.addEventListener('DOMContentLoaded', function() {
        var savedTheme = localStorage.getItem('theme');
        var icon = document.querySelector('.theme-icon');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            if (icon) icon.textContent = '\u2600\uFE0F';
        }
    });

    // === DROPDOWN MANAGEMENT ===
    function closeAllDropdowns() {
        document.getElementById('editReqDropdown').style.display = 'none';
        document.getElementById('msgDropdown').style.display = 'none';
        document.getElementById('bellDropdown').style.display = 'none';
    }

    function toggleEditReqDropdown() {
        var dd = document.getElementById('editReqDropdown');
        var isOpen = dd.style.display === 'block';
        closeAllDropdowns();
        if (!isOpen) {
            dd.style.display = 'block';
            loadNotifications();
        }
    }

    function toggleMsgDropdown() {
        var dd = document.getElementById('msgDropdown');
        var isOpen = dd.style.display === 'block';
        closeAllDropdowns();
        if (!isOpen) {
            dd.style.display = 'block';
            loadNotifications();
        }
    }

    function toggleBellDropdown() {
        var dd = document.getElementById('bellDropdown');
        var isOpen = dd.style.display === 'block';
        closeAllDropdowns();
        if (!isOpen) {
            dd.style.display = 'block';
            loadNotifications();
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        var isInsideAny = false;
        ['editReqDropdown', 'msgDropdown', 'bellDropdown'].forEach(function(id) {
            var dd = document.getElementById(id);
            if (dd && dd.style.display === 'block' && dd.contains(e.target)) {
                isInsideAny = true;
            }
        });
        // Check if clicked on any toggle button
        if (!isInsideAny && !e.target.closest('[onclick*="toggleEditReq"], [onclick*="toggleMsg"], [onclick*="toggleBell"]')) {
            closeAllDropdowns();
        }
    });

    // === NOTIFICATIONS ===
    var editReqCount = 0;
    var msgCount = 0;
    var bellCount = 0;

    function renderEditRequests(reqs) {
        var list = document.getElementById('editReqList');
        var badge = document.getElementById('editReqBadge');

        if (reqs.length === 0) {
            list.innerHTML = '<div style="text-align:center;padding:30px;color:#777;">No pending requests</div>';
            badge.style.display = 'none';
            editReqCount = 0;
            return;
        }

        editReqCount = reqs.length;
        badge.textContent = editReqCount;
        badge.style.display = 'flex';

        var html = '';
        reqs.forEach(function(req) {
            var orderId = req.order_id;
            html += '<div style="padding:12px 16px;border-bottom:1px solid #f3f4f6;background:#fffbeb;">';
            html += '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">';
            html += '<strong style="color:#92400e;font-size:13px;">✏️ Order #' + orderId + '</strong>';
            html += '<span style="font-size:11px;color:#a16207;">' + timeAgo(req.created_at) + '</span>';
            html += '</div>';
            html += '<div style="font-size:12px;color:#78716c;margin-bottom:8px;">' + (req.customer_name || 'Customer') + ': ' + (req.message || 'Wants to edit order') + '</div>';
            html += '<div style="display:flex;gap:6px;">';
            html += '<form method="POST" action="/admin/orders/' + orderId + '/edit-requests/' + req.id + '/accept" style="flex:1;">';
            html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            html += '<button type="submit" style="width:100%;padding:7px;border:none;border-radius:6px;background:#16a34a;color:white;font-weight:bold;cursor:pointer;font-size:12px;">✅ Accept</button>';
            html += '</form>';
            html += '<form method="POST" action="/admin/orders/' + orderId + '/edit-requests/' + req.id + '/reject" style="flex:1;">';
            html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            html += '<input type="hidden" name="admin_response" value="Request rejected.">';
            html += '<button type="submit" style="width:100%;padding:7px;border:none;border-radius:6px;background:#dc2626;color:white;font-weight:bold;cursor:pointer;font-size:12px;">❌ Reject</button>';
            html += '</form>';
            html += '</div>';
            html += '<a href="/admin/orders/' + orderId + '" style="display:block;text-align:center;margin-top:6px;font-size:11px;color:#2563eb;text-decoration:none;">View Order →</a>';
            html += '</div>';
        });

        list.innerHTML = html;
    }

    function renderMessages(msgs) {
        var list = document.getElementById('msgList');
        var badge = document.getElementById('msgBadge');

        if (msgs.length === 0) {
            list.innerHTML = '<div style="text-align:center;padding:30px;color:#777;">No unread messages</div>';
            badge.style.display = 'none';
            msgCount = 0;
            return;
        }

        msgCount = msgs.length;
        badge.textContent = msgCount;
        badge.style.display = 'flex';

        var html = '';
        msgs.forEach(function(msg) {
            var orderId = msg.order_id;
            html += '<a href="/admin/orders/' + orderId + '" style="display:block;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .15s;" onmouseover="this.style.background=\'#eff6ff\';" onmouseout="this.style.background=\'white\';">';
            html += '<div style="display:flex;align-items:flex-start;gap:10px;">';
            html += '<div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">💬</div>';
            html += '<div style="flex:1;min-width:0;">';
            html += '<div style="font-weight:bold;font-size:13px;color:#1d4ed8;margin-bottom:2px;">Order #' + orderId + '</div>';
            html += '<div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (msg.sender_name || 'Customer') + ': ' + msg.message + '</div>';
            html += '</div>';
            html += '</div>';
            html += '</a>';
        });

        list.innerHTML = html;
    }

    function renderBell(updates) {
        var list = document.getElementById('bellList');
        var badge = document.getElementById('bellBadge');

        if (updates.length === 0) {
            list.innerHTML = '<div style="text-align:center;padding:30px;color:#777;">No order updates</div>';
            badge.style.display = 'none';
            bellCount = 0;
            return;
        }

        bellCount = updates.length;
        badge.textContent = bellCount;
        badge.style.display = 'flex';

        var html = '';
        updates.forEach(function(ord) {
            html += '<a href="/admin/orders/' + ord.id + '" style="display:block;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .15s;" onmouseover="this.style.background=\'#fffbeb\';" onmouseout="this.style.background=\'white\';">';
            html += '<div style="display:flex;align-items:flex-start;gap:10px;">';
            html += '<div style="width:36px;height:36px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">✏️</div>';
            html += '<div style="flex:1;min-width:0;">';
            html += '<div style="font-weight:bold;font-size:13px;color:#b45309;margin-bottom:2px;">Order #' + ord.id + ' Updated</div>';
            html += '<div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (ord.customer_name || 'Customer') + ' edited order - Rs. ' + parseFloat(ord.total_amount).toFixed(2) + '</div>';
            html += '</div>';
            html += '</div>';
            html += '</a>';
        });

        list.innerHTML = html;
    }

    function timeAgo(dateStr) {
        var now = new Date();
        var then = new Date(dateStr);
        var diff = Math.floor((now - then) / 1000);
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        return Math.floor(diff / 86400) + 'd ago';
    }

    function loadNotifications() {
        fetch('/admin/notifications-json', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            if (res.status === 401 || res.redirected) return null;
            return res.json();
        })
        .then(function(data) {
            if (!data) return;

            renderEditRequests(data.edit_requests || []);
            renderMessages(data.unread_messages || []);
            renderBell(data.recently_updated_orders || []);
        })
        .catch(function() {});
    }

    // Poll every 8 seconds
    setInterval(loadNotifications, 8000);
    loadNotifications();
</script>
