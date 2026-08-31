<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', Arial, sans-serif; }
        body { background: #f8f9fa; color: #222; }
        
        nav { background: #111827; padding: 18px 7%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        .logo { color: #ff6b00; font-size: 26px; font-weight: bold; text-decoration: none; }
        .logo-icon { margin-right: 4px; }
        .hub-brand { color: white; }
        nav a { color: white; text-decoration: none; margin-left: 18px; padding: 10px 14px; border-radius: 8px; transition: all 0.2s ease; }
        nav a:hover { background: #ff6b00; }
        .history-active { background: #2563eb; }
        .cart-count { display: inline-flex; align-items: center; justify-content: center; min-width: 21px; height: 21px; padding: 0 6px; margin-left: 4px; border-radius: 50%; background: white; color: #ff6b00; font-size: 12px; font-weight: bold; }
        
        .container { max-width: 900px; margin: 40px auto; padding: 20px; }
        
        .card { background: white; padding: 35px; border-radius: 18px; box-shadow: 0 8px 30px rgba(0, 0, 0, .08); }
        
        .title { text-align: center; margin-bottom: 30px; }
        .title .icon { font-size: 55px; margin-bottom: 10px; }
        .title h1 { font-size: 32px; margin-bottom: 8px; }
        .title p { color: #777; }
        
        .search-box { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .search-box input { flex: 1; min-width: 200px; padding: 14px; border: 1px solid #d1d5db; border-radius: 9px; font-size: 15px; outline: none; }
        .search-box input:focus { border-color: #ff6b00; box-shadow: 0 0 0 3px rgba(255, 107, 0, .10); }
        
        .filter-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { flex: 1; min-width: 150px; }
        .filter-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 13px; color: #555; }
        .filter-group input, .filter-group select { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; }
        .filter-group input:focus, .filter-group select:focus { border-color: #ff6b00; }
        
        .search-btn, .filter-btn { padding: 12px 24px; border: none; border-radius: 9px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .search-btn { background: #ff6b00; color: white; }
        .search-btn:hover { background: #e85f00; }
        .filter-btn { background: #2563eb; color: white; }
        .filter-btn:hover { background: #1d4ed8; }
        .clear-btn { background: #6b7280; color: white; }
        .clear-btn:hover { background: #4b5563; }
        
        .order-list { margin-top: 20px; }
        
        .order-card { background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; margin-bottom: 16px; transition: all 0.2s; }
        .order-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.08); transform: translateY(-2px); }
        
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px; }
        .order-id { font-size: 18px; font-weight: bold; color: #111827; }
        .order-date { font-size: 13px; color: #6b7280; }
        
        .status { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-preparing { background: #dbeafe; color: #1d4ed8; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-out-for-delivery { background: #e0e7ff; color: #3730a3; }
        
        .order-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin: 12px 0; }
        .detail-item { font-size: 13px; color: #555; }
        .detail-item strong { color: #111827; display: block; margin-bottom: 2px; }
        
        .order-items { margin-top: 12px; padding-top: 12px; border-top: 1px solid #f3f4f6; }
        .order-items h4 { font-size: 14px; color: #374151; margin-bottom: 8px; }
        .item-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; color: #555; border-bottom: 1px solid #f9fafb; }
        .item-row:last-child { border-bottom: none; }
        
        .order-total { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; padding-top: 12px; border-top: 2px solid #e5e7eb; }
        .order-total .total-label { font-size: 14px; font-weight: 600; color: #374151; }
        .order-total .total-amount { font-size: 18px; font-weight: bold; color: #ff6b00; }
        
        .track-btn { display: inline-block; padding: 8px 16px; background: #2563eb; color: white; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; margin-top: 12px; transition: all 0.2s; }
        .track-btn:hover { background: #1d4ed8; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
        .empty-state .icon { font-size: 60px; margin-bottom: 16px; }
        .empty-state h3 { font-size: 20px; color: #6b7280; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; }
        
        .error { margin-bottom: 20px; padding: 14px; background: #fee2e2; color: #991b1b; border-radius: 10px; }
        .success { margin-bottom: 20px; padding: 14px; background: #dcfce7; color: #166534; border-radius: 10px; }
        
        .info { margin-top: 30px; padding: 18px; background: #f8fafc; border-radius: 10px; border: 1px solid #e5e7eb; }
        .info h3 { margin-bottom: 10px; }
        .info p { color: #666; line-height: 1.6; font-size: 14px; }
        
        @media(max-width:700px) {
            nav { padding: 15px 5%; flex-direction: column; gap: 15px; }
            nav div:last-child { display: flex; flex-wrap: wrap; justify-content: center; }
            nav a { margin: 4px; font-size: 13px; }
            .container { margin: 20px auto; }
            .card { padding: 25px 18px; }
            .title h1 { font-size: 26px; }
            .search-box { flex-direction: column; }
            .filter-row { flex-direction: column; }
            .order-header { flex-direction: column; align-items: flex-start; }
            .order-details { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon">🍔</span>
        Food<span class="hub-brand">Hub</span>
    </a>
    <div>
        <a href="{{ url('/') }}">🏠 Home</a>
        <a href="{{ url('/#categories') }}">📁 Categories</a>
        <a href="{{ url('/#full-menu') }}"><i class="fas fa-utensils"></i> Menu</a>
        <a href="{{ url('/#announcement') }}"><i class="fas fa-tags"></i> New Deals</a>
        <a href="{{ route('track.order') }}"><i class="fas fa-map-marker-alt"></i> Track Order</a>
        <a href="{{ route('order.history') }}" class="history-active"><i class="fas fa-history"></i> History</a>
        <a href="{{ route('cart') }}" class="cart-nav" style="background:#ff6b00;">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count">{{ collect(session()->get('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<div class="container">
    <div class="card">
        <div class="title">
            <div class="icon"><i class="fas fa-history" style="font-size:40px;color:#ff6b00;"></i></div>
            <h1>Order History</h1>
            <p>Search and view all your past orders</p>
        </div>

        @if(session('success'))
            <div class="success">✅ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="error">❌ {{ session('error') }}</div>
        @endif

        <!-- SEARCH FORM -->
        <form action="{{ route('order.history') }}" method="GET">
            <div class="search-box">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Enter your phone number" required>
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search Orders</button>
            </div>

            <div class="filter-row">
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}">
                </div>
                <div class="filter-group">
                    <label>📅 To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                </div>
                <div class="filter-group">
                    <label>📊 Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Preparing" {{ request('status') === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="Out for Delivery" {{ request('status') === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="Delivered" {{ request('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>📦 Order Type</label>
                    <select name="order_type">
                        <option value="">All Types</option>
                        <option value="Dine In" {{ request('order_type') === 'Dine In' ? 'selected' : '' }}>🍽️ Dine In</option>
                        <option value="Delivery" {{ request('order_type') === 'Delivery' ? 'selected' : '' }}>🛵 Delivery</option>
                        <option value="Takeaway" {{ request('order_type') === 'Takeaway' ? 'selected' : '' }}>🥡 Takeaway</option>
                    </select>
                </div>
                <div class="filter-group" style="display:flex;gap:8px;align-items:flex-end;">
                    <button type="submit" class="filter-btn">Filter</button>
                    <a href="{{ route('order.history', ['phone' => request('phone')]) }}" class="search-btn clear-btn" style="text-decoration:none;">Clear</a>
                </div>
            </div>
        </form>

        <!-- ORDER LIST -->
        <div class="order-list">
            @if(request()->has('phone'))
                @if($orders && $orders->count() > 0)
                    <p style="margin-bottom:16px;color:#6b7280;font-size:14px;">
                        Found <strong>{{ $orders->count() }}</strong> order(s) for <strong>{{ request('phone') }}</strong>
                        @if(request('from_date') || request('to_date') || request('status') || request('order_type'))
                            <span> with filters applied</span>
                        @endif
                    </p>

                    @foreach($orders as $order)
                        @php
                            $statusClass = match($order->status) {
                                'Preparing' => 'status-preparing',
                                'Delivered' => 'status-delivered',
                                'Completed' => 'status-completed',
                                'Cancelled' => 'status-cancelled',
                                'Out for Delivery' => 'status-out-for-delivery',
                                default => 'status-pending',
                            };
                            $typeIcon = match($order->order_type) {
                                'Dine In' => '🍽️',
                                'Delivery' => '🛵',
                                'Takeaway' => '🥡',
                                default => '📦',
                            };
                        @endphp

                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <span class="order-id">Order #{{ $order->id }}</span>
                                    <span class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <span class="status {{ $statusClass }}">{{ $order->status }}</span>
                            </div>

                            <div class="order-details">
                                <div class="detail-item">
                                    <strong>📦 Type</strong>
                                    {{ $typeIcon }} {{ $order->order_type }}
                                </div>
                                <div class="detail-item">
                                    <strong>💳 Payment</strong>
                                    {{ $order->payment_method }}
                                </div>
                                <div class="detail-item">
                                    <strong>💰 Total</strong>
                                    Rs. {{ number_format($order->total_amount, 2) }}
                                </div>
                                <div class="detail-item">
                                    <strong>📞 Phone</strong>
                                    {{ $order->phone }}
                                </div>
                                @if($order->address)
                                <div class="detail-item">
                                    <strong>📍 Address</strong>
                                    {{ Str::limit($order->address, 50) }}
                                </div>
                                @endif
                                @if($order->email)
                                <div class="detail-item">
                                    <strong>📧 Email</strong>
                                    {{ $order->email }}
                                </div>
                                @endif
                            </div>

                            @if($order->items && $order->items->count() > 0)
                            <div class="order-items">
                                <h4>Items ({{ $order->items->count() }})</h4>
                                @foreach($order->items as $item)
                                <div class="item-row">
                                    <span>{{ $item->quantity }}x {{ $item->food_name }}</span>
                                    <span>Rs. {{ number_format($item->subtotal, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div class="order-total">
                                <span class="total-label">Total Amount</span>
                                <span class="total-amount">Rs. {{ number_format($order->total_amount, 2) }}</span>
                            </div>

                            @if(!in_array($order->status, ['Cancelled', 'Completed', 'Delivered']))
                            <a href="{{ route('track.order.search', ['order_number' => $order->id]) }}" class="track-btn">
                                📍 Track This Order
                            </a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <h3>No Orders Found</h3>
                        <p>No orders found for phone number "{{ request('phone') }}" with the selected filters.</p>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="icon">🔍</div>
                    <h3>Search Your Orders</h3>
                    <p>Enter your phone number above to view your order history.</p>
                </div>
            @endif
        </div>

        <div class="info">
            <h3>📋 How Order History Works</h3>
            <p>
                Enter the phone number you used when placing your order. You can filter by date range, 
                order status, and order type. Click "Track This Order" to see real-time status updates 
                and chat with the restaurant.
            </p>
        </div>
    </div>
</div>

</body>
</html>
