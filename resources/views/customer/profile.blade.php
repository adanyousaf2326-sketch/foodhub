<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f6f9; }
        .profile-container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        .profile-header { background: linear-gradient(135deg, #ff6b00, #ff8c33); border-radius: 20px; padding: 30px; color: white; margin-bottom: 25px; display: flex; align-items: center; gap: 20px; }
        .profile-avatar { width: 80px; height: 80px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.5); object-fit: cover; }
        .profile-info h2 { font-size: 24px; }
        .profile-info p { opacity: 0.9; margin-top: 4px; }
        .loyalty-badge { background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; font-size: 13px; margin-top: 8px; display: inline-block; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab { padding: 12px 24px; background: white; border-radius: 10px; cursor: pointer; font-weight: 600; border: 2px solid #e5e7eb; transition: .2s; }
        .tab.active { border-color: #ff6b00; color: #ff6b00; background: #fff7ed; }
        .tab:hover { border-color: #ff6b00; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,.05); margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; color: #374151; font-size: 13px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; outline: none; }
        .form-group input:focus { border-color: #ff6b00; }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: .2s; }
        .btn-primary { background: #ff6b00; color: white; }
        .btn-primary:hover { background: #e85f00; }
        .order-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
        .order-item:last-child { border-bottom: none; }
        .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .wishlist-item { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .wishlist-item img { width: 100%; height: 140px; object-fit: cover; }
        .wishlist-item .info { padding: 12px; }
        .wishlist-item .name { font-weight: 700; font-size: 14px; }
        .wishlist-item .price { color: #ff6b00; font-weight: 700; margin-top: 4px; }
        .wishlist-item .remove-btn { background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; margin-top: 8px; }
        .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
        .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 15px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; }
        @media (max-width: 600px) {
            .profile-header { flex-direction: column; text-align: center; }
            .tabs { overflow-x: auto; }
            .tab { white-space: nowrap; font-size: 13px; padding: 10px 16px; }
            .wishlist-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon"><i class="fas fa-utensils"></i></span>
        Food<span class="hub-brand">Hub</span>
    </a>
    <div>
        <a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a>
        <a href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i> Cart</a>
        <a href="{{ route('customer.profile') }}" style="color:#ff6b00;"><i class="fas fa-user"></i> Profile</a>
    </div>
</nav>

<div class="profile-container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="profile-header">
        <img src="{{ $customer->avatar_url }}" alt="Avatar" class="profile-avatar">
        <div class="profile-info">
            <h2>{{ $customer->name }}</h2>
            <p><i class="fas fa-phone"></i> {{ $customer->phone }} · <i class="fas fa-envelope"></i> {{ $customer->email }}</p>
            <div class="loyalty-badge">⭐ {{ $customer->loyalty_points }} Loyalty Points</div>
        </div>
    </div>

    <div class="tabs">
        <div class="tab active" onclick="switchTab('orders')"><i class="fas fa-receipt"></i> My Orders</div>
        <div class="tab" onclick="switchTab('wishlist')"><i class="fas fa-heart"></i> Wishlist</div>
        <div class="tab" onclick="switchTab('settings')"><i class="fas fa-cog"></i> Settings</div>
    </div>

    <!-- ORDERS TAB -->
    <div id="tab-orders" class="tab-content active">
        <div class="card">
            <h3 style="margin-bottom:15px;"><i class="fas fa-receipt"></i> Recent Orders</h3>
            @forelse($orders as $order)
                <div class="order-item">
                    <div>
                        <div style="font-weight:700;">Order #{{ $order->id }}</div>
                        <div style="font-size:12px;color:#9ca3af;">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">
                            {{ $order->order_type }} · {{ $order->items->count() }} items
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700;color:#ff6b00;">Rs. {{ number_format($order->total_amount) }}</div>
                        @php
                            $statusClass = match($order->status) {
                                'Cancelled' => 'status-cancelled',
                                'Delivered', 'Completed' => 'status-delivered',
                                default => 'status-pending',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <p>No orders yet. Start ordering! 🍕</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- WISHLIST TAB -->
    <div id="tab-wishlist" class="tab-content">
        <div class="card">
            <h3 style="margin-bottom:15px;"><i class="fas fa-heart"></i> My Favorites</h3>
            @forelse($wishlist as $food)
                <div class="wishlist-grid" style="display:contents;">
                    <div class="wishlist-item">
                        <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://via.placeholder.com/200' }}" alt="{{ $food->name }}">
                        <div class="info">
                            <div class="name">{{ $food->name }}</div>
                            <div class="price">Rs. {{ number_format($food->discounted_price) }}</div>
                            <button class="remove-btn" onclick="removeFromWishlist({{ $food->id }})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-heart"></i>
                    <p>No favorites yet. Tap ❤️ on any item to save it!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SETTINGS TAB -->
    <div id="tab-settings" class="tab-content">
        <div class="card">
            <h3 style="margin-bottom:15px;"><i class="fas fa-cog"></i> Profile Settings</h3>
            <form method="POST" action="{{ route('customer.update-profile') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ $customer->name }}" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ $customer->phone }}" required>
                </div>
                <div class="form-group">
                    <label>Default Address</label>
                    <input type="text" name="address" value="{{ $customer->address }}" placeholder="Enter your address">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    event.target.closest('.tab').classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

function removeFromWishlist(foodId) {
    fetch('/api/wishlist/' + foodId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => {
        if (d.success) location.reload();
    });
}
</script>

</body>
</html>
