<div class="topbar" style="display:flex;align-items:center;padding:0 24px;background:#0f172a;color:white;position:sticky;top:0;z-index:100;height:60px;gap:12px;flex-wrap:nowrap;">
    <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:white;margin-right:12px;flex-shrink:0;">
        <span style="font-size:24px;">🍔</span>
        <span style="font-family:var(--font-display);font-size:17px;font-weight:800;">Food<span style="color:var(--primary);">Hub</span></span>
    </a>

    <button type="button" class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <nav class="nav" id="adminNav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>📊</span> Dashboard
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span>🛒</span> Orders
        </a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
            <span>🍔</span> Food
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span>📂</span> Categories
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <span>📣</span> Deals
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span>👥</span> Users
        </a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener" style="opacity:.7;">
            <span>🌐</span> Website
        </a>

        <div class="topbar-actions">
            {{-- Chat Button --}}
            <div class="chat-wrapper">
                <button type="button" id="chatToggleBtn" onclick="toggleChatDropdown()" class="chat-btn">
                    💬 Chat
                    <span id="chatTotalBadge" class="chat-badge"></span>
                </button>

                <div id="chatDropdown" class="chat-dropdown">
                    <div class="chat-dropdown-header">
                        <strong>💬 Chat</strong>
                        <button onclick="closeChatDropdown()" class="chat-close-btn">✕</button>
                    </div>
                    <div class="chat-tabs">
                        <button onclick="switchChatTab('editReqs')" id="tabEditReqs" class="chat-tab active-tab" data-color="#f59e0b">
                            ✏️ <span class="tab-label">Requests</span> <span id="tabEditReqsCount" class="tab-badge" style="display:none;background:#f59e0b;"></span>
                        </button>
                        <button onclick="switchChatTab('messages')" id="tabMessages" class="chat-tab" data-color="#3b82f6">
                            💬 <span class="tab-label">Messages</span> <span id="tabMessagesCount" class="tab-badge" style="display:none;background:#3b82f6;"></span>
                        </button>
                        <button onclick="switchChatTab('updates')" id="tabUpdates" class="chat-tab" data-color="#ef4444">
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
            <button type="button" onclick="toggleTheme()" class="topbar-icon-btn" title="Toggle Dark/Light Mode">
                <span class="theme-icon">🌙</span>
            </button>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-logout-btn">
                    ↪ Logout
                </button>
            </form>
        </div>
    </nav>
</div>

<style>
/* ========== TOPBAR NAV LINKS ========== */
.topbar .nav {
    display: flex;
    align-items: center;
    gap: 4px;
    flex: 1;
}
.topbar .nav a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 6px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
    white-space: nowrap;
}
.topbar .nav a:hover { color: white; background: rgba(255,255,255,.06); }
.topbar .nav a.active { color: white; background: rgba(255,107,0,.2); }

/* ========== TOPBAR ACTIONS (right side) ========== */
.topbar-actions {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.topbar-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    color: white;
    cursor: pointer;
    font-size: 16px;
    transition: all .2s;
}

.topbar-logout-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0 12px;
    height: 36px;
    border-radius: 8px;
    background: rgba(239,68,68,.1);
    border: 1px solid rgba(239,68,68,.2);
    color: #fca5a5;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all .2s;
}

/* ========== HAMBURGER BUTTON ========== */
.hamburger-btn {
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
    flex-shrink: 0;
}
.hamburger-btn span {
    display: block;
    width: 18px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s;
}
.hamburger-btn.active span:nth-child(1) { transform: rotate(45deg) translate(4px, 4px); }
.hamburger-btn.active span:nth-child(2) { opacity: 0; }
.hamburger-btn.active span:nth-child(3) { transform: rotate(-45deg) translate(4px, -4px); }

