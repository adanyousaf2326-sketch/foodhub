

<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <span class="logo-icon">🍔</span>
        <span class="brand-name">Food<span class="brand-accent">Hub</span> Hotel</span>
        <span class="badge-admin">Admin</span>
    </a>

    <nav class="nav" aria-label="Admin navigation">
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
                uD83DuDD14
                <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;font-size:10px;font-weight:bold;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid #1e293b;"></span>
            </button>
            <div id="notifDropdown" style="display:none;position:absolute;top:50px;right:0;width:360px;max-height:450px;background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.25);z-index:10001;overflow:hidden;border:1px solid #e5e7eb;">
                <div style="padding:14px 16px;background:#111827;color:white;display:flex;justify-content:space-between;align-items:center;">
                    <strong style="font-size:15px;">uD83DuDD14 Notifications</strong>
                    <button onclick="clearNotifications()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:12px;">Clear All</button>
                </div>
                <div id="notifList" style="max-height:380px;overflow-y:auto;">
                    <div style="text-align:center;padding:30px;color:#777;">uD83DuDD14 Koi notification nahi</div>
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
    // Theme Toggle Functionality
    function toggleTheme() {
        const body = document.body;
        const icon = document.querySelector('.theme-icon');
        
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

    // Apply saved theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const icon = document.querySelector('.theme-icon');
        
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            if (icon) icon.textContent = '☀️';
        }
    });
</script>
