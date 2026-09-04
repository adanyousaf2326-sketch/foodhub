{{-- ===== ADMIN TOPBAR — Clean Responsive ===== --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<nav class="admin-topbar" id="adminTopbar">

    {{-- Logo --}}
    <a href="{{ route('admin.dashboard') }}" class="atb-logo">
        <i class="fas fa-utensils"></i> Food<span style="color:#fff;">Hub</span>
    </a>

    {{-- Desktop Nav Links --}}
    <div class="atb-links">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'atb-active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'atb-active' : '' }}"><i class="fas fa-receipt"></i> Orders</a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'atb-active' : '' }}"><i class="fas fa-hamburger"></i> Food</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'atb-active' : '' }}"><i class="fas fa-layer-group"></i> Categories</a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'atb-active' : '' }}"><i class="fas fa-bullhorn"></i> Deals</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'atb-active' : '' }}"><i class="fas fa-users"></i> Users</a>
        <a href="{{ route('admin.kitchen') }}" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-weight: 700;" class="{{ request()->routeIs('admin.kitchen') ? 'atb-active' : '' }}"><i class="fas fa-fire"></i> Kitchen</a>
        <a href="{{ route('admin.riders.index') }}" style="background: linear-gradient(135deg, #16a34a, #15803d); color: white; font-weight: 700;" class="{{ request()->routeIs('admin.riders.*') ? 'atb-active' : '' }}"><i class="fas fa-motorcycle"></i> Riders</a>
        <a href="{{ route('admin.riders.cash') }}" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #111; font-weight: 700;" class="{{ request()->routeIs('admin.riders.cash') ? 'atb-active' : '' }}"><i class="fas fa-money-bill-wave"></i> Cash</a>
        <a href="{{ route('admin.inventory') }}" class="{{ request()->routeIs('admin.inventory') ? 'atb-active' : '' }}"><i class="fas fa-boxes-stacked"></i> Stock</a>
        <a href="{{ route('admin.kitchen-printer') }}" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white; font-weight: 700;" class="{{ request()->routeIs('admin.kitchen-printer') ? 'atb-active' : '' }}"><i class="fas fa-print"></i> Printer</a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener" class="atb-website"><i class="fas fa-globe"></i> Website</a>
    </div>

    {{-- Right Side Controls --}}
    <div class="atb-actions">

        {{-- Chat Button --}}
        <div class="atb-chat-wrap" id="atbChatWrap">
            <button type="button" id="atbChatBtn" onclick="atbToggleChat()" class="atb-icon-btn" title="Chat">
                <i class="fas fa-comments"></i>
                <span id="atbChatBadge" class="atb-badge"></span>
            </button>
            {{-- Chat Dropdown --}}
            <div id="atbChatDropdown" class="atb-chat-dropdown">
                <div class="atb-cd-header">
                    <strong><i class="fas fa-comments"></i> Chat Center</strong>
                    <button onclick="atbCloseChat()" class="atb-cd-close">✕</button>
                </div>
                <div class="atb-cd-tabs">
                    <button onclick="atbSwitchTab('editReqs')" id="atbTabEditReqs" class="atb-cd-tab atb-tab-active">
                        <i class="fas fa-pen"></i> Requests <span id="atbBadgeEditReqs" class="atb-tab-badge" style="display:none;background:#f59e0b;"></span>
                    </button>
                    <button onclick="atbSwitchTab('messages')" id="atbTabMessages" class="atb-cd-tab">
                        <i class="fas fa-envelope"></i> Messages <span id="atbBadgeMessages" class="atb-tab-badge" style="display:none;background:#3b82f6;"></span>
                    </button>
                    <button onclick="atbSwitchTab('updates')" id="atbTabUpdates" class="atb-cd-tab">
                        <i class="fas fa-sync-alt"></i> Updates <span id="atbBadgeUpdates" class="atb-tab-badge" style="display:none;background:#ef4444;"></span>
                    </button>
                </div>
                <div id="atbPanelEditReqs" class="atb-cd-panel"><div class="atb-cd-empty"><i class="fas fa-inbox" style="font-size:20px;"></i><br>No pending requests</div></div>
                <div id="atbPanelMessages" class="atb-cd-panel" style="display:none;"><div class="atb-cd-empty"><i class="fas fa-envelope-open" style="font-size:20px;"></i><br>No unread messages</div></div>
                <div id="atbPanelUpdates" class="atb-cd-panel" style="display:none;"><div class="atb-cd-empty"><i class="fas fa-clock" style="font-size:20px;"></i><br>No order updates</div></div>
            </div>
        </div>

        {{-- Theme Toggle --}}
        <button type="button" onclick="toggleTheme()" class="atb-icon-btn" title="Toggle Theme">
            <span class="theme-icon"><i class="fas fa-moon"></i></span>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="atb-logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>

        {{-- Hamburger (mobile only) --}}
        <button type="button" class="atb-hamburger" id="atbHamburger" onclick="atbToggleMobileMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- ===== MOBILE SLIDE-DOWN MENU ===== --}}
<div class="atb-mobile-menu" id="atbMobileMenu">
    <div class="atb-mm-links">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'atb-mm-active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'atb-mm-active' : '' }}"><i class="fas fa-receipt"></i> Orders</a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'atb-mm-active' : '' }}"><i class="fas fa-hamburger"></i> Food</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'atb-mm-active' : '' }}"><i class="fas fa-layer-group"></i> Categories</a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'atb-mm-active' : '' }}"><i class="fas fa-bullhorn"></i> Deals</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'atb-mm-active' : '' }}"><i class="fas fa-users"></i> Users</a>
        <a href="{{ route('admin.kitchen') }}" style="color: #fca5a5;" class="{{ request()->routeIs('admin.kitchen') ? 'atb-mm-active' : '' }}"><i class="fas fa-fire"></i> Kitchen Display</a>
        <a href="{{ route('admin.riders.index') }}" style="color: #86efac;" class="{{ request()->routeIs('admin.riders.*') ? 'atb-mm-active' : '' }}"><i class="fas fa-motorcycle"></i> Manage Riders</a>
        <a href="{{ route('admin.riders.cash') }}" style="color: #fcd34d;" class="{{ request()->routeIs('admin.riders.cash') ? 'atb-mm-active' : '' }}"><i class="fas fa-money-bill-wave"></i> Cash Collection</a>
        <a href="{{ route('admin.inventory') }}" style="color: #86efac;" class="{{ request()->routeIs('admin.inventory') ? 'atb-mm-active' : '' }}"><i class="fas fa-boxes-stacked"></i> Inventory</a>
        <a href="{{ route('admin.kitchen-printer') }}" style="color: #c4b5fd;" class="{{ request()->routeIs('admin.kitchen-printer') ? 'atb-mm-active' : '' }}"><i class="fas fa-print"></i> Kitchen Printer</a>
        <a href="{{ route('admin.rider-map') }}" style="color: #93c5fd;" class="{{ request()->routeIs('admin.rider-map') ? 'atb-mm-active' : '' }}"><i class="fas fa-map-marked-alt"></i> Rider Map</a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener"><i class="fas fa-globe"></i> Website</a>
    </div>
    <div class="atb-mm-footer">
        <button type="button" onclick="toggleTheme()" class="atb-mm-btn">
            <span class="theme-icon"><i class="fas fa-moon"></i></span> Theme
        </button>
        <form method="POST" action="{{ route('logout') }}" style="flex:1;">
            @csrf
            <button type="submit" class="atb-mm-btn atb-mm-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</div>

{{-- Backdrop --}}
<div class="atb-backdrop" id="atbBackdrop" onclick="atbCloseMobileMenu()"></div>

<style>
/* ============================================================
   ADMIN TOPBAR — Fully Responsive
   ============================================================ */

.admin-topbar {
    background: #111827;
    padding: 0 5%;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    position: sticky;
    top: 0;
    z-index: 10000;
    box-shadow: 0 2px 12px rgba(0,0,0,.4);
}

/* Logo */
.atb-logo {
    color: #ff6b00;
    font-size: 22px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    letter-spacing: -0.5px;
}

/* Desktop nav links */
.atb-links {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
    justify-content: center;
    flex-wrap: nowrap;
    overflow: hidden;
}

.atb-links a {
    color: #cbd5e1;
    text-decoration: none;
    padding: 7px 10px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: background 0.2s, color 0.2s;
}

.atb-links a:hover { background: rgba(255,107,0,0.2); color: #ff6b00; }
.atb-links .atb-active { background: #ff6b00 !important; color: white !important; }
.atb-links .atb-website { background: #16a34a; color: white; }
.atb-links .atb-website:hover { background: #15803d; }

/* Right side actions */
.atb-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}

/* Icon buttons (chat, theme) */
.atb-icon-btn {
    position: relative;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    color: white;
    width: 38px;
    height: 38px;
    border-radius: 9px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    flex-shrink: 0;
}
.atb-icon-btn:hover { background: #ff6b00; border-color: #ff6b00; }

/* Notification badge */
.atb-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: #ef4444;
    color: white;
    font-size: 9px;
    font-weight: bold;
    min-width: 16px;
    height: 16px;
    border-radius: 8px;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    border: 2px solid #111827;
}

/* Logout button */
.atb-logout-btn {
    background: #dc2626;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    transition: background 0.2s;
    font-family: inherit;
}
.atb-logout-btn:hover { background: #b91c1c; }

/* ============================================================
   CHAT DROPDOWN
   ============================================================ */
.atb-chat-wrap { position: relative; }

.atb-chat-dropdown {
    display: none;
    position: absolute;
    top: 46px;
    right: 0;
    width: 380px;
    max-height: 520px;
    background: white;
    border-radius: 14px;
    box-shadow: 0 12px 50px rgba(0,0,0,.25);
    z-index: 10001;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    animation: atbDropIn 0.2s ease;
}

@keyframes atbDropIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.atb-cd-header {
    padding: 14px 16px;
    background: #111827;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}
.atb-cd-close {
    background: rgba(255,255,255,.15);
    border: none;
    color: white;
    cursor: pointer;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
.atb-cd-close:hover { background: rgba(255,255,255,.3); }

.atb-cd-tabs {
    display: flex;
    border-bottom: 2px solid #f3f4f6;
    background: #f9fafb;
}
.atb-cd-tab {
    flex: 1;
    padding: 10px 6px;
    border: none;
    background: transparent;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    color: #6b7280;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    transition: all 0.2s;
}
.atb-tab-active { color: #f59e0b !important; border-bottom-color: #f59e0b !important; }
.atb-tab-badge {
    color: white;
    font-size: 9px;
    padding: 1px 5px;
    border-radius: 8px;
    font-weight: bold;
}
.atb-cd-panel { max-height: 380px; overflow-y: auto; }
.atb-cd-empty { text-align: center; padding: 30px; color: #9ca3af; font-size: 13px; }

/* ============================================================
   HAMBURGER — mobile only
   ============================================================ */
.atb-hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 9px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    cursor: pointer;
    padding: 10px;
    gap: 5px;
    flex-shrink: 0;
    transition: background 0.2s;
}
.atb-hamburger:hover { background: rgba(255,255,255,.15); }
.atb-hamburger span {
    display: block;
    width: 20px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s;
}
.atb-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
.atb-hamburger.open span:nth-child(2) { opacity: 0; }
.atb-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

/* ============================================================
   MOBILE SLIDE-DOWN MENU
   ============================================================ */
.atb-mobile-menu {
    display: none;
    position: fixed;
    top: 60px;
    left: 0;
    right: 0;
    background: #1e293b;
    border-bottom: 1px solid rgba(255,255,255,.08);
    box-shadow: 0 12px 40px rgba(0,0,0,.4);
    z-index: 9999;
    max-height: calc(100vh - 60px);
    overflow-y: auto;
    animation: atbSlideDown 0.25s ease;
}
.atb-mobile-menu.show { display: block; }

@keyframes atbSlideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.atb-mm-links {
    padding: 8px 12px;
}
.atb-mm-links a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 14px;
    border-radius: 10px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.2s;
    margin-bottom: 2px;
}
.atb-mm-links a:hover, .atb-mm-links a:active {
    color: white;
    background: rgba(255,255,255,.07);
}
.atb-mm-links .atb-mm-active {
    color: white;
    background: #ff6b00;
}

.atb-mm-footer {
    display: flex;
    gap: 10px;
    padding: 12px;
    border-top: 1px solid rgba(255,255,255,.08);
}
.atb-mm-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 16px;
    border-radius: 9px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
    width: 100%;
}
.atb-mm-btn:hover { background: rgba(255,255,255,.14); }
.atb-mm-logout {
    background: rgba(220,38,38,.2) !important;
    border-color: rgba(220,38,38,.3) !important;
    color: #fca5a5 !important;
}
.atb-mm-logout:hover { background: rgba(220,38,38,.35) !important; }

/* Backdrop */
.atb-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 9998;
    backdrop-filter: blur(2px);
}
.atb-backdrop.show { display: block; }

/* ============================================================
   RESPONSIVE — Tablet / Mobile (<= 1024px)
   ============================================================ */
@media (max-width: 1024px) {
    .atb-links { display: none; }
    .atb-hamburger { display: flex; }

    /* Keep chat & theme on mobile topbar, hide logout (it's in mobile menu) */
    .atb-logout-btn { display: none; }

    .admin-topbar { padding: 0 4%; }
}

@media (max-width: 640px) {
    .atb-logo { font-size: 19px; }
    .admin-topbar { padding: 0 3%; height: 56px; }
    .atb-mobile-menu { top: 56px; }
    .atb-mm-links a { font-size: 14px; padding: 12px 12px; }
}

/* ============================================================
   CHAT DROPDOWN — mobile bottom sheet (<= 1024px)
   ============================================================ */
@media (max-width: 1024px) {
    .atb-chat-dropdown {
        position: fixed !important;
        bottom: 0 !important;
        top: auto !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        max-height: 75vh;
        border-radius: 18px 18px 0 0;
        z-index: 10002 !important;
    }
    .atb-cd-panel { max-height: 55vh; }
}
</style>

<script>
/* ---- Mobile Menu ---- */
function atbToggleMobileMenu() {
    var menu = document.getElementById('atbMobileMenu');
    var btn  = document.getElementById('atbHamburger');
    var bd   = document.getElementById('atbBackdrop');
    if (menu.classList.contains('show')) {
        atbCloseMobileMenu();
    } else {
        menu.classList.add('show');
        btn.classList.add('open');
        bd.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}
function atbCloseMobileMenu() {
    document.getElementById('atbMobileMenu').classList.remove('show');
    document.getElementById('atbHamburger').classList.remove('open');
    document.getElementById('atbBackdrop').classList.remove('show');
    document.body.style.overflow = '';
}
document.querySelectorAll('.atb-mm-links a').forEach(function(l) {
    l.addEventListener('click', atbCloseMobileMenu);
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { atbCloseMobileMenu(); atbCloseChat(); }
});

/* ---- Theme ---- */
function toggleTheme() {
    var body = document.body;
    var icons = document.querySelectorAll('.theme-icon');
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        icons.forEach(function(i) { i.innerHTML = '<i class="fas fa-moon"></i>'; });
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        icons.forEach(function(i) { i.innerHTML = '<i class="fas fa-sun"></i>'; });
        localStorage.setItem('theme', 'dark');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
        document.querySelectorAll('.theme-icon').forEach(function(i) { i.innerHTML = '<i class="fas fa-sun"></i>'; });
    }
});

/* ---- Chat ---- */
var atbActiveTab = 'editReqs';
var atbEditReqCount = 0, atbMsgCount = 0, atbUpdateCount = 0;

function atbCloseChat() {
    var dd = document.getElementById('atbChatDropdown');
    if (dd) dd.style.display = 'none';
}
function atbToggleChat() {
    var dd = document.getElementById('atbChatDropdown');
    if (dd.style.display === 'block') {
        atbCloseChat();
    } else {
        dd.style.display = 'block';
        atbLoadChatNotifications();
    }
}

function atbSwitchTab(tab) {
    atbActiveTab = tab;
    var panels = { editReqs:'atbPanelEditReqs', messages:'atbPanelMessages', updates:'atbPanelUpdates' };
    var tabs   = { editReqs:'atbTabEditReqs',   messages:'atbTabMessages',   updates:'atbTabUpdates' };
    var colors = { editReqs:'#f59e0b', messages:'#3b82f6', updates:'#ef4444' };
    Object.keys(panels).forEach(function(k) {
        document.getElementById(panels[k]).style.display = k === tab ? 'block' : 'none';
        var t = document.getElementById(tabs[k]);
        if (k === tab) {
            t.classList.add('atb-tab-active');
            t.style.color = colors[k];
            t.style.borderBottomColor = colors[k];
        } else {
            t.classList.remove('atb-tab-active');
            t.style.color = '';
            t.style.borderBottomColor = '';
        }
    });
}

function atbRenderEditRequests(reqs) {
    var panel = document.getElementById('atbPanelEditReqs');
    var badge = document.getElementById('atbBadgeEditReqs');
    if (!reqs.length) { panel.innerHTML = '<div class="atb-cd-empty"><i class="fas fa-inbox" style="font-size:20px;"></i><br>No pending requests</div>'; badge.style.display = 'none'; atbEditReqCount = 0; return; }
    atbEditReqCount = reqs.length; badge.textContent = atbEditReqCount; badge.style.display = 'inline';
    var html = '';
    reqs.forEach(function(r) {
        html += '<div style="padding:12px 16px;border-bottom:1px solid #f3f4f6;background:#fffbeb;">';
        html += '<div style="display:flex;justify-content:space-between;margin-bottom:6px;"><strong style="color:#92400e;font-size:13px;"><i class="fas fa-pen"></i> Order #' + r.order_id + '</strong><span style="font-size:11px;color:#a16207;">' + atbTimeAgo(r.created_at) + '</span></div>';
        html += '<div style="font-size:12px;color:#78716c;margin-bottom:8px;">' + (r.customer_name || 'Customer') + ': ' + (r.message || 'Wants to edit') + '</div>';
        html += '<div style="display:flex;gap:6px;">';
        html += '<form method="POST" action="/admin/orders/' + r.order_id + '/edit-requests/' + r.id + '/accept" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" style="width:100%;padding:8px;border:none;border-radius:6px;background:#16a34a;color:white;font-weight:bold;cursor:pointer;font-size:12px;"><i class="fas fa-check"></i> Accept</button></form>';
        html += '<form method="POST" action="/admin/orders/' + r.order_id + '/edit-requests/' + r.id + '/reject" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="admin_response" value="Rejected."><button type="submit" style="width:100%;padding:8px;border:none;border-radius:6px;background:#dc2626;color:white;font-weight:bold;cursor:pointer;font-size:12px;"><i class="fas fa-times"></i> Reject</button></form>';
        html += '</div></div>';
    });
    panel.innerHTML = html;
}

function atbRenderMessages(msgs) {
    var panel = document.getElementById('atbPanelMessages');
    var badge = document.getElementById('atbBadgeMessages');
    if (!msgs.length) { panel.innerHTML = '<div class="atb-cd-empty"><i class="fas fa-envelope-open" style="font-size:20px;"></i><br>No unread messages</div>'; badge.style.display = 'none'; atbMsgCount = 0; return; }
    atbMsgCount = msgs.length; badge.textContent = atbMsgCount; badge.style.display = 'inline';
    var html = '';
    msgs.forEach(function(m) {
        html += '<a href="/admin/orders/' + m.order_id + '" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;">';
        html += '<div style="width:34px;height:34px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:#3b82f6;"><i class="fas fa-comment-dots"></i></div>';
        html += '<div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#1d4ed8;margin-bottom:2px;">Order #' + m.order_id + '</div><div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (m.sender_name || 'Customer') + ': ' + m.message + '</div></div></a>';
    });
    panel.innerHTML = html;
}

function atbRenderUpdates(updates) {
    var panel = document.getElementById('atbPanelUpdates');
    var badge = document.getElementById('atbBadgeUpdates');
    if (!updates.length) { panel.innerHTML = '<div class="atb-cd-empty"><i class="fas fa-clock" style="font-size:20px;"></i><br>No order updates</div>'; badge.style.display = 'none'; atbUpdateCount = 0; return; }
    atbUpdateCount = updates.length; badge.textContent = atbUpdateCount; badge.style.display = 'inline';
    var html = '';
    updates.forEach(function(o) {
        html += '<a href="/admin/orders/' + o.id + '" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;">';
        html += '<div style="width:34px;height:34px;border-radius:8px;background:#fff7ed;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:#f59e0b;"><i class="fas fa-sync-alt"></i></div>';
        html += '<div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#b45309;margin-bottom:2px;">Order #' + o.id + ' Updated</div><div style="font-size:12px;color:#6b7280;">' + (o.customer_name || 'Customer') + ' — Rs. ' + parseFloat(o.total_amount).toLocaleString('en-PK', {minimumFractionDigits:2}) + '</div></div></a>';
    });
    panel.innerHTML = html;
}

function atbUpdateBadge() {
    var t = atbEditReqCount + atbMsgCount + atbUpdateCount;
    var b = document.getElementById('atbChatBadge');
    if (t > 0) { b.textContent = t; b.style.display = 'flex'; } else { b.style.display = 'none'; }
}

function atbTimeAgo(d) {
    var n = new Date(), t = new Date(d), s = Math.floor((n - t) / 1000);
    if (s < 60) return 'just now';
    if (s < 3600) return Math.floor(s/60) + 'm ago';
    if (s < 86400) return Math.floor(s/3600) + 'h ago';
    return Math.floor(s/86400) + 'd ago';
}

function atbLoadChatNotifications() {
    fetch('/admin/notifications-json', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { if (r.status === 401 || r.redirected) return null; return r.json(); })
    .then(function(d) {
        if (!d) return;
        atbRenderEditRequests(d.edit_requests || []);
        atbRenderMessages(d.unread_messages || []);
        atbRenderUpdates(d.recently_updated_orders || []);
        atbUpdateBadge();
    })
    .catch(function() {});
}
setInterval(atbLoadChatNotifications, 8000);
atbLoadChatNotifications();

/* Close chat on outside click */
document.addEventListener('click', function(e) {
    var dd  = document.getElementById('atbChatDropdown');
    var btn = document.getElementById('atbChatBtn');
    if (dd && dd.style.display === 'block' && !dd.contains(e.target) && btn && !btn.contains(e.target)) {
        atbCloseChat();
    }
});
</script>
