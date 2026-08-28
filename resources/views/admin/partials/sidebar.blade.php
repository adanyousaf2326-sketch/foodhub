<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-logo">
        <span class="logo-icon">🍔</span>
        <div>
            <span class="brand">Food<span class="brand-accent">Hub</span></span>
            <span style="display:block;font-size:11px;color:#64748b;font-weight:400;">Admin Panel</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span> Orders
        </a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
            <span class="nav-icon">🍔</span> Food Items
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="nav-icon">📂</span> Categories
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <span class="nav-icon">📣</span> Deals
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Users
        </a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener">
            <span class="nav-icon">🌐</span> View Website
        </a>
    </nav>

    <div class="sidebar-bottom">
        {{-- Chat Button --}}
        <div style="position:relative;margin-bottom:10px;">
            <button type="button" id="chatToggleBtn" onclick="toggleChatDropdown()" style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:var(--radius-md);background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);color:white;cursor:pointer;font-size:14px;font-weight:500;transition:all .25s;position:relative;">
                💬 Chat
                <span id="chatTotalBadge" style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;padding:0 4px;border:2px solid #0f172a;"></span>
            </button>

            {{-- Chat Dropdown --}}
            <div id="chatDropdown" style="display:none;position:absolute;bottom:50px;left:0;width:380px;max-height:500px;background:#1e293b;border-radius:var(--radius-lg);box-shadow:0 10px 40px rgba(0,0,0,.5);z-index:10001;overflow:hidden;border:1px solid #334155;">
                <div style="padding:12px 16px;background:linear-gradient(135deg,#1e40af,#7c3aed);color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">💬 Chat</strong>
                    <button onclick="closeChatDropdown()" style="background:none;border:none;color:#c7d2fe;cursor:pointer;font-size:18px;">✕</button>
                </div>
                <div style="display:flex;border-bottom:2px solid #334155;background:#0f172a;">
                    <button onclick="switchChatTab('editReqs')" id="tabEditReqs" style="flex:1;padding:10px 8px;border:none;background:transparent;font-size:12px;font-weight:700;cursor:pointer;color:#f59e0b;border-bottom:2px solid #f59e0b;margin-bottom:-2px;display:flex;align-items:center;justify-content:center;gap:4px;">
                        ✏️ Requests <span id="tabEditReqsCount" style="display:none;background:#f59e0b;color:white;font-size:9px;padding:1px 5px;border-radius:8px;"></span>
                    </button>
                    <button onclick="switchChatTab('messages')" id="tabMessages" style="flex:1;padding:10px 8px;border:none;background:transparent;font-size:12px;font-weight:700;cursor:pointer;color:#64748b;border-bottom:2px solid transparent;margin-bottom:-2px;display:flex;align-items:center;justify-content:center;gap:4px;">
                        💬 Messages <span id="tabMessagesCount" style="display:none;background:#3b82f6;color:white;font-size:9px;padding:1px 5px;border-radius:8px;"></span>
                    </button>
                    <button onclick="switchChatTab('updates')" id="tabUpdates" style="flex:1;padding:10px 8px;border:none;background:transparent;font-size:12px;font-weight:700;cursor:pointer;color:#64748b;border-bottom:2px solid transparent;margin-bottom:-2px;display:flex;align-items:center;justify-content:center;gap:4px;">
                        🔄 Updates <span id="tabUpdatesCount" style="display:none;background:#ef4444;color:white;font-size:9px;padding:1px 5px;border-radius:8px;"></span>
                    </button>
                </div>
                <div id="chatEditReqsPanel" style="max-height:380px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#64748b;font-size:13px;">No pending requests</div>
                </div>
                <div id="chatMessagesPanel" style="max-height:380px;overflow-y:auto;display:none;">
                    <div style="text-align:center;padding:30px;color:#64748b;font-size:13px;">No unread messages</div>
                </div>
                <div id="chatUpdatesPanel" style="max-height:380px;overflow-y:auto;display:none;">
                    <div style="text-align:center;padding:30px;color:#64748b;font-size:13px;">No order updates</div>
                </div>
            </div>
        </div>

        {{-- Theme Toggle --}}
        <button type="button" onclick="toggleTheme()" style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:var(--radius-md);background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);color:white;cursor:pointer;font-size:14px;font-weight:500;transition:all .25s;">
            <span class="theme-icon">🌙</span> <span class="theme-label">Dark Mode</span>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:var(--radius-md);background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#fca5a5;cursor:pointer;font-size:14px;font-weight:500;transition:all .25s;">
                ↪ Logout
            </button>
        </form>
    </div>
</aside>

{{-- Mobile Overlay --}}
<div class="mobile-menu-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<script>
// Sidebar toggle (mobile)
function openSidebar() {
    document.getElementById('adminSidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('active');
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}

// Theme toggle
function toggleTheme() {
    var body = document.body;
    var icon = document.querySelector('.theme-icon');
    var label = document.querySelector('.theme-label');
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        icon.textContent = '🌙';
        if (label) label.textContent = 'Dark Mode';
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        icon.textContent = '☀️';
        if (label) label.textContent = 'Light Mode';
        localStorage.setItem('theme', 'dark');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var savedTheme = localStorage.getItem('theme');
    var icon = document.querySelector('.theme-icon');
    var label = document.querySelector('.theme-label');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if (icon) icon.textContent = '☀️';
        if (label) label.textContent = 'Light Mode';
    }
});

