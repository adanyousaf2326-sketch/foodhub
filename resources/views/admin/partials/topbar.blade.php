{{-- ===== TOPBAR BAR (always visible) ===== }}
<div class="admin-topbar">
    {{-- Logo --}}
    <a href="{{ route('admin.dashboard') }}" class="topbar-logo">
        <span class="topbar-logo-icon">🍔</span>
        <span class="topbar-logo-text">Food<span class="topbar-logo-accent">Hub</span></span>
    </a>

    {{-- Desktop nav links (hidden on mobile) --}}
    <nav class="topbar-desktop-nav" id="desktopNav">
        <a href="{{ route('admin.dashboard') }}" class="topbar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span>
            <span class="nav-text">Dashboard</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="topbar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span>
            <span class="nav-text">Orders</span>
        </a>
        <a href="{{ route('admin.food.index') }}" class="topbar-nav-link {{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
            <span class="nav-icon">🍔</span>
            <span class="nav-text">Food</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="topbar-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="nav-icon">📂</span>
            <span class="nav-text">Categories</span>
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="topbar-nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <span class="nav-icon">📣</span>
            <span class="nav-text">Deals</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="topbar-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span>
            <span class="nav-text">Users</span>
        </a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener" class="topbar-nav-link website-link">
            <span class="nav-icon">🌐</span>
            <span class="nav-text">Website</span>
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
            <button type="button" id="chatToggleBtn" onclick="toggleChatDropdown()" class="topbar-action-btn chat-btn">
                💬 <span class="action-label hide-mobile-text">Chat</span>
                <span id="chatTotalBadge" class="topbar-badge"></span>
            </button>
            <div id="chatDropdown" class="topbar-chat-dropdown">
                <div class="chat-dropdown-header">
                    <div class="chat-dropdown-title">
                        <span class="chat-dropdown-icon">💬</span>
                        <strong>Chat Center</strong>
                    </div>
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
                    <div class="chat-empty">
                        <span class="chat-empty-icon">📭</span>
                        <p>No pending requests</p>
                    </div>
                </div>
                <div id="chatMessagesPanel" class="chat-panel" style="display:none;">
                    <div class="chat-empty">
                        <span class="chat-empty-icon">💬</span>
                        <p>No unread messages</p>
                    </div>
                </div>
                <div id="chatUpdatesPanel" class="chat-panel" style="display:none;">
                    <div class="chat-empty">
                        <span class="chat-empty-icon">🔄</span>
                        <p>No order updates</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Theme Toggle --}}
        <button type="button" onclick="toggleTheme()" class="topbar-action-btn theme-btn" title="Toggle Dark/Light Mode">
            <span class="theme-icon">🌙</span>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="topbar-logout-form">
            @csrf
            <button type="submit" class="topbar-action-btn topbar-logout" title="Logout">
                <span class="logout-icon">↪</span> <span class="hide-mobile-text">Logout</span>
            </button>
        </form>
    </div>
</div>

{{-- ===== MOBILE MENU (separate panel, slides down) ===== }}
<div class="admin-mobile-menu" id="adminMobileMenu">
    <div class="mobile-menu-header">
        <span class="mobile-menu-logo">🍔</span>
        <span class="mobile-menu-brand">Food<span style="color:#ff6b00;">Hub</span> Admin</span>
    </div>
    <div class="mobile-menu-links">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="mobile-link-icon">📊</span>
            <span class="mobile-link-text">Dashboard</span>
            <span class="mobile-link-arrow">›</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="mobile-link-icon">🛒</span>
            <span class="mobile-link-text">Orders</span>
            <span class="mobile-link-arrow">›</span>
        </a>
        <a href="{{ route('admin.food.index') }}" class="{{ request()->routeIs('admin.food.*') ? 'active' : '' }}">
            <span class="mobile-link-icon">🍔</span>
            <span class="mobile-link-text">Food</span>
            <span class="mobile-link-arrow">›</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="mobile-link-icon">📂</span>
            <span class="mobile-link-text">Categories</span>
            <span class="mobile-link-arrow">›</span>
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <span class="mobile-link-icon">📣</span>
            <span class="mobile-link-text">Deals</span>
            <span class="mobile-link-arrow">›</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="mobile-link-icon">👥</span>
            <span class="mobile-link-text">Users</span>
            <span class="mobile-link-arrow">›</span>
        </a>
        <a href="{{ url('/') }}" target="_blank" rel="noopener">
            <span class="mobile-link-icon">🌐</span>
            <span class="mobile-link-text">View Website</span>
            <span class="mobile-link-arrow">↗</span>
        </a>
    </div>
    <div class="mobile-menu-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mobile-logout-btn">
                ↪ Logout
            </button>
        </form>
    </div>
