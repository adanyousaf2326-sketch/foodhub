

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
