{{-- ===== TOPBAR BAR (always visible) ===== --}}
<div class="admin-topbar">
    {{-- Logo --}}
    <a href="{{ route('admin.dashboard') }}" class="topbar-logo">
        <span style="font-size:22px;">🍔</span>
        <span class="topbar-logo-text">Food<span class="topbar-logo-accent">Hub</span></span>
    </a>

    {{-- Desktop nav links (hidden on mobile) --}}
    <nav class="topbar-desktop-nav" id="desktopNav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            🛒 Orders
        </a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
            🍔 Food
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            📂 Categories
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            📣 Deals
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            👥 Users
        </a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener" class="topbar-nav-link website-link">
            🌐 Website
        </a>
    </nav>

    {{-- Right side: hamburger (mobile) + chat + theme + logout --}}
    <div class="topbar-right">
        {{-- Hamburger (mobile only) --}}
        <button type="button" class="topbar-hamburger" onclick="toggleMobileMenu()" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        {{-- Chat --}}
        <div class="topbar-chat-wrap">
            <button type="button" id="chatToggleBtn" onclick="toggleChatDropdown()" class="topbar-action-btn">
                💬 <span class="topbar-action-label hide-mobile-text">Chat</span>
                <span id="chatTotalBadge" class="topbar-badge"></span>
            </button>
            <div id="chatDropdown" class="topbar-chat-dropdown">
                <div class="chat-dropdown-header">
                    <strong>💬 Chat</strong>
                    <button onclick="closeChatDropdown()" class="chat-close-btn">✕</button>
                </div>
                <div class="chat-tabs-bar">
                    <button onclick="switchChatTab('editReqs')" id="tabEditReqs" class="chat-tab active-tab">
                        ✏️ <span class="tab-label">Requests</span> <span id="tabEditReqsCount" class="tab-badge" style="display:none;background:#f59e0b;"></span>
                    </button>
                    <button onclick="switchChatTab('messages')" id="tabMessages" class="chat-tab">
                        💬 <span class="tab-label">Messages</span> <span id="tabMessagesCount" class="tab-badge" style="display:none;background:#3b82f6;"></span>
                    </button>
                    <button onclick="switchChatTab('updates')" id="tabUpdates" class="chat-tab">
                        🔄 <span class="tab-label">Updates</span> <span id="tabUpdatesCount" class="tab-badge" style="display:none;background:#ef4444;"></span>
                    </button>
                </div>
                <div id="chatEditReqsPanel" class="chat-panel">
                    <div class="chat-empty">No pending requests</div>
                </div>
                <div id="chatMessagesPanel" class="chat-panel" style="display:none;">
                    <div class="chat-empty">No unread messages</div>
                </div>
                <div id="chatUpdatesPanel" class="chat-panel" style="display:none;">
                    <div class="chat-empty">No order updates</div>
                </div>
            </div>
        </div>

        {{-- Theme Toggle --}}
        <button type="button" onclick="toggleTheme()" class="topbar-action-btn" title="Toggle Dark/Light Mode">
            <span class="theme-icon">🌙</span>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="topbar-logout-form">
            @csrf
            <button type="submit" class="topbar-action-btn topbar-logout">
                ↪ <span class="hide-mobile-text">Logout</span>
            </button>
        </form>
    </div>
</div>

{{-- ===== MOBILE MENU (separate panel, slides down) ===== --}}
<div class="admin-mobile-menu" id="adminMobileMenu">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        📊 Dashboard
    </a>
    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        🛒 Orders
    </a>
    <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
        🍔 Food
    </a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        📂 Categories
    </a>
    <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
        📣 Deals
    </a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        👥 Users
    </a>
    <a href="{{ url('/') }}" target="_blank" rel="noopener">
        🌐 Website
    </a>
</div>

{{-- ===== BACKDROP ===== --}}
<div class="admin-mobile-backdrop" id="adminMobileBackdrop" onclick="closeMobileMenu()"></div>

<style>
/* ============================================
   TOPBAR BAR
   ============================================ */
.admin-topbar {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 20px;
    height: 56px;
    background: #0f172a;
    color: white;
    position: sticky;
    top: 0;
    z-index: 1000;
}