</div>

{{-- ===== BACKDROP ===== --}}
<div class="admin-mobile-backdrop" id="adminMobileBackdrop" onclick="closeMobileMenu()"></div>

<style>
/* ============================================
   TOPBAR — Compact Single-Line Design
   ============================================ */
.admin-topbar {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 0 16px;
    height: 52px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: white;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
}
.admin-topbar::-webkit-scrollbar { display: none; }

/* Logo */
.topbar-logo {
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: white;
    flex-shrink: 0;
    margin-right: 12px;
}
.topbar-logo-icon {
    font-size: 20px;
    transition: transform 0.3s ease;
}
.topbar-logo:hover .topbar-logo-icon {
    transform: rotate(-10deg) scale(1.1);
}
.topbar-logo-text {
    font-family: 'Poppins', Arial, sans-serif;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.5px;
}
.topbar-logo-accent {
    color: #ff6b00;
}

/* Desktop nav */
.topbar-desktop-nav {
    display: flex;
    align-items: center;
    gap: 1px;
    flex: 1;
    min-width: 0;
    flex-shrink: 1;
}
.topbar-nav-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 8px;
    border-radius: 6px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.2s ease;
    position: relative;
}
.topbar-nav-link .nav-icon {
    font-size: 12px;
}
.topbar-nav-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.08);
}
.topbar-nav-link.active {
    color: white;
    background: rgba(255, 107, 0, 0.2);
    box-shadow: inset 0 -2px 0 #ff6b00;
}
.topbar-nav-link.website-link {
    background: rgba(22, 163, 74, 0.15);
    color: #4ade80;
}

/* Right side */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
    margin-left: 8px;
}

/* Action buttons */
.topbar-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0 10px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e2e8f0;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
    position: relative;
    white-space: nowrap;
    flex-shrink: 0;
}
.topbar-action-btn:hover {
    background: rgba(255, 255, 255, 0.14);
}

/* Chat button */
.chat-btn:hover {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.3);
}

/* Theme button */
.theme-btn:hover {
    background: rgba(250, 204, 21, 0.15);
    border-color: rgba(250, 204, 21, 0.3);
}

/* Logout button */
.topbar-logout {
    background: rgba(239, 68, 68, 0.1) !important;
    border-color: rgba(239, 68, 68, 0.2) !important;
    color: #fca5a5 !important;
}
.topbar-logout:hover {
    background: rgba(239, 68, 68, 0.25) !important;
    color: #f87171 !important;
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
    font-size: 9px;
    font-weight: bold;
    min-width: 16px;
    height: 16px;
    border-radius: 8px;
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
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    cursor: pointer;
    padding: 9px;
    gap: 4px;
    flex-shrink: 0;
    transition: all 0.25s ease;
}
.topbar-hamburger:hover {
    background: rgba(255, 255, 255, 0.15);
}
.topbar-hamburger span {
    display: block;
    width: 18px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s ease;
    transform-origin: center;
}
.topbar-hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}
.topbar-hamburger.active span:nth-child(2) {
    opacity: 0;
    transform: scaleX(0);
}
.topbar-hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
}

/* ============================================
   MOBILE MENU — Beautiful Slide-Down Panel
   ============================================ */
