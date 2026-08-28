{{-- ===== TOPBAR — Same style as customer side ===== }}
<nav class="admin-topbar">
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <span>🍔</span>
        Food<span style="color:white;">Hub</span>
    </a>

    <div>
        {{-- Desktop nav --}}
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active-nav' : '' }}">📊 Dashboard</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active-nav' : '' }}">🛒 Orders</a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active-nav' : '' }}">🍔 Food</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active-nav' : '' }}">📂 Categories</a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active-nav' : '' }}">📣 Deals</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active-nav' : '' }}">👥 Users</a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener" style="background:#16a34a;">🌐 Website</a>

        {{-- Hamburger (mobile only) --}}
        <button type="button" class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        {{-- Chat --}}
        <div class="chat-wrap">
            <button type="button" id="chatToggleBtn" onclick="toggleChatDropdown()" class="nav-chat-btn">
                💬 Chat
                <span id="chatTotalBadge" class="nav-badge"></span>
            </button>
            <div id="chatDropdown" class="chat-dropdown">
                <div class="cd-header">
                    <strong>💬 Chat Center</strong>
                    <button onclick="closeChatDropdown()" class="cd-close">✕</button>
                </div>
                <div class="cd-tabs">
                    <button onclick="switchChatTab('editReqs')" id="tabEditReqs" class="cd-tab active-tab">✏️ Requests <span id="tabEditReqsCount" class="tab-badge" style="display:none;background:#f59e0b;"></span></button>
                    <button onclick="switchChatTab('messages')" id="tabMessages" class="cd-tab">💬 Messages <span id="tabMessagesCount" class="tab-badge" style="display:none;background:#3b82f6;"></span></button>
                    <button onclick="switchChatTab('updates')" id="tabUpdates" class="cd-tab">🔄 Updates <span id="tabUpdatesCount" class="tab-badge" style="display:none;background:#ef4444;"></span></button>
                </div>
                <div id="chatEditReqsPanel" class="cd-panel"><div class="cd-empty">📭 No pending requests</div></div>
                <div id="chatMessagesPanel" class="cd-panel" style="display:none;"><div class="cd-empty">💬 No unread messages</div></div>
                <div id="chatUpdatesPanel" class="cd-panel" style="display:none;"><div class="cd-empty">🔄 No order updates</div></div>
            </div>
        </div>

        {{-- Theme --}}
        <button type="button" onclick="toggleTheme()" class="theme-toggle-btn" title="Toggle Theme">
            <span class="theme-icon">🌙</span>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="margin:0;display:inline;">
            @csrf
            <button type="submit" class="logout-btn">↪ Logout</button>
        </form>
    </div>
</nav>

{{-- ===== MOBILE MENU ===== --}}
<div class="mobile-nav" id="adminMobileMenu">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active-nav' : '' }}">📊 Dashboard</a>
    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active-nav' : '' }}">🛒 Orders</a>
    <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active-nav' : '' }}">🍔 Food</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active-nav' : '' }}">📂 Categories</a>
    <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active-nav' : '' }}">📣 Deals</a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active-nav' : '' }}">👥 Users</a>
    <a href="{{ url('/') }}" target="_blank" rel="noopener">🌐 Website</a>
    <div class="mobile-nav-footer">
        <button type="button" onclick="toggleTheme()" class="theme-toggle-btn" style="border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.06);color:white;padding:10px 18px;border-radius:8px;font-size:14px;cursor:pointer;">
            <span class="theme-icon">🌙</span> Theme
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn" style="background:#dc2626;border-color:#dc2626;">↪ Logout</button>
        </form>
    </div>
</div>

<div class="mobile-backdrop" id="adminMobileBackdrop" onclick="closeMobileMenu()"></div>

<style>
/* ============================================
   TOPBAR — EXACTLY like customer side
   ============================================ */
.admin-topbar {
    background: #111827;
    padding: 14px 7%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 10000;
}

.admin-topbar .logo {
    color: #ff6b00;
    font-size: 26px;
    font-weight: bold;
    text-decoration: none;
    white-space: nowrap;
}

.admin-topbar > div:last-child {
    display: flex;
    align-items: center;
    gap: 4px;
}

.admin-topbar a, .nav-chat-btn, .theme-toggle-btn, .logout-btn {
    color: white;
    text-decoration: none;
    margin-left: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    transition: .2s ease;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    background: transparent;
    font-family: inherit;
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}

.admin-topbar a:hover, .nav-chat-btn:hover, .theme-toggle-btn:hover {
    background: #ff6b00;
}

.active-nav {
    background: #ff6b00 !important;
}

.logout-btn {
    background: #dc2626 !important;
}
.logout-btn:hover {
    background: #b91c1c !important;
}

/* Chat badge */
.nav-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: bold;
    min-width: 16px;
    height: 16px;
    border-radius: 8px;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid #111827;
}

/* Hamburger — hidden on desktop */
.hamburger-btn {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 10px;
    gap: 5px;
    margin-left: 10px;
}
.hamburger-btn span {
    display: block;
    width: 20px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s;
}
.hamburger-btn.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
.hamburger-btn.active span:nth-child(2) { opacity: 0; }
.hamburger-btn.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

/* ============================================
   MOBILE MENU — slides down
   ============================================ */
.mobile-nav {
    display: none;
    position: sticky;
    top: 56px;
    z-index: 9999;
    background: #1e293b;
    border-bottom: 1px solid rgba(255,255,255,.08);
    box-shadow: 0 10px 30px rgba(0,0,0,.3);
    padding: 10px;
}
.mobile-nav.show { display: block; }

