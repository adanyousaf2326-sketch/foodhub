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

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,.05); text-align: center; }
        .stat-card .stat-num { font-size: 28px; font-weight: 900; }
        .stat-card .stat-label { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .stat-pending .stat-num { color: #f59e0b; }
        .stat-count .stat-num { color: #3b82f6; }
        .stat-total .stat-num { color: #16a34a; }

        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-weight: 600; }
        .error-box { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px; }

        /* Rider Cards */
        .rider-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; margin-bottom: 25px; }
        .rider-card { background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #e5e7eb; }
        .rider-card-header { padding: 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f3f4f6; }
        .rider-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb; }
        .rider-name { font-size: 16px; font-weight: 700; color: #111827; }
        .rider-phone { font-size: 12px; color: #6b7280; }
        .rider-card-body { padding: 12px 16px; }
        .rider-stat { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 14px; }
        .rider-stat .label { color: #6b7280; }
        .rider-stat .value { font-weight: 700; }
        .rider-stat .value.pending { color: #f59e0b; }
        .rider-stat .value.zero { color: #9ca3af; }
        .rider-card-actions { padding: 10px 16px; border-top: 1px solid #f3f4f6; }
        .receive-btn {
            width: 100%; padding: 12px; background: linear-gradient(135deg, #16a34a, #15803d); color: white;
            border: none; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer;
            transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .receive-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
        .receive-btn:disabled { background: #d1d5db; cursor: not-allowed; transform: none; box-shadow: none; }
        .no-orders-btn { background: #f3f4f6; color: #9ca3af; }

        /* Order Details */
        .order-list { margin-top: 12px; }
        .order-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        .order-row:last-child { border-bottom: none; }
        .order-row .order-info { color: #374151; }
        .order-row .order-info strong { color: #111827; }
        .order-row .order-amount { font-weight: 700; color: #f59e0b; }
        .order-row .single-close { padding: 4px 8px; border: none; border-radius: 4px; background: #16a34a; color: white; font-size: 11px; font-weight: 700; cursor: pointer; }

        /* Grand Total */
        .grand-total {
            background: white; border-radius: 14px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,.06);
            display: flex; justify-content: space-between; align-items: center;
        }
        .grand-total .gt-label { font-size: 18px; font-weight: 700; color: #111827; }
        .grand-total .gt-amount { font-size: 28px; font-weight: 900; color: #16a34a; }

        .empty { text-align: center; padding: 40px; color: #9ca3af; }
        .empty i { font-size: 40px; margin-bottom: 10px; display: block; }

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
        <p class="subtitle">Receive cash from riders and close delivered orders</p>
    </div>

    @if(session('success'))
        <div class="success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-pending">
            <div class="stat-num">Rs. {{ number_format($grandTotal, 2) }}</div>
            <div class="stat-label">Total Pending Cash</div>
        </div>
        <div class="stat-card stat-count">
            <div class="stat-num">{{ $allCashPending->count() }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
        <div class="stat-card stat-total">
            <div class="stat-num">{{ $riders->where('cash_pending_count', '>', 0)->count() }}</div>
            <div class="stat-label">Riders with Cash</div>
        </div>
    </div>

    <!-- Rider Cards -->
    <h2 style="font-size:20px;margin-bottom:16px;"><i class="fas fa-motorcycle"></i> Riders</h2>
    <div class="rider-grid">
        @forelse($riders->where('cash_pending_count', '>', 0) as $rider)
            <div class="rider-card">
                <div class="rider-card-header">
                    <img src="{{ $rider->photo_url }}" alt="" class="rider-avatar">
                    <div>
                        <div class="rider-name">{{ $rider->name }}</div>
                        <div class="rider-phone">{{ $rider->phone }}</div>
                    </div>
                </div>
                <div class="rider-card-body">
                    <div class="rider-stat">
                        <span class="label">📦 Pending Orders</span>
                        <span class="value pending">{{ $rider->cash_pending_count }}</span>
                    </div>
                    <div class="rider-stat">
                        <span class="label">💰 Cash to Collect</span>
                        <span class="value pending">Rs. {{ number_format($rider->cash_pending_total ?? 0, 2) }}</span>
                    </div>
                    <div class="rider-stat">
                        <span class="label">✅ Total Deliveries</span>
                        <span class="value">{{ $rider->total_orders }}</span>
                    </div>

                    <!-- Order Details -->
                    <div class="order-list">
                        @foreach($allCashPending->where('rider_id', $rider->id) as $order)
                            <div class="order-row">
                                <div class="order-info">
                                    <strong>#{{ $order->id }}</strong> — {{ $order->customer_name }}
                                    <br><small style="color:#9ca3af;">{{ $order->created_at->format('h:i A') }}</small>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span class="order-amount">Rs. {{ number_format($order->total_amount, 2) }}</span>
                                    <form method="POST" action="{{ route('admin.riders.receive-single-cash', $order->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="single-close" onclick="return confirm('Close order #{{ $order->id }}?')">✓</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="rider-card-actions">
                    <form method="POST" action="{{ route('admin.riders.receive-cash', $rider->id) }}">
                        @csrf
                        <button type="submit" class="receive-btn"
                                onclick="return confirm('Receive Rs. {{ number_format($rider->cash_pending_total ?? 0, 2) }} from {{ $rider->name }}? This will close {{ $rider->cash_pending_count }} orders.')">
                            <i class="fas fa-check-circle"></i>
                            Receive Rs. {{ number_format($rider->cash_pending_total ?? 0, 2) }} from {{ $rider->name }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty" style="grid-column:1/-1;">
                <i class="fas fa-check-double"></i>
                <h3 style="color:#16a34a;">All Clear! 🎉</h3>
                <p>No riders have pending cash collections.</p>
            </div>
        @endforelse
    </div>

    <!-- Grand Total -->
    @if($grandTotal > 0)
    <div class="grand-total">
        <div class="gt-label"><i class="fas fa-coins"></i> Grand Total Pending</div>
        <div class="gt-amount">Rs. {{ number_format($grandTotal, 2) }}</div>
    </div>
    @endif
</div>
</body>
</html>