/* Logo */
.topbar-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: white;
    flex-shrink: 0;
}
.topbar-logo-text {
    font-family: var(--font-display, 'Poppins', sans-serif);
    font-size: 17px;
    font-weight: 800;
}
.topbar-logo-accent {
    color: #ff6b00;
}

/* Desktop nav */
.topbar-desktop-nav {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
}
.topbar-desktop-nav a {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 7px 12px;
    border-radius: 6px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.2s;
}
.topbar-desktop-nav a:hover {
    color: white;
    background: rgba(255,255,255,.06);
}
.topbar-desktop-nav a.active {
    color: white;
    background: rgba(255,107,0,.2);
}

/* Right side */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}

/* Action buttons */
.topbar-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0 12px;
    height: 34px;
    border-radius: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    color: white;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all .2s;
    position: relative;
    white-space: nowrap;
}
.topbar-action-btn:hover {
    background: rgba(255,255,255,.15);
}

.topbar-logout {
    background: rgba(239,68,68,.1);
    border-color: rgba(239,68,68,.2);
    color: #fca5a5;
}

.topbar-logout-form {
    margin: 0;
    display: inline-flex;
}

/* Badge */
.topbar-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: bold;
    min-width: 18px;
    height: 18px;
    border-radius: 9px;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid #0f172a;
}

/* Hamburger */
.topbar-hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    cursor: pointer;
    padding: 8px;
    gap: 4px;
}
.topbar-hamburger span {
    display: block;
    width: 18px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s;
}
.topbar-hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(4px, 4px); }
.topbar-hamburger.active span:nth-child(2) { opacity: 0; }
.topbar-hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(4px, -4px); }

/* ============================================
   MOBILE MENU (separate drop-down panel)
   ============================================ */
.admin-mobile-menu {
    display: none;
    position: sticky;
    top: 56px;
    z-index: 999;
    background: #1e293b;
    border-bottom: 1px solid rgba(255,255,255,.08);
    box-shadow: 0 10px 30px rgba(0,0,0,.3);
    padding: 8px;
}
.admin-mobile-menu.show {
    display: block;
}
.admin-mobile-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border-radius: 8px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.2s;
}
.admin-mobile-menu a:hover,
.admin-mobile-menu a:active {
    color: white;
    background: rgba(255,255,255,.06);
}
.admin-mobile-menu a.active {
    color: white;
    background: rgba(255,107,0,.25);
}

/* Backdrop */
.admin-mobile-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,.5);
    z-index: 998;
    backdrop-filter: blur(2px);
}
.admin-mobile-backdrop.show {
    display: block;
}

/* ============================================
   CHAT DROPDOWN
   ============================================ */
.topbar-chat-wrap { position: relative; }

.topbar-chat-dropdown {
    display: none;
    position: absolute;
    top: 42px;
    right: 0;
    width: 400px;
    max-height: 500px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,.2);
    z-index: 10001;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}
