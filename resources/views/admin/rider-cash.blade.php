<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Cash Collection - FoodHub Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #f4f6f9; }
        .container { max-width: 1100px; margin: auto; padding: 30px 20px; }
        .page-header { margin-bottom: 25px; }
        .page-header h1 { font-size: 28px; color: #111827; }
        .subtitle { color: #777; margin-top: 4px; }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-weight: 600; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,.05); text-align: center; }
        .stat-card .stat-num { font-size: 28px; font-weight: 900; }
        .stat-card .stat-label { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .stat-pending .stat-num { color: #f59e0b; }
        .stat-count .stat-num { color: #3b82f6; }
        .stat-total .stat-num { color: #16a34a; }

        /* Tabs */
        .tabs { display: flex; gap: 4px; margin-bottom: 20px; background: white; padding: 4px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
        .tab-btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; background: transparent; color: #6b7280; transition: all 0.2s; }
        .tab-btn.active { background: #ff6b00; color: white; }
        .tab-btn:hover:not(.active) { background: #f3f4f6; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Rider Cards */
        .rider-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }
        .rider-card { background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #e5e7eb; }
        .rider-header { padding: 16px; display: flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0; }
        .rider-avatar { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb; }
        .rider-info { flex: 1; }
        .rider-name { font-size: 16px; font-weight: 700; color: #111827; }
        .rider-phone { font-size: 12px; color: #6b7280; }
        .rider-total-badge { background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 20px; font-weight: 800; font-size: 14px; white-space: nowrap; }

        /* Order List */
        .order-list { padding: 0 16px; }
        .order-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
        .order-item:last-child { border-bottom: none; }
        .order-num { font-weight: 700; color: #ff6b00; font-size: 14px; }
        .order-customer { color: #374151; font-size: 13px; }
        .order-time { color: #9ca3af; font-size: 11px; }
        .order-amount { font-weight: 800; color: #16a34a; font-size: 15px; }

        /* Receive Button */
        .rider-actions { padding: 12px 16px; }
        .receive-btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #16a34a, #15803d); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .receive-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,0.3); }
        .receive-btn:disabled { background: #d1d5db; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Sales History */
        .sale-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: white; border-radius: 10px; margin-bottom: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.04); }
        .sale-rider { display: flex; align-items: center; gap: 10px; }
        .sale-rider img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .sale-name { font-weight: 700; font-size: 14px; }
        .sale-date { color: #9ca3af; font-size: 12px; }
        .sale-amount { font-weight: 800; color: #16a34a; font-size: 16px; }

        .empty { text-align: center; padding: 50px; color: #9ca3af; }
        .empty i { font-size: 48px; margin-bottom: 12px; display: block; }
        .empty h3 { color: #16a34a; margin-bottom: 6px; }

        @media(max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } .rider-grid { grid-template-columns: 1fr; } }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
</head>
<body>
@include('admin.partials.topbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-money-bill-wave"></i> Rider Cash Collection</h1>
        <p class="subtitle">Receive cash from riders — one click closes all orders</p>
    </div>

    @if(session('success'))
        <div class="success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-pending">
            <div class="stat-num">Rs. {{ number_format($grandTotal, 0) }}</div>
            <div class="stat-label">💰 Total Pending Cash</div>
        </div>
        <div class="stat-card stat-count">
            <div class="stat-num">{{ $allCashPending->count() }}</div>
            <div class="stat-label">📦 Pending Orders</div>
        </div>
        <div class="stat-card stat-total">
            <div class="stat-num">{{ $riders->where('cash_pending_count', '>', 0)->count() }}</div>
            <div class="stat-label">🛵 Riders with Cash</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('pending')">
            <i class="fas fa-clock"></i> Pending Cash ({{ $allCashPending->count() }})
        </button>
        <button class="tab-btn" onclick="switchTab('sales')">
            <i class="fas fa-check-double"></i> Today's Sales
        </button>
    </div>

    <!-- PENDING CASH TAB -->
    <div class="tab-content active" id="tab-pending">
        <div class="rider-grid">
            @forelse($riders->where('cash_pending_count', '>', 0) as $rider)
                <div class="rider-card">
                    <div class="rider-header">
                        <img src="{{ $rider->photo_url }}" alt="" class="rider-avatar">
                        <div class="rider-info">
                            <div class="rider-name">{{ $rider->name }}</div>
                            <div class="rider-phone">{{ $rider->phone }}</div>
                        </div>
                        <div class="rider-total-badge">
                            Rs. {{ number_format($rider->cash_pending_total ?? 0, 0) }}
                        </div>
                    </div>

                    <div class="order-list">
                        @foreach($allCashPending->where('rider_id', $rider->id) as $order)
                            <div class="order-item">
                                <div>
                                    <span class="order-num">#{{ $order->id }}</span>
                                    <span class="order-customer">{{ $order->customer_name }}</span>
                                    <div class="order-time">{{ $order->created_at->format('h:i A') }}</div>
                                </div>
                                <div class="order-amount">Rs. {{ number_format($order->total_amount, 0) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rider-actions">
                        <form method="POST" action="{{ route('admin.riders.receive-cash', $rider->id) }}">
                            @csrf
                            <button type="submit" class="receive-btn"
                                    onclick="return confirm('✅ Receive Rs. {{ number_format($rider->cash_pending_total ?? 0, 0) }} from {{ $rider->name }}?\n\nThis will close {{ $rider->cash_pending_count }} orders and add them to today\'s sales.')">
                                <i class="fas fa-check-circle"></i>
                                Receive Rs. {{ number_format($rider->cash_pending_total ?? 0, 0) }} — Close All
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty" style="grid-column:1/-1;">
                    <i class="fas fa-check-double"></i>
                    <h3>All Clear! 🎉</h3>
                    <p>No riders have pending cash collections today.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SALES TAB -->
    <div class="tab-content" id="tab-sales">
        @php
            $todaySales = \App\Models\Order::where('status', 'Delivered')
                ->whereDate('updated_at', today())
                ->with('rider')
                ->latest('updated_at')
                ->get();
            $todayTotal = $todaySales->sum('total_amount');
        @endphp

        <div style="background:white;border-radius:12px;padding:16px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,.05);">
            <div>
                <div style="font-size:14px;color:#6b7280;">Today's Total Sales</div>
                <div style="font-size:28px;font-weight:900;color:#16a34a;">Rs. {{ number_format($todayTotal, 0) }}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:14px;color:#6b7280;">Orders Completed</div>
                <div style="font-size:28px;font-weight:900;color:#ff6b00;">{{ $todaySales->count() }}</div>
            </div>
        </div>

        @forelse($todaySales as $order)
            <div class="sale-row">
                <div class="sale-rider">
                    @if($order->rider)
                        <img src="{{ $order->rider->photo_url }}" alt="">
                    @else
                        <div style="width:36px;height:36px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:14px;">🍽️</div>
                    @endif
                    <div>
                        <div class="sale-name">
                            #{{ $order->id }} — {{ $order->customer_name }}
                        </div>
                        <div class="sale-date">
                            {{ $order->rider ? $order->rider->name . ' · ' : '' }}
                            {{ $order->updated_at->format('h:i A') }}
                        </div>
                    </div>
                </div>
                <div class="sale-amount">Rs. {{ number_format($order->total_amount, 0) }}</div>
            </div>
        @empty
            <div class="empty">
                <i class="fas fa-receipt"></i>
                <h3>No sales today yet</h3>
                <p>Sales will appear here after orders are completed.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    event.target.closest('.tab-btn').classList.add('active');
}
</script>
</body>
</html>
