<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cash Collection - Rider Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #0f172a; color: white; min-height: 100vh; }

        .topbar {
            background: linear-gradient(135deg, #1e293b, #334155);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff6b00;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar .logo { font-size: 18px; font-weight: 800; color: #ff6b00; display: flex; align-items: center; gap: 6px; }
        .topbar .rider-info { display: flex; align-items: center; gap: 10px; }
        .logout-btn { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #94a3b8; padding: 6px 12px; border-radius: 8px; cursor: pointer; font-size: 12px; text-decoration: none; }
        .back-link { color: #94a3b8; text-decoration: none; font-size: 13px; }
        .back-link:hover { color: white; }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            padding: 20px;
        }
        .summary-card {
            background: #1e293b;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #334155;
            text-align: center;
        }
        .summary-card .card-icon { font-size: 32px; margin-bottom: 8px; }
        .summary-card .card-amount { font-size: 28px; font-weight: 900; }
        .summary-card .card-label { font-size: 12px; color: #64748b; margin-top: 4px; }
        .card-pending .card-amount { color: #fbbf24; }
        .card-collected .card-amount { color: #4ade80; }

        .section-label {
            padding: 12px 20px 8px;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .orders-list { padding: 0 20px 20px; display: flex; flex-direction: column; gap: 12px; }

        .order-card {
            background: #1e293b;
            border-radius: 14px;
            border: 1px solid #334155;
            overflow: hidden;
        }
        .order-header {
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .order-id { font-size: 18px; font-weight: 800; }
        .cash-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            background: #fbbf2420;
            color: #fbbf24;
            border: 1px solid #fbbf2440;
        }
        .order-body { padding: 12px 16px; }
        .order-detail { display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 4px; color: #94a3b8; }
        .order-detail i { color: #64748b; width: 16px; }
        .order-detail strong { color: #e2e8f0; }
        .order-amount { padding: 12px 16px; text-align: right; font-size: 20px; font-weight: 900; color: #fbbf24; border-top: 1px solid rgba(255,255,255,0.05); }

        .empty-state { text-align: center; padding: 40px 20px; color: #475569; }
        .empty-state i { font-size: 48px; margin-bottom: 10px; display: block; }

        .collect-note {
            margin: 0 20px 20px;
            padding: 14px;
            background: #fbbf2410;
            border: 1px solid #fbbf2430;
            border-radius: 10px;
            font-size: 13px;
            color: #fbbf24;
            line-height: 1.6;
        }

        @media(max-width: 600px) {
            .summary-cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a href="{{ route('rider.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <div class="logo"><i class="fas fa-money-bill-wave"></i> Cash Collection</div>
        <div class="rider-info">
            <span style="font-size:13px;font-weight:600;">{{ $rider->name }}</span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card card-pending">
            <div class="card-icon">💰</div>
            <div class="card-amount">Rs. {{ number_format($totalToCollect, 2) }}</div>
            <div class="card-label">To Collect ({{ $cashPendingOrders->count() }} orders)</div>
        </div>
        <div class="summary-card card-collected">
            <div class="card-icon">✅</div>
            <div class="card-amount">Rs. {{ number_format($totalCollected, 2) }}</div>
            <div class="card-label">Collected Today</div>
        </div>
    </div>

    <div class="collect-note">
        <i class="fas fa-info-circle"></i>
        Collect cash from customers for these orders, then give the total amount to the restaurant admin.
        Admin will click "Amount Received" to close these orders.
    </div>

    <!-- Cash Pending Orders -->
    <div class="section-label"><i class="fas fa-coins" style="color:#fbbf24;"></i> Cash Pending Orders</div>
    <div class="orders-list">
        @forelse($cashPendingOrders as $order)
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">#{{ $order->id }}</span>
                    <span class="cash-badge">💰 Cash Pending</span>
                </div>
                <div class="order-body">
                    <div class="order-detail"><i class="fas fa-user"></i> <strong>{{ $order->customer_name }}</strong></div>
                    <div class="order-detail"><i class="fas fa-phone"></i> {{ $order->phone }}</div>
                    @if($order->address)
                        <div class="order-detail"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($order->address, 60) }}</div>
                    @endif
                    <div class="order-detail"><i class="fas fa-box"></i>
                        @foreach($order->items as $item)
                            ×{{ $item->quantity }} {{ $item->food_name }}{{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </div>
                </div>
                <div class="order-amount">Rs. {{ number_format($order->total_amount, 2) }}</div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <p>No cash pending orders!</p>
                <p style="margin-top:6px;font-size:13px;color:#4ade80;">All deliveries collected ✅</p>
            </div>
        @endforelse
    </div>

    <div style="height:30px;"></div>
</body>
</html>