// Chat dropdown
var activeTab = 'editReqs';
function closeChatDropdown() { document.getElementById('chatDropdown').style.display = 'none'; }
function toggleChatDropdown() {
    var dd = document.getElementById('chatDropdown');
    if (dd.style.display === 'block') { dd.style.display = 'none'; }
    else { dd.style.display = 'block'; loadChatNotifications(); }
}
function switchChatTab(tab) {
    activeTab = tab;
    var panels = { editReqs:'chatEditReqsPanel', messages:'chatMessagesPanel', updates:'chatUpdatesPanel' };
    var tabs = { editReqs:'tabEditReqs', messages:'tabMessages', updates:'tabUpdates' };
    var colors = { editReqs:'#f59e0b', messages:'#3b82f6', updates:'#ef4444' };
    Object.keys(panels).forEach(function(k) {
        document.getElementById(panels[k]).style.display = k===tab?'block':'none';
        var t = document.getElementById(tabs[k]);
        t.style.color = k===tab?colors[k]:'#64748b';
        t.style.borderBottom = k===tab?'2px solid '+colors[k]:'2px solid transparent';
    });
}

var editReqCount=0, msgCount=0, updateCount=0;

function renderChatEditRequests(reqs) {
    var panel = document.getElementById('chatEditReqsPanel');
    var badge = document.getElementById('tabEditReqsCount');
    if (reqs.length===0) { panel.innerHTML='<div style="text-align:center;padding:30px;color:#64748b;font-size:13px;">No pending requests</div>'; badge.style.display='none'; editReqCount=0; return; }
    editReqCount=reqs.length; badge.textContent=editReqCount; badge.style.display='inline';
    var html='';
    reqs.forEach(function(r) {
        html+='<div style="padding:12px 16px;border-bottom:1px solid #334155;background:#1a1a2e;">';
        html+='<div style="display:flex;justify-content:space-between;margin-bottom:6px;"><strong style="color:#f59e0b;font-size:13px;">✏️ Order #'+r.order_id+'</strong><span style="font-size:11px;color:#94a3b8;">'+timeAgo(r.created_at)+'</span></div>';
        html+='<div style="font-size:12px;color:#94a3b8;margin-bottom:8px;">'+(r.customer_name||'Customer')+': '+(r.message||'Wants to edit')+'</div>';
        html+='<div style="display:flex;gap:6px;">';
        html+='<form method="POST" action="/admin/orders/'+r.order_id+'/edit-requests/'+r.id+'/accept" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" style="width:100%;padding:7px;border:none;border-radius:6px;background:#16a34a;color:white;font-weight:bold;cursor:pointer;font-size:12px;">✅ Accept</button></form>';
        html+='<form method="POST" action="/admin/orders/'+r.order_id+'/edit-requests/'+r.id+'/reject" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="admin_response" value="Rejected."><button type="submit" style="width:100%;padding:7px;border:none;border-radius:6px;background:#dc2626;color:white;font-weight:bold;cursor:pointer;font-size:12px;">❌ Reject</button></form>';
        html+='</div></div>';
    });
    panel.innerHTML=html;
}

function renderChatMessages(msgs) {
    var panel=document.getElementById('chatMessagesPanel'), badge=document.getElementById('tabMessagesCount');
    if(msgs.length===0){panel.innerHTML='<div style="text-align:center;padding:30px;color:#64748b;font-size:13px;">No unread messages</div>';badge.style.display='none';msgCount=0;return;}
    msgCount=msgs.length;badge.textContent=msgCount;badge.style.display='inline';
    var html='';
    msgs.forEach(function(m){html+='<a href="/admin/orders/'+m.order_id+'" style="display:block;padding:12px 16px;border-bottom:1px solid #334155;text-decoration:none;color:inherit;"><div style="display:flex;align-items:center;gap:10px;"><div style="width:32px;height:32px;border-radius:8px;background:#1e3a5f;display:flex;align-items:center;justify-content:center;font-size:16px;">💬</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#60a5fa;">Order #'+m.order_id+'</div><div style="font-size:12px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(m.sender_name||'Customer')+': '+m.message+'</div></div></div></a>';});
    panel.innerHTML=html;
}

function renderChatUpdates(updates) {
    var panel=document.getElementById('chatUpdatesPanel'),badge=document.getElementById('tabUpdatesCount');
    if(updates.length===0){panel.innerHTML='<div style="text-align:center;padding:30px;color:#64748b;font-size:13px;">No order updates</div>';badge.style.display='none';updateCount=0;return;}
    updateCount=updates.length;badge.textContent=updateCount;badge.style.display='inline';
    var html='';
    updates.forEach(function(o){html+='<a href="/admin/orders/'+o.id+'" style="display:block;padding:12px 16px;border-bottom:1px solid #334155;text-decoration:none;color:inherit;"><div style="display:flex;align-items:center;gap:10px;"><div style="width:32px;height:32px;border-radius:8px;background:#422006;display:flex;align-items:center;justify-content:center;font-size:16px;">🔄</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#fbbf24;">Order #'+o.id+' Updated</div><div style="font-size:12px;color:#94a3b8;">'+(o.customer_name||'Customer')+' - Rs. '+parseFloat(o.total_amount).toFixed(2)+'</div></div></div></a>';});
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

// Close chat dropdown on outside click
document.addEventListener('click',function(e){
    var dd=document.getElementById('chatDropdown'),btn=document.getElementById('chatToggleBtn');
    if(dd&&dd.style.display==='block'&&!dd.contains(e.target)&&!btn.contains(e.target)){dd.style.display='none';}
});
</script>