.mobile-nav a {
    display: block;
    padding: 14px 16px;
    border-radius: 8px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    margin-left: 0;
    transition: all 0.2s;
}
.mobile-nav a:hover, .mobile-nav a:active {
    color: white;
    background: rgba(255,255,255,.06);
}
.mobile-nav a.active-nav {
    color: white;
    background: #ff6b00;
}

.mobile-nav-footer {
    display: flex;
    gap: 10px;
    padding: 10px 6px 6px;
    border-top: 1px solid rgba(255,255,255,.08);
    margin-top: 6px;
}
.mobile-nav-footer .logout-btn { margin-left: 0; flex: 1; }

/* Backdrop */
.mobile-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 9998;
    backdrop-filter: blur(2px);
}
.mobile-backdrop.show { display: block; }

/* ============================================
   CHAT DROPDOWN
   ============================================ */
.chat-wrap { position: relative; }

.chat-dropdown {
    display: none;
    position: absolute;
    top: 48px;
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

.cd-header {
    padding: 14px 16px;
    background: #111827;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cd-close {
    background: rgba(255,255,255,.15);
    border: none;
    color: white;
    cursor: pointer;
    font-size: 18px;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cd-close:hover { background: rgba(255,255,255,.25); }

.cd-tabs {
    display: flex;
    border-bottom: 2px solid #f3f4f6;
    background: #f9fafb;
}
.cd-tab {
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
.cd-tab.active-tab { color: #f59e0b; border-bottom-color: #f59e0b; }
.tab-badge {
    color: white;
    font-size: 9px;
    padding: 1px 5px;
    border-radius: 8px;
    font-weight: bold;
}
.cd-panel { max-height: 380px; overflow-y: auto; }
.cd-empty { text-align: center; padding: 30px; color: #9ca3af; font-size: 13px; }

/* ============================================
   MOBILE (max 1024px)
   ============================================ */
@media (max-width: 1024px) {
    .admin-topbar > div:last-child > a,
    .admin-topbar > div:last-child > .chat-wrap,
    .admin-topbar > div:last-child > .theme-toggle-btn,
    .admin-topbar > div:last-child > form {
        display: none !important;
    }
    .hamburger-btn { display: flex !important; }

    .admin-topbar { padding: 12px 5%; }
    .admin-topbar .logo { font-size: 22px; }

    /* Chat dropdown → bottom sheet */
    .chat-dropdown {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        top: auto !important;
        width: 100% !important;
        max-height: 75vh;
        border-radius: 16px 16px 0 0;
    }
    .cd-panel { max-height: 55vh; }
}

@media (max-width: 480px) {
    .admin-topbar { padding: 10px 4%; }
    .admin-topbar .logo { font-size: 20px; }
    .mobile-nav a { font-size: 14px; padding: 12px 14px; }
}
</style>

<script>
// Mobile menu
function toggleMobileMenu() {
    var menu = document.getElementById('adminMobileMenu');
    var btn = document.querySelector('.hamburger-btn');
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
    document.getElementById('adminMobileMenu').classList.remove('show');
    document.querySelector('.hamburger-btn').classList.remove('active');
    document.getElementById('adminMobileBackdrop').classList.remove('show');
    document.body.style.overflow = '';
}
document.querySelectorAll('.mobile-nav a').forEach(function(l){ l.addEventListener('click', closeMobileMenu); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeMobileMenu(); closeChatDropdown(); } });

// Theme
function toggleTheme() {
    var body = document.body;
    var icons = document.querySelectorAll('.theme-icon');
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        icons.forEach(function(i){ i.textContent = '🌙'; });
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        icons.forEach(function(i){ i.textContent = '☀️'; });
        localStorage.setItem('theme', 'dark');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
        document.querySelectorAll('.theme-icon').forEach(function(i){ i.textContent = '☀️'; });
    }
});

// Chat
var activeTab = 'editReqs';
function closeChatDropdown() { var dd = document.getElementById('chatDropdown'); if(dd) dd.style.display = 'none'; }
function toggleChatDropdown() {
    var dd = document.getElementById('chatDropdown');
    if (dd.style.display === 'block') { closeChatDropdown(); }
    else { dd.style.display = 'block'; loadChatNotifications(); }
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
    if (reqs.length===0) { panel.innerHTML='<div class="cd-empty">📭 No pending requests</div>'; badge.style.display='none'; editReqCount=0; return; }
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
    if(msgs.length===0){panel.innerHTML='<div class="cd-empty">💬 No unread messages</div>';badge.style.display='none';msgCount=0;return;}
    msgCount=msgs.length;badge.textContent=msgCount;badge.style.display='inline';
    var html='';
    msgs.forEach(function(m){html+='<a href="/admin/orders/'+m.order_id+'" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;"><div style="width:34px;height:34px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">💬</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#1d4ed8;margin-bottom:2px;">Order #'+m.order_id+'</div><div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(m.sender_name||'Customer')+': '+m.message+'</div></div></a>';});
    panel.innerHTML=html;
}

function renderChatUpdates(updates) {
    var panel=document.getElementById('chatUpdatesPanel'),badge=document.getElementById('tabUpdatesCount');
    if(updates.length===0){panel.innerHTML='<div class="cd-empty">🔄 No order updates</div>';badge.style.display='none';updateCount=0;return;}
    updateCount=updates.length;badge.textContent=updateCount;badge.style.display='inline';
    var html='';
    updates.forEach(function(o){html+='<a href="/admin/orders/'+o.id+'" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;"><div style="width:34px;height:34px;border-radius:8px;background:#fff7ed;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">🔄</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#b45309;margin-bottom:2px;">Order #'+o.id+' Updated</div><div style="font-size:12px;color:#6b7280;">'+(o.customer_name||'Customer')+' — Rs. '+parseFloat(o.total_amount).toLocaleString('en-PK',{minimumFractionDigits:2})+'</div></div></a>';});
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
