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
        <a href="{{ route('admin.kds') }}" class="{{ request()->routeIs('admin.kds') ? 'active' : '' }}">
            <span>👨‍🍳</span> Kitchen
        </a>
        <a href="{{ url('/') }}" class="website-btn" target="_blank" rel="noopener">
            <span>🌐</span> View Website
        </a>

        <button type="button" class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
            <span class="theme-icon">🌙</span>
        </button>

        {{-- COMBINED CHAT BUTTON --}}
        <div style="position:relative;display:inline-flex;">
            <button type="button" id="chatToggleBtn" onclick="toggleChatDropdown()" style="display:inline-flex;align-items:center;gap:6px;padding:0 14px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:white;cursor:pointer;font-size:14px;font-weight:600;transition:all .25s ease;position:relative;" title="Chat">
                💬 Chat
                <span id="chatTotalBadge" style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;padding:0 4px;border:2px solid #1e293b;"></span>
            </button>

            <div id="chatDropdown" style="display:none;position:absolute;top:50px;right:0;width:400px;max-height:550px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.25);z-index:10001;overflow:hidden;border:1px solid #e5e7eb;">
                {{-- Header --}}
                <div style="padding:12px 16px;background:linear-gradient(135deg,#1e40af,#7c3aed);color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">💬 Chat</strong>
                    <button onclick="closeChatDropdown()" style="background:none;border:none;color:#c7d2fe;cursor:pointer;font-size:18px;">✕</button>
                </div>

                {{-- Tabs --}}
                <div style="display:flex;border-bottom:2px solid #f3f4f6;background:#fafafa;">
                    <button onclick="switchChatTab('editReqs')" id="tabEditReqs" style="flex:1;padding:10px 8px;border:none;background:transparent;font-size:12px;font-weight:700;cursor:pointer;color:#92400e;border-bottom:2px solid #f59e0b;margin-bottom:-2px;display:flex;align-items:center;justify-content:center;gap:4px;">
                        ✏️ Edit Requests <span id="tabEditReqsCount" style="display:none;background:#f59e0b;color:white;font-size:9px;padding:1px 5px;border-radius:8px;font-weight:bold;"></span>
                    </button>
                    <button onclick="switchChatTab('messages')" id="tabMessages" style="flex:1;padding:10px 8px;border:none;background:transparent;font-size:12px;font-weight:700;cursor:pointer;color:#6b7280;border-bottom:2px solid transparent;margin-bottom:-2px;display:flex;align-items:center;justify-content:center;gap:4px;">
                        💬 Messages <span id="tabMessagesCount" style="display:none;background:#3b82f6;color:white;font-size:9px;padding:1px 5px;border-radius:8px;font-weight:bold;"></span>
                    </button>
                    <button onclick="switchChatTab('updates')" id="tabUpdates" style="flex:1;padding:10px 8px;border:none;background:transparent;font-size:12px;font-weight:700;cursor:pointer;color:#6b7280;border-bottom:2px solid transparent;margin-bottom:-2px;display:flex;align-items:center;justify-content:center;gap:4px;">
                        🔄 Updates <span id="tabUpdatesCount" style="display:none;background:#ef4444;color:white;font-size:9px;padding:1px 5px;border-radius:8px;font-weight:bold;"></span>
                    </button>
                </div>

                {{-- Tab Contents --}}
                <div id="chatEditReqsPanel" style="max-height:400px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#999;font-size:13px;">No pending requests</div>
                </div>
                <div id="chatMessagesPanel" style="max-height:400px;overflow-y:auto;display:none;">
                    <div style="text-align:center;padding:30px;color:#999;font-size:13px;">No unread messages</div>
                </div>
                <div id="chatUpdatesPanel" style="max-height:400px;overflow-y:auto;display:none;">
                    <div style="text-align:center;padding:30px;color:#999;font-size:13px;">No order updates</div>
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

    // === CHAT DROPDOWN ===
    var activeTab = 'editReqs';

    function closeChatDropdown() {
        document.getElementById('chatDropdown').style.display = 'none';
    }

    function toggleChatDropdown() {
        var dd = document.getElementById('chatDropdown');
        if (dd.style.display === 'block') {
            dd.style.display = 'none';
        } else {
            dd.style.display = 'block';
            loadChatNotifications();
        }
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        var dd = document.getElementById('chatDropdown');
        var btn = document.getElementById('chatToggleBtn');
        if (dd && dd.style.display === 'block' && !dd.contains(e.target) && !btn.contains(e.target)) {
            dd.style.display = 'none';
        }
    });

    // Tab switching
    function switchChatTab(tab) {
        activeTab = tab;
        var panels = { editReqs: 'chatEditReqsPanel', messages: 'chatMessagesPanel', updates: 'chatUpdatesPanel' };
        var tabs = { editReqs: 'tabEditReqs', messages: 'tabMessages', updates: 'tabUpdates' };
        var colors = { editReqs: '#f59e0b', messages: '#3b82f6', updates: '#ef4444' };
        var textColors = { editReqs: '#92400e', messages: '#1e40af', updates: '#991b1b' };

        Object.keys(panels).forEach(function(key) {
            document.getElementById(panels[key]).style.display = key === tab ? 'block' : 'none';
            var tabBtn = document.getElementById(tabs[key]);
            if (key === tab) {
                tabBtn.style.color = textColors[key];
                tabBtn.style.borderBottom = '2px solid ' + colors[key];
                tabBtn.style.background = 'white';
            } else {
                tabBtn.style.color = '#6b7280';
                tabBtn.style.borderBottom = '2px solid transparent';
                tabBtn.style.background = 'transparent';
            }
        });
    }

    // === RENDERING ===
    var editReqCount = 0;
    var msgCount = 0;
    var updateCount = 0;

    function renderChatEditRequests(reqs) {
        var panel = document.getElementById('chatEditReqsPanel');
        var countBadge = document.getElementById('tabEditReqsCount');

        if (reqs.length === 0) {
            panel.innerHTML = '<div style="text-align:center;padding:30px;color:#999;font-size:13px;">No pending requests</div>';
            countBadge.style.display = 'none';
            editReqCount = 0;
            return;
        }

        editReqCount = reqs.length;
        countBadge.textContent = editReqCount;
        countBadge.style.display = 'inline';

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

        panel.innerHTML = html;
    }

    function renderChatMessages(msgs) {
        var panel = document.getElementById('chatMessagesPanel');
        var countBadge = document.getElementById('tabMessagesCount');

        if (msgs.length === 0) {
            panel.innerHTML = '<div style="text-align:center;padding:30px;color:#999;font-size:13px;">No unread messages</div>';
            countBadge.style.display = 'none';
            msgCount = 0;
            return;
        }

        msgCount = msgs.length;
        countBadge.textContent = msgCount;
        countBadge.style.display = 'inline';

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

        panel.innerHTML = html;
    }

    function renderChatUpdates(updates) {
        var panel = document.getElementById('chatUpdatesPanel');
        var countBadge = document.getElementById('tabUpdatesCount');

        if (updates.length === 0) {
            panel.innerHTML = '<div style="text-align:center;padding:30px;color:#999;font-size:13px;">No order updates</div>';
            countBadge.style.display = 'none';
            updateCount = 0;
            return;
        }

        updateCount = updates.length;
        countBadge.textContent = updateCount;
        countBadge.style.display = 'inline';

        var html = '';
        updates.forEach(function(ord) {
            html += '<a href="/admin/orders/' + ord.id + '" style="display:block;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .15s;" onmouseover="this.style.background=\'#fffbeb\';" onmouseout="this.style.background=\'white\';">';
            html += '<div style="display:flex;align-items:flex-start;gap:10px;">';
            html += '<div style="width:36px;height:36px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">🔄</div>';
            html += '<div style="flex:1;min-width:0;">';
            html += '<div style="font-weight:bold;font-size:13px;color:#b45309;margin-bottom:2px;">Order #' + ord.id + ' Updated</div>';
            html += '<div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (ord.customer_name || 'Customer') + ' edited order - Rs. ' + parseFloat(ord.total_amount).toFixed(2) + '</div>';
            html += '</div>';
            html += '</div>';
            html += '</a>';
        });

        panel.innerHTML = html;
    }

    function updateChatTotalBadge() {
        var total = editReqCount + msgCount + updateCount;
        var badge = document.getElementById('chatTotalBadge');
        if (total > 0) {
            badge.textContent = total;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
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

    function loadChatNotifications() {
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

            renderChatEditRequests(data.edit_requests || []);
            renderChatMessages(data.unread_messages || []);
            renderChatUpdates(data.recently_updated_orders || []);
            updateChatTotalBadge();
        })
        .catch(function() {});
    }

    // Poll every 8 seconds
    setInterval(loadChatNotifications, 8000);
    loadChatNotifications();
</script>