/* ========== CHAT BUTTON ========== */
.chat-wrapper { position: relative; }
.chat-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 14px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    color: white;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all .2s;
    position: relative;
}
.chat-badge {
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

/* ========== CHAT DROPDOWN ========== */
.chat-dropdown {
    display: none;
    position: absolute;
    top: 44px;
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
body.dark-theme .chat-dropdown {
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
    border-radius: 12px 12px 0 0;
}
.chat-close-btn {
    background: none;
    border: none;
    color: #c7d2fe;
    cursor: pointer;
    font-size: 20px;
    padding: 4px;
}
.chat-tabs {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    background: #f9fafb;
}
.chat-tab {
    flex: 1;
    padding: 10px 8px;
    border: none;
    background: transparent;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    color: #6b7280;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
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

/* ================= TABLET (max 1024px) ================= */
@media (max-width: 1024px) {
    .topbar {
        padding: 10px 16px;
        height: auto;
        min-height: 50px;
        flex-wrap: wrap;
    }

    .hamburger-btn { display: flex !important; }

    /* Hide nav by default on mobile */
    .topbar .nav {
        display: none;
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 4px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,.08);
        margin-top: 8px;
    }

    .topbar .nav.open { display: flex; }

    .topbar .nav a {
        width: 100%;
        text-align: left;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
    }

    /* Actions row on mobile */
    .topbar-actions {
        margin-left: 0;
        width: 100%;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,.08);
        justify-content: flex-end;
    }

    .container { padding: 20px 16px; }
    .header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .header h1 { font-size: 22px; }
    .cards, .stats { grid-template-columns: repeat(2, 1fr); }
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
    .order-filter { flex-direction: column; align-items: stretch; }
    .order-filter input, .order-filter select, .order-filter div { width: 100%; min-width: 0; }
    .table-wrapper, .table-card { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table { min-width: 700px; }
    .grid, .info-grid { grid-template-columns: 1fr; }
    .card-header { flex-direction: column; align-items: flex-start; gap: 10px; }

    /* Chat dropdown on mobile — bottom sheet */
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
    .chat-panel { max-height: 60vh; }
}

/* ================= MOBILE (max 768px) ================= */
@media (max-width: 768px) {
    .container { padding: 12px 10px; }
    h1 { font-size: 20px; }
    h2 { font-size: 17px; }
    .cards, .stats { grid-template-columns: repeat(2, 1fr); gap: 8px; }
    .stat-number { font-size: 20px; }
    .quick-actions { grid-template-columns: 1fr; }
    table { min-width: 600px; font-size: 13px; }
    th, td { padding: 10px 8px; }
    .table-header { flex-direction: column; align-items: flex-start; gap: 8px; }
    .btn, .action-btn { padding: 10px 14px; font-size: 13px; }
    .actions { gap: 6px; }
    .status, .order-type { padding: 4px 8px; font-size: 11px; }
    .bill-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .info-grid { grid-template-columns: 1fr; gap: 10px; }
    .total-row { font-size: 18px; padding: 14px; }
}

/* ================= SMALL MOBILE (max 480px) ================= */
@media (max-width: 480px) {
    .container { padding: 10px 8px; }
    .cards, .stats { grid-template-columns: 1fr; gap: 8px; }
    .stat-number { font-size: 18px; }
    h1 { font-size: 18px; }
    .header .btn, .header a { width: 100%; text-align: center; padding: 12px; }
    table { min-width: 550px; font-size: 12px; }
    .actions { flex-direction: column; width: 100%; }
    .actions .btn, .actions form { width: 100%; }
    .actions .btn { text-align: center; display: block; }
}

/* ================= PRINT ================= */
@media print {
    .hamburger-btn { display: none !important; }
    .topbar .nav { display: none !important; }
}
</style>

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

// Close nav on link click (mobile)
document.querySelectorAll('#adminNav a').forEach(function(link) {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 1024) {
            var nav = document.getElementById('adminNav');
            var btn = document.querySelector('.hamburger-btn');
            if (nav) nav.classList.remove('open');
            if (btn) btn.classList.remove('active');
        }
    });
});

// Close nav on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        var nav = document.getElementById('adminNav');
        var btn = document.querySelector('.hamburger-btn');
        if (nav) nav.classList.remove('open');
        if (btn) btn.classList.remove('active');
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
        setTimeout(function(){ dd.classList.add('open'); }, 10);
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
    msgs.forEach(function(m){html+='<a href="/admin/orders/'+m.order_id+'" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .15s;" onmouseover="this.style.background=\'#eff6ff\';" onmouseout="this.style.background=\'transparent\';"><div style="width:34px;height:34px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">💬</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#1d4ed8;margin-bottom:2px;">Order #'+m.order_id+'</div><div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(m.sender_name||'Customer')+': '+m.message+'</div></div></a>';});
    panel.innerHTML=html;
}

function renderChatUpdates(updates) {
    var panel=document.getElementById('chatUpdatesPanel'),badge=document.getElementById('tabUpdatesCount');
    if(updates.length===0){panel.innerHTML='<div class="chat-empty">No order updates</div>';badge.style.display='none';updateCount=0;return;}
    updateCount=updates.length;badge.textContent=updateCount;badge.style.display='inline';
    var html='';
    updates.forEach(function(o){html+='<a href="/admin/orders/'+o.id+'" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .15s;" onmouseover="this.style.background=\'#fffbeb\';" onmouseout="this.style.background=\'transparent\';"><div style="width:34px;height:34px;border-radius:8px;background:#fff7ed;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">🔄</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#b45309;margin-bottom:2px;">Order #'+o.id+' Updated</div><div style="font-size:12px;color:#6b7280;">'+(o.customer_name||'Customer')+' - Rs. '+parseFloat(o.total_amount).toFixed(2)+'</div></div></a>';});
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

// Close chat dropdown when clicking outside
document.addEventListener('click',function(e){
    var dd=document.getElementById('chatDropdown'),btn=document.getElementById('chatToggleBtn');
    if(dd&&dd.style.display==='block'&&!dd.contains(e.target)&&!btn.contains(e.target)){closeChatDropdown();}
});
</script>
