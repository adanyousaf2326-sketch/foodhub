<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rider Dashboard - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #0f172a; color: white; min-height: 100vh; }

        /* Top Bar */
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
        .rider-avatar { width: 36px; height: 36px; border-radius: 50%; border: 2px solid #ff6b00; object-fit: cover; }
        .logout-btn { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #94a3b8; padding: 6px 12px; border-radius: 8px; cursor: pointer; font-size: 12px; text-decoration: none; }
        .logout-btn:hover { background: #dc2626; color: white; }

        /* Duty Toggle */
        .duty-section {
            padding: 20px;
            text-align: center;
        }
        .duty-toggle {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            background: #1e293b;
            padding: 16px 28px;
            border-radius: 16px;
            border: 2px solid #334155;
        }
        .duty-label { font-size: 16px; font-weight: 700; }
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 32px;
            cursor: pointer;
        }
        .toggle-switch input { display: none; }
        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #475569;
            border-radius: 32px;
            transition: background 0.3s;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 26px;
            height: 26px;
            left: 3px;
            top: 3px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }
        .toggle-switch input:checked + .toggle-slider { background: #16a34a; }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(28px); }
        .duty-status {
            font-size: 14px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 8px;
        }
        .duty-on { background: #16a34a20; color: #4ade80; border: 1px solid #16a34a40; }
        .duty-off { background: #ef444420; color: #f87171; border: 1px solid #ef444440; }

        /* Stats */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 0 20px 20px;
        }
        .stat-box {
            background: #1e293b;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
            border: 1px solid #334155;
        }
        .stat-box .stat-num { font-size: 28px; font-weight: 900; color: #ff6b00; }
        .stat-box .stat-label { font-size: 11px; color: #64748b; margin-top: 2px; }

        /* Orders */
        .section-title {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title i { color: #ff6b00; }
        .orders-list { padding: 0 20px 20px; display: flex; flex-direction: column; gap: 12px; }

        .order-card {
            background: #1e293b;
            border-radius: 14px;
            border: 1px solid #334155;
            overflow: hidden;
            transition: all 0.3s;
        }
        .order-card.urgent { border-color: #f59e0b; }
        .order-header {
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .order-id { font-size: 18px; font-weight: 800; }
        .order-type-badge {
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-delivery { background: #3b82f620; color: #93c5fd; border: 1px solid #3b82f640; }

        .order-body { padding: 12px 16px; }
        .order-detail { display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; color: #94a3b8; }
        .order-detail i { color: #64748b; width: 16px; }
        .order-detail strong { color: #e2e8f0; }

        .order-items-list { font-size: 12px; color: #64748b; padding: 4px 0 8px; border-top: 1px solid rgba(255,255,255,0.05); margin-top: 6px; }
        .order-item-row { display: flex; justify-content: space-between; padding: 3px 0; }

        .order-actions { padding: 10px 16px; display: flex; gap: 8px; }
        .action-btn {
            flex: 1; padding: 10px; border: none; border-radius: 8px;
            font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .btn-accept { background: #3b82f6; color: white; }
        .btn-accept:hover { background: #2563eb; }
        .btn-deliver { background: #16a34a; color: white; }
        .btn-deliver:hover { background: #15803d; }
        .btn-navigate { background: #f59e0b; color: #111; }
        .btn-navigate:hover { background: #d97706; }

        /* Empty */
        .empty-state { text-align: center; padding: 40px 20px; color: #475569; }
        .empty-state i { font-size: 48px; margin-bottom: 10px; display: block; }

        /* Delivered History */
        .history-card {
            background: #1e293b;
            border-radius: 12px;
            border: 1px solid #334155;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-left { display: flex; align-items: center; gap: 10px; }
        .history-icon { width: 34px; height: 34px; border-radius: 8px; background: #16a34a20; display: flex; align-items: center; justify-content: center; color: #4ade80; font-size: 14px; }
        .history-id { font-weight: 700; font-size: 14px; }
        .history-date { font-size: 11px; color: #64748b; }
        .history-amount { color: #ff6b00; font-weight: 700; }

        /* Toast */
        .toast {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            background: #16a34a; color: white; padding: 12px 24px; border-radius: 10px;
            font-weight: 600; z-index: 999; opacity: 0; transition: opacity 0.3s;
            box-shadow: 0 4px 15px rgba(22,163,74,0.4);
        }
        .toast.show { opacity: 1; }

        @media(max-width: 600px) {
            .stats-row { grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 0 12px 12px; }
            .orders-list { padding: 0 12px 12px; }
            .section-title { padding: 10px 12px; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo"><i class="fas fa-motorcycle"></i> Rider Panel</div>
        <div class="rider-info">
            <img src="{{ $rider->photo_url }}" alt="" class="rider-avatar">
            <span style="font-size:13px;font-weight:600;">{{ $rider->name }}</span>
            <a href="{{ route('rider.logout') }}" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <!-- Duty Toggle -->
    <div class="duty-section">
        <div class="duty-toggle">
            <span class="duty-label">Your Status</span>
            <label class="toggle-switch">
                <input type="checkbox" id="dutyToggle" {{ $rider->is_on_duty ? 'checked' : '' }} onchange="toggleDuty()">
                <span class="toggle-slider"></span>
            </label>
            <span class="duty-status {{ $rider->is_on_duty ? 'duty-on' : 'duty-off' }}" id="dutyStatus">
                {{ $rider->is_on_duty ? '🟢 ON DUTY' : '🔴 OFF DUTY' }}
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-num">{{ $assignedOrders->count() }}</div>
            <div class="stat-label">Active Orders</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $totalDelivered }}</div>
            <div class="stat-label">Delivered</div>
        </div>
        <div class="stat-box">
            <div class="stat-num" id="onDutyText">{{ $rider->is_on_duty ? 'ON' : 'OFF' }}</div>
            <div class="stat-label">Status</div>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="section-title"><i class="fas fa-box"></i> Active Orders</div>
    <div class="orders-list">
        @forelse($assignedOrders as $order)
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">#{{ $order->id }}</span>
                    <span class="order-type-badge badge-delivery">{{ $order->status }}</span>
                </div>
                <div class="order-body">
                    <div class="order-detail"><i class="fas fa-user"></i> <strong>{{ $order->customer_name }}</strong></div>
                    <div class="order-detail"><i class="fas fa-phone"></i> {{ $order->phone }}</div>
                    @if($order->address)
                        <div class="order-detail"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($order->address, 60) }}</div>
                    @endif
                    <div class="order-detail"><i class="fas fa-money-bill"></i> <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong></div>
                    @if($order->delivery_distance_km)
                        <div class="order-detail"><i class="fas fa-road"></i> {{ $order->delivery_distance_km }} km away</div>
                    @endif
                    @if($order->customer_lat && $order->customer_lng)
                        <div class="order-items-list">
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->customer_lat }},{{ $order->customer_lng }}"
                               target="_blank" style="color:#3b82f6;font-weight:600;text-decoration:none;">
                                <i class="fas fa-directions"></i> Open in Google Maps
                            </a>
                        </div>
                    @endif
                    <div class="order-items-list">
                        @foreach($order->items as $item)
                            <div class="order-item-row">
                                <span>×{{ $item->quantity }} {{ $item->food_name }}{{ $item->variant_name ? ' ('.$item->variant_name.')' : '' }}</span>
                                <span>Rs. {{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="order-actions">
                    @if($order->customer_lat && $order->customer_lng)
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->customer_lat }},{{ $order->customer_lng }}"
                           target="_blank" class="action-btn btn-navigate"><i class="fas fa-directions"></i> Navigate</a>
                    @endif
                    @if($order->status === 'Assigned')
                        <a href="{{ route('rider.accept-order', $order->id) }}" class="action-btn btn-accept"
                           onclick="return confirm('Accept this order?')">
                            <i class="fas fa-check"></i> Accept
                        </a>
                    @elseif($order->status === 'Out for Delivery')
                        <a href="{{ route('rider.mark-delivered', $order->id) }}" class="action-btn btn-deliver"
                           onclick="return confirm('Mark as delivered?')">
                            <i class="fas fa-check-double"></i> Mark Delivered
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No active orders</p>
                @if(!$rider->is_on_duty)
                    <p style="margin-top:8px;font-size:13px;color:#64748b;">Turn ON duty to receive orders</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Delivery History -->
    @if($deliveredOrders->count() > 0)
        <div class="section-title"><i class="fas fa-history"></i> Recent Deliveries</div>
        <div class="orders-list">
            @foreach($deliveredOrders as $order)
                <div class="history-card">
                    <div class="history-left">
                        <div class="history-icon"><i class="fas fa-check"></i></div>
                        <div>
                            <div class="history-id">#{{ $order->id }} — {{ $order->customer_name }}</div>
                            <div class="history-date">{{ $order->updated_at->format('d M, h:i A') }}</div>
                        </div>
                    </div>
                    <div class="history-amount">Rs. {{ number_format($order->total_amount, 2) }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div style="height:30px;"></div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <script>
        function showToast(msg) {
            var t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(function() { t.classList.remove('show'); }, 3000);
        }

        function toggleDuty() {
            fetch('/rider/toggle-duty', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var statusEl = document.getElementById('dutyStatus');
                var textEl = document.getElementById('onDutyText');
                if (data.is_on_duty) {
                    statusEl.className = 'duty-status duty-on';
                    statusEl.textContent = '🟢 ON DUTY';
                    textEl.textContent = 'ON';
                } else {
                    statusEl.className = 'duty-status duty-off';
                    statusEl.textContent = '🔴 OFF DUTY';
                    textEl.textContent = 'OFF';
                }
                showToast(data.message);
            })
            .catch(function() { showToast('Failed to update status'); });
        }

        // Poll for new orders every 15 seconds
        setInterval(function() { location.reload(); }, 15000);
    </script>
</body>
</html>
