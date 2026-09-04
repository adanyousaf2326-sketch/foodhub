<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customer->name }} - Customer Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #f4f6f9; }
        .container { max-width: 900px; margin: auto; padding: 30px 20px; }
        .back-link { color: #6b7280; text-decoration: none; font-size: 14px; margin-bottom: 20px; display: inline-block; }
        .back-link:hover { color: #111; }

        .profile-card { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,.05); margin-bottom: 20px; }
        .profile-header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .profile-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #ff6b00; }
        .profile-info h2 { font-size: 24px; color: #111827; }
        .profile-info p { color: #6b7280; font-size: 14px; margin-top: 4px; }
        .profile-info p i { width: 20px; color: #ff6b00; }

        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-box { background: #f8fafc; border-radius: 12px; padding: 16px; text-align: center; }
        .stat-box .num { font-size: 24px; font-weight: 900; }
        .stat-box .label { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .stat-orders .num { color: #3b82f6; }
        .stat-spent .num { color: #16a34a; }
        .stat-points .num { color: #f59e0b; }

        .orders-section { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .orders-section h3 { margin-bottom: 16px; color: #111827; }

        .order-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
        .order-item:last-child { border-bottom: none; }
        .order-id { font-weight: 700; color: #ff6b00; }
        .order-date { color: #9ca3af; font-size: 12px; }
        .order-type { font-size: 11px; background: #eff6ff; color: #1d4ed8; padding: 2px 8px; border-radius: 6px; }
        .order-amount { font-weight: 700; color: #16a34a; }
        .order-status { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .empty { text-align: center; padding: 30px; color: #9ca3af; }

        @media(max-width: 600px) {
            .stats-row { grid-template-columns: 1fr; }
            .profile-header { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
@include('admin.partials.topbar')

<div class="container">
    <a href="{{ route('admin.customers.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Customers
    </a>

    <div class="profile-card">
        <div class="profile-header">
            <img src="{{ $customer->avatar_url }}" alt="" class="profile-avatar">
            <div class="profile-info">
                <h2>{{ $customer->name }}</h2>
                <p><i class="fas fa-phone"></i> {{ $customer->phone }}</p>
                <p><i class="fas fa-envelope"></i> {{ $customer->email }}</p>
                @if($customer->address)
                    <p><i class="fas fa-map-marker-alt"></i> {{ $customer->address }}</p>
                @endif
                <p><i class="fas fa-calendar"></i> Joined {{ $customer->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-box stat-orders">
                <div class="num">{{ $totalOrders }}</div>
                <div class="label">Total Orders</div>
            </div>
            <div class="stat-box stat-spent">
                <div class="num">Rs. {{ number_format($totalSpent, 0) }}</div>
                <div class="label">Total Spent</div>
            </div>
            <div class="stat-box stat-points">
                <div class="num">{{ $customer->loyalty_points }}</div>
                <div class="label">⭐ Loyalty Points</div>
            </div>
        </div>
    </div>

    <div class="orders-section">
        <h3><i class="fas fa-receipt"></i> Order History</h3>
        @forelse($customer->orders as $order)
            <div class="order-item">
                <div>
                    <span class="order-id">#{{ $order->id }}</span>
                    <span class="order-type">{{ $order->order_type }}</span>
                    <div class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div style="text-align:right;">
                    <div class="order-amount">Rs. {{ number_format($order->total_amount, 2) }}</div>
                    @php
                        $statusClass = match($order->status) {
                            'Cancelled' => 'status-cancelled',
                            'Delivered', 'Completed' => 'status-delivered',
                            default => 'status-pending',
                        };
                    @endphp
                    <span class="order-status {{ $statusClass }}">{{ $order->status }}</span>
                </div>
            </div>
        @empty
            <div class="empty">
                <i class="fas fa-receipt" style="font-size:36px;margin-bottom:8px;"></i>
                <p>No orders yet</p>
            </div>
        @endforelse
    </div>
</div>
</body>
</html>