body.dark-theme .topbar-chat-dropdown {
    background: #1e293b;
    border-color: #334155;
}
.chat-dropdown-header {
    padding: 14px 16px;
    background: linear-gradient(135deg,#1e40af,#7c3aed);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chat-close-btn {
    background: none;
    border: none;
    color: #c7d2fe;
    cursor: pointer;
    font-size: 20px;
    padding: 4px;
}
.chat-tabs-bar {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    background: #f9fafb;
}
.chat-tab {
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
.chat-tab.active-tab { color: #f59e0b; border-bottom-color: #f59e0b; }
.tab-badge {
    color: white;
    font-size: 9px;
    padding: 1px 5px;
    border-radius: 8px;
    font-weight: bold;
}
.chat-panel { max-height: 380px; overflow-y: auto; }
.chat-empty { text-align: center; padding: 30px; color: #9ca3af; font-size: 13px; }

/* ============================================
   MOBILE (max 1024px)
   ============================================ */
@media (max-width: 1024px) {
    /* Hide desktop nav */
    .topbar-desktop-nav { display: none !important; }

    /* Show hamburger */
    .topbar-hamburger { display: flex !important; }

    /* Hide text labels on small buttons */
    .hide-mobile-text { display: none; }

    /* Make topbar fit mobile */
    .admin-topbar {
        padding: 0 12px;
        height: 52px;
    }

    /* Chat dropdown → bottom sheet */
    .topbar-chat-dropdown {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        top: auto !important;
        width: 100% !important;
        max-height: 75vh;
        border-radius: 16px 16px 0 0;
    }
    .chat-panel { max-height: 55vh; }
}

@media (max-width: 480px) {
    .admin-topbar {
        padding: 0 10px;
        height: 50px;
    }
    .topbar-logo-text { font-size: 15px; }
    .topbar-action-btn { padding: 0 10px; height: 32px; font-size: 12px; }
}
</style>

<script>
// Mobile menu
function toggleMobileMenu() {
    var menu = document.getElementById('adminMobileMenu');
    var btn = document.querySelector('.topbar-hamburger');
    var backdrop = document.getElementById('adminMobileBackdrop');
    if (menu.classList.contains('show')) {
        closeMobileMenu();
    } else {
        menu.classList.add('show');
        btn.classList.add('active');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}
function closeMobileMenu() {
    var menu = document.getElementById('adminMobileMenu');
    var btn = document.querySelector('.topbar-hamburger');
    var backdrop = document.getElementById('adminMobileBackdrop');
    menu.classList.remove('show');
    btn.classList.remove('active');
    backdrop.classList.remove('show');
    document.body.style.overflow = '';
}

// Close mobile menu on link click
document.querySelectorAll('.admin-mobile-menu a').forEach(function(link) {
    link.addEventListener('click', closeMobileMenu);
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMobileMenu();
        closeChatDropdown();
    }
});

// Theme toggle
function toggleTheme() {
    var body = document.body;
    var icon = document.querySelector('.theme-icon');
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        icon.textContent = '🌙';
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        icon.textContent = '☀️';
        localStorage.setItem('theme', 'dark');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var savedTheme = localStorage.getItem('theme');
    var icon = document.querySelector('.theme-icon');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if (icon) icon.textContent = '☀️';
    }
});

// Chat dropdown
var activeTab = 'editReqs';
function closeChatDropdown() {
    var dd = document.getElementById('chatDropdown');
    if (!dd) return;
    dd.classList.remove('open');
    dd.style.display = 'none';
}
function toggleChatDropdown() {
    var dd = document.getElementById('chatDropdown');
    if (dd.style.display === 'block') {
        closeChatDropdown();
    } else {
        dd.style.display = 'block';
        loadChatNotifications();
    }
}
function switchChatTab(tab) {
    activeTab = tab;
    var panels = { editReqs:'chatEditReqsPanel', messages:'chatMessagesPanel', updates:'chatUpdatesPanel' };
    var tabs = { editReqs:'tabEditReqs', messages:'tabMessages', updates:'tabUpdates' };
    var colors = { editReqs:'#f59e0b', messages:'#3b82f6', updates:'#ef4444' };
    Object.keys(panels).forEach(function(k) {
        document.getElementById(panels[k]).style.display = k===tab ? 'block' : 'none';
        var t = document.getElementById(tabs[k]);
        if (k === tab) { t.classList.add('active-tab'); t.style.color = colors[k]; t.style.borderBottomColor = colors[k]; }
        else { t.classList.remove('active-tab'); t.style.color = '#6b7280'; t.style.borderBottomColor = 'transparent'; }
    });
}

var editReqCount=0, msgCount=0, updateCount=0;

function renderChatEditRequests(reqs) {
    var panel = document.getElementById('chatEditReqsPanel');
    var badge = document.getElementById('tabEditReqsCount');
    if (reqs.length===0) { panel.innerHTML='<div class="chat-empty">No pending requests</div>'; badge.style.display='none'; editReqCount=0; return; }
    editReqCount=reqs.length; badge.textContent=editReqCount; badge.style.display='inline';
    var html='';
    reqs.forEach(function(r) {
        html+='<div style="padding:12px 16px;border-bottom:1px solid #f3f4f6;background:#fffbeb;">';
        html+='<div style="display:flex;justify-content:space-between;margin-bottom:6px;"><strong style="color:#92400e;font-size:13px;">✏️ Order #'+r.order_id+'</strong><span style="font-size:11px;color:#a16207;">'+timeAgo(r.created_at)+'</span></div>';
        html+='<div style="font-size:12px;color:#78716c;margin-bottom:8px;">'+(r.customer_name||'Customer')+': '+(r.message||'Wants to edit')+'</div>';
        html+='<div style="display:flex;gap:6px;">';
        html+='<form method="POST" action="/admin/orders/'+r.order_id+'/edit-requests/'+r.id+'/accept" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" style="width:100%;padding:8px;border:none;border-radius:6px;background:#16a34a;color:white;font-weight:bold;cursor:pointer;font-size:12px;">✅ Accept</button></form>';
        html+='<form method="POST" action="/admin/orders/'+r.order_id+'/edit-requests/'+r.id+'/reject" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="admin_response" value="Rejected."><button type="submit" style="width:100%;padding:8px;border:none;border-radius:6px;background:#dc2626;color:white;font-weight:bold;cursor:pointer;font-size:12px;">❌ Reject</button></form>';
        html+='</div></div>';
    });
    panel.innerHTML=html;
}

function renderChatMessages(msgs) {
    var panel=document.getElementById('chatMessagesPanel'), badge=document.getElementById('tabMessagesCount');
    if(msgs.length===0){panel.innerHTML='<div class="chat-empty">No unread messages</div>';badge.style.display='none';msgCount=0;return;}
    msgCount=msgs.length;badge.textContent=msgCount;badge.style.display='inline';
    var html='';
    msgs.forEach(function(m){html+='<a href="/admin/orders/'+m.order_id+'" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;"><div style="width:34px;height:34px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">💬</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#1d4ed8;margin-bottom:2px;">Order #'+m.order_id+'</div><div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(m.sender_name||'Customer')+': '+m.message+'</div></div></a>';});
    panel.innerHTML=html;
}

function renderChatUpdates(updates) {
    var panel=document.getElementById('chatUpdatesPanel'),badge=document.getElementById('tabUpdatesCount');
    if(updates.length===0){panel.innerHTML='<div class="chat-empty">No order updates</div>';badge.style.display='none';updateCount=0;return;}
    updateCount=updates.length;badge.textContent=updateCount;badge.style.display='inline';
    var html='';
    updates.forEach(function(o){html+='<a href="/admin/orders/'+o.id+'" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;"><div style="width:34px;height:34px;border-radius:8px;background:#fff7ed;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">🔄</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#b45309;margin-bottom:2px;">Order #'+o.id+' Updated</div><div style="font-size:12px;color:#6b7280;">'+(o.customer_name||'Customer')+' - Rs. '+parseFloat(o.total_amount).toFixed(2)+'</div></div></a>';});
    panel.innerHTML=html;
}

function updateChatTotalBadge() {
    var t=editReqCount+msgCount+updateCount, b=document.getElementById('chatTotalBadge');
    if(t>0){b.textContent=t;b.style.display='flex';}else{b.style.display='none';}
}

function timeAgo(d){var n=new Date(),t=new Date(d),s=Math.floor((n-t)/1000);if(s<60)return'just now';if(s<3600)return Math.floor(s/60)+'m ago';if(s<86400)return Math.floor(s/3600)+'h ago';return Math.floor(s/86400)+'d ago';}

function loadChatNotifications() {
    fetch('/admin/notifications-json',{credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){if(r.status===401||r.redirected)return null;return r.json();})
    .then(function(d){if(!d)return;renderChatEditRequests(d.edit_requests||[]);renderChatMessages(d.unread_messages||[]);renderChatUpdates(d.recently_updated_orders||[]);updateChatTotalBadge();})
    .catch(function(){});
}
setInterval(loadChatNotifications,8000);
loadChatNotifications();

document.addEventListener('click',function(e){
    var dd=document.getElementById('chatDropdown'),btn=document.getElementById('chatToggleBtn');
    if(dd&&dd.style.display==='block'&&!dd.contains(e.target)&&!btn.contains(e.target)){closeChatDropdown();}
});
</script>