.admin-mobile-menu {
    display: none;
    position: sticky;
    top: 50px;
    z-index: 999;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
    border-radius: 0 0 16px 16px;
    overflow: hidden;
    animation: slideDown 0.3s ease;
}
.admin-mobile-menu.show {
    display: block;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.mobile-menu-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.mobile-menu-logo {
    font-size: 24px;
}
.mobile-menu-brand {
    font-family: 'Poppins', Arial, sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: white;
}

.mobile-menu-links {
    padding: 8px;
}
.mobile-menu-links a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 12px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.25s ease;
}
.mobile-menu-links a:hover,
.mobile-menu-links a:active {
    color: white;
    background: rgba(255, 255, 255, 0.06);
}
.mobile-menu-links a.active {
    color: white;
    background: linear-gradient(135deg, rgba(255, 107, 0, 0.25) 0%, rgba(255, 107, 0, 0.1) 100%);
    border-left: 3px solid #ff6b00;
}
.mobile-link-icon {
    font-size: 18px;
    width: 24px;
    text-align: center;
}
.mobile-link-text {
    flex: 1;
}
.mobile-link-arrow {
    font-size: 18px;
    color: #475569;
    font-weight: bold;
}
.mobile-menu-links a.active .mobile-link-arrow {
    color: #ff6b00;
}

.mobile-menu-footer {
    padding: 12px 16px 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.mobile-logout-btn {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid rgba(239, 68, 68, 0.3);
    background: rgba(239, 68, 68, 0.1);
    color: #fca5a5;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}
.mobile-logout-btn:hover {
    background: rgba(239, 68, 68, 0.25);
    color: #f87171;
}

/* Backdrop */
.admin-mobile-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 998;
    backdrop-filter: blur(4px);
    animation: fadeIn 0.2s ease;
}
.admin-mobile-backdrop.show {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* ============================================
   CHAT DROPDOWN — Modern Card Design
   ============================================ */
.topbar-chat-wrap { position: relative; }

.topbar-chat-dropdown {
    display: none;
    position: absolute;
    top: 48px;
    right: 0;
    width: 420px;
    max-height: 520px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05);
    z-index: 10001;
    overflow: hidden;
    animation: dropdownSlide 0.25s ease;
}

@keyframes dropdownSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

body.dark-theme .topbar-chat-dropdown {
    background: #1e293b;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.chat-dropdown-header {
    padding: 16px 18px;
    background: linear-gradient(135deg, #1e40af, #7c3aed);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chat-dropdown-title {
    display: flex;
    align-items: center;
    gap: 8px;
}
.chat-dropdown-icon {
    font-size: 18px;
}
.chat-close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    cursor: pointer;
    font-size: 18px;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.chat-close-btn:hover {
    background: rgba(255, 255, 255, 0.25);
}

.chat-tabs-bar {
    display: flex;
    border-bottom: 2px solid #f3f4f6;
    background: #f9fafb;
}
body.dark-theme .chat-tabs-bar {
    background: #1a2332;
    border-color: #334155;
}
.chat-tab {
    flex: 1;
    padding: 12px 8px;
    border: none;
    background: transparent;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    color: #6b7280;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: all 0.25s ease;
}
.chat-tab:hover {
    color: #374151;
    background: rgba(0, 0, 0, 0.02);
}
body.dark-theme .chat-tab:hover {
    color: #e2e8f0;
    background: rgba(255, 255, 255, 0.03);
}
.chat-tab.active-tab {
    color: #f59e0b;
    border-bottom-color: #f59e0b;
}
.tab-badge {
    color: white;
    font-size: 9px;
    padding: 1px 6px;
    border-radius: 8px;
    font-weight: bold;
}
.chat-panel {
    max-height: 400px;
    overflow-y: auto;
}
.chat-panel::-webkit-scrollbar {
    width: 6px;
}
.chat-panel::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}
.chat-empty {
    text-align: center;
    padding: 35px 20px;
    color: #9ca3af;
    font-size: 13px;
}
.chat-empty-icon {
    font-size: 36px;
    display: block;
    margin-bottom: 8px;
}
.chat-empty p {
    margin: 0;
}

/* ============================================
   MOBILE (max 1024px)
   ============================================ */
@media (max-width: 1024px) {
    .topbar-desktop-nav { display: none !important; }
    .topbar-hamburger { display: flex !important; }
    .hide-mobile-text { display: none; }
    .admin-topbar { padding: 0 12px; height: 50px; }
    .topbar-logo { margin-right: auto; }
    .topbar-logo-text { font-size: 15px; }
    .topbar-action-btn { padding: 0 9px; height: 32px; font-size: 11px; }

    .topbar-chat-dropdown {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        top: auto !important;
        width: 100% !important;
        max-height: 78vh;
        border-radius: 20px 20px 0 0;
    }
    .chat-panel { max-height: 58vh; }
    .admin-mobile-menu { top: 50px; border-radius: 0; }
}

@media (max-width: 480px) {
    .admin-topbar { padding: 0 8px; height: 48px; }
    .topbar-logo-text { font-size: 13px; }
    .topbar-logo-icon { font-size: 18px; }
    .topbar-logo { gap: 4px; margin-right: 8px; }
    .topbar-action-btn { padding: 0 8px; height: 30px; font-size: 11px; gap: 3px; }
    .topbar-hamburger { width: 34px; height: 34px; }
    .action-label { display: none; }
    .nav-text { display: none; }
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
document.querySelectorAll('.admin-mobile-menu .mobile-menu-links a').forEach(function(link) {
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
    if (reqs.length===0) { panel.innerHTML='<div class="chat-empty"><span class="chat-empty-icon">📭</span><p>No pending requests</p></div>'; badge.style.display='none'; editReqCount=0; return; }
    editReqCount=reqs.length; badge.textContent=editReqCount; badge.style.display='inline';
    var html='';
    reqs.forEach(function(r) {
        html+='<div style="padding:14px 16px;border-bottom:1px solid #f3f4f6;background:#fffbeb;">';
        html+='<div style="display:flex;justify-content:space-between;margin-bottom:6px;"><strong style="color:#92400e;font-size:13px;">✏️ Order #'+r.order_id+'</strong><span style="font-size:11px;color:#a16207;">'+timeAgo(r.created_at)+'</span></div>';
        html+='<div style="font-size:12px;color:#78716c;margin-bottom:10px;">'+(r.customer_name||'Customer')+': '+(r.message||'Wants to edit')+'</div>';
        html+='<div style="display:flex;gap:8px;">';
        html+='<form method="POST" action="/admin/orders/'+r.order_id+'/edit-requests/'+r.id+'/accept" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" style="width:100%;padding:9px;border:none;border-radius:8px;background:#16a34a;color:white;font-weight:bold;cursor:pointer;font-size:12px;transition:all .2s;">✅ Accept</button></form>';
        html+='<form method="POST" action="/admin/orders/'+r.order_id+'/edit-requests/'+r.id+'/reject" style="flex:1;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="admin_response" value="Rejected."><button type="submit" style="width:100%;padding:9px;border:none;border-radius:8px;background:#dc2626;color:white;font-weight:bold;cursor:pointer;font-size:12px;transition:all .2s;">❌ Reject</button></form>';
        html+='</div></div>';
    });
    panel.innerHTML=html;
}

function renderChatMessages(msgs) {
    var panel=document.getElementById('chatMessagesPanel'), badge=document.getElementById('tabMessagesCount');
    if(msgs.length===0){panel.innerHTML='<div class="chat-empty"><span class="chat-empty-icon">💬</span><p>No unread messages</p></div>';badge.style.display='none';msgCount=0;return;}
    msgCount=msgs.length;badge.textContent=msgCount;badge.style.display='inline';
    var html='';
    msgs.forEach(function(m){html+='<a href="/admin/orders/'+m.order_id+'" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .2s;"><div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;">💬</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#1d4ed8;margin-bottom:3px;">Order #'+m.order_id+'</div><div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+(m.sender_name||'Customer')+': '+m.message+'</div></div></a>';});
    panel.innerHTML=html;
}

function renderChatUpdates(updates) {
    var panel=document.getElementById('chatUpdatesPanel'),badge=document.getElementById('tabUpdatesCount');
    if(updates.length===0){panel.innerHTML='<div class="chat-empty"><span class="chat-empty-icon">🔄</span><p>No order updates</p></div>';badge.style.display='none';updateCount=0;return;}
    updateCount=updates.length;badge.textContent=updateCount;badge.style.display='inline';
    var html='';
    updates.forEach(function(o){html+='<a href="/admin/orders/'+o.id+'" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f3f4f6;text-decoration:none;color:inherit;transition:background .2s;"><div style="width:38px;height:38px;border-radius:10px;background:#fff7ed;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;">🔄</div><div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;color:#b45309;margin-bottom:3px;">Order #'+o.id+' Updated</div><div style="font-size:12px;color:#6b7280;">'+(o.customer_name||'Customer')+' — Rs. '+parseFloat(o.total_amount).toLocaleString('en-PK',{minimumFractionDigits:2})+'</div></div></a>';});
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
