<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display - FoodHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #0f172a; color: white; min-height: 100vh; }

        .kitchen-topbar {
            background: linear-gradient(135deg, #1e293b, #334155);
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff6b00;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .kitchen-topbar .logo { font-size: 20px; font-weight: 800; color: #ff6b00; display: flex; align-items: center; gap: 8px; }
        .kitchen-topbar .stats { display: flex; gap: 16px; font-size: 13px; }
        .stat-item { display: flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 8px; background: rgba(255,255,255,0.06); }
        .stat-item .count { font-weight: 800; font-size: 18px; }
        .stat-pending .count { color: #f59e0b; }
        .stat-picked .count { color: #3b82f6; }
        .stat-delivered .count { color: #10b981; }
        .stat-cancelled .count { color: #ef4444; }
        .back-link { color: #94a3b8; text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 4px; }
        .back-link:hover { color: white; }

        .section-label {
            padding: 16px 20px 8px;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .orders-container { padding: 0 20px 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }

        .order-card {
            background: #1e293b;
            border-radius: 14px;
            border: 2px solid #334155;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeIn 0.4s ease;
            position: relative;
        }
        .order-card.status-pending { border-color: #f59e0b; }
        .order-card.status-assigned { border-color: #3b82f6; }
        .order-card.status-picked { border-color: #8b5cf6; animation: pickedPulse 2s infinite; }
        .order-card.status-ready { border-color: #10b981; background: #064e3b; }
        .order-card.status-cancelled { border-color: #64748b; opacity: 0.7; border-style: dashed; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pickedPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.3); }
            50% { box-shadow: 0 0 20px 4px rgba(139, 92, 246, 0.2); }
        }

        .order-header { padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .order-id { font-size: 18px; font-weight: 800; }
        .order-meta { text-align: right; }
        .order-table { font-size: 12px; color: #94a3b8; }
        .order-type-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-dinein { background: #7c3aed20; color: #a78bfa; border: 1px solid #7c3aed40; }
        .badge-delivery { background: #3b82f620; color: #93c5fd; border: 1px solid #3b82f640; }
        .badge-takeaway { background: #f59e0b20; color: #fcd34d; border: 1px solid #f59e0b40; }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 4px;
        }
        .sb-pending { background: #f59e0b20; color: #fbbf24; border: 1px solid #f59e0b40; }
        .sb-assigned { background: #3b82f620; color: #93c5fd; border: 1px solid #3b82f640; }
        .sb-picked { background: #8b5cf620; color: #c4b5fd; border: 1px solid #8b5cf640; }
        .sb-cancelled { background: #ef444420; color: #fca5a5; border: 1px solid #ef444440; }
        .sb .dot { width: 6px; height: 6px; border-radius: 50%; }
        .sb-pending .dot { background: #fbbf24; animation: dotPulse 1.5s infinite; }
        .sb-assigned .dot { background: #93c5fd; }
        .sb-picked .dot { background: #c4b5fd; animation: dotPulse 1s infinite; }
        .sb-cancelled .dot { background: #fca5a5; }
        @keyframes dotPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* Rider Info */
        .rider-info {
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(139, 92, 246, 0.08);
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 13px;
        }
        .rider-info i { color: #8b5cf6; }
        .rider-info strong { color: #c4b5fd; }
        .rider-info .rider-phone { color: #64748b; font-size: 12px; margin-left: auto; }

        /* Timer */
        .timer-section { padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; }
        .timer-display { font-size: 28px; font-weight: 900; font-variant-numeric: tabular-nums; letter-spacing: 2px; }
        .timer-display.on-time { color: #10b981; }
        .timer-display.warning { color: #f59e0b; }
        .timer-display.overdue { color: #ef4444; animation: blink 1s infinite; }
        .timer-display.done { color: #10b981; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .timer-label { font-size: 11px; color: #64748b; }
        .timer-progress { width: 100%; height: 4px; background: #334155; border-radius: 2px; overflow: hidden; margin: 0 16px; }
        .timer-bar { height: 100%; border-radius: 2px; transition: width 1s linear, background 0.3s; }
        .timer-bar.on-time { background: linear-gradient(90deg, #10b981, #34d399); }
        .timer-bar.warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .timer-bar.overdue { background: linear-gradient(90deg, #ef4444, #f87171); }

        .order-items { padding: 8px 16px; }
        .order-item { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 13px; }
        .order-item:last-child { border-bottom: none; }
        .item-name { display: flex; align-items: center; gap: 8px; }
        .item-qty { background: #ff6b0020; color: #ff6b00; padding: 2px 7px; border-radius: 5px; font-weight: 800; font-size: 12px; min-width: 24px; text-align: center; }
        .item-prep { font-size: 11px; color: #64748b; }
        .item-variant { font-size: 11px; color: #94a3b8; font-style: italic; }
        .order-notes { margin-top: 6px; padding: 6px 8px; background: #1a1a2e; border-radius: 6px; font-size: 11px; color: #fbbf24; }

        .order-actions { padding: 10px 16px; display: flex; gap: 8px; }
        .action-btn { flex: 1; padding: 10px; border: none; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-clear { background: #ef4444; color: white; }
        .btn-clear:hover { background: #dc2626; }

        /* Cancelled order details */
        .cancelled-info { padding: 8px 16px; font-size: 12px; color: #94a3b8; }
        .cancelled-info i { color: #ef4444; }

        .no-orders { text-align: center; padding: 60px 20px; color: #475569; grid-column: 1 / -1; }
        .no-orders i { font-size: 50px; margin-bottom: 10px; display: block; }

        @media (max-width: 768px) {
            .orders-container { grid-template-columns: 1fr 1fr; padding: 0 8px 8px; gap: 8px; }
            .kitchen-topbar { flex-wrap: wrap; gap: 8px; padding: 8px 12px; }
            .kitchen-topbar .stats { flex-wrap: wrap; gap: 6px; }
            .stat-item { padding: 4px 8px; font-size: 11px; }
            .stat-item .count { font-size: 14px; }
            .order-header { padding: 8px 12px; }
            .order-id { font-size: 16px; }
            .order-body { padding: 8px 12px; }
            .order-detail { font-size: 11px; margin-bottom: 3px; }
            .order-items { font-size: 10px; padding: 6px 12px; }
            .order-actions { padding: 6px 12px; }
            .action-btn { padding: 6px 8px; font-size: 11px; }
            .section-label { padding: 10px 12px 6px; font-size: 12px; }
        }
        @media (max-width: 480px) {
            .orders-container { grid-template-columns: 1fr; gap: 6px; }
            .order-card { border-radius: 10px; }
            .order-header { padding: 6px 10px; }
            .order-id { font-size: 14px; }
            .order-body { padding: 6px 10px; }
            .order-detail { font-size: 10px; }
            .order-items { font-size: 9px; padding: 4px 10px; }
            .action-btn { padding: 5px 6px; font-size: 10px; }
        }
    </style>
</head>
<body>

<div class="kitchen-topbar">
    <a href="{{ route('admin.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Dashboard</a>
    <div class="logo"><i class="fas fa-fire"></i> Kitchen Display</div>
    <div class="stats">
        <div class="stat-item stat-pending"><i class="fas fa-clock"></i><span class="count">{{ $pendingOrders->count() }}</span><span>Pending</span></div>
        <div class="stat-item stat-picked"><i class="fas fa-motorcycle"></i><span class="count">{{ $pickedUpOrders->count() }}</span><span>Picked Up</span></div>
        <div class="stat-item stat-delivered"><i class="fas fa-check-circle"></i><span class="count">{{ $readyOrders->count() }}</span><span>Ready</span></div>
        @if($cancelledOrders->count() > 0)
            <div class="stat-item stat-cancelled"><i class="fas fa-times-circle"></i><span class="count">{{ $cancelledOrders->count() }}</span><span>Cancelled</span></div>
        @endif
    </div>
</div>

{{-- ACTIVE ORDERS --}}
<div class="section-label"><i class="fas fa-fire" style="color:#ff6b00;"></i> Active Orders</div>
<div class="orders-container">
    @forelse($pendingOrders as $order)
        @php
            $totalPrepMinutes = \App\Services\PrepTimeCalculator::calculate($order);
            $createdAt = $order->created_at;
        @endphp

        <div class="order-card status-{{ strtolower(str_replace(' ', '', $order->status)) }}"
             id="order-{{ $order->id }}"
             data-order-id="{{ $order->id }}"
             data-created="{{ $createdAt->timestamp }}"
             data-prep-time="{{ $totalPrepMinutes }}"
             data-status="{{ $order->status }}">

            <div class="order-header">
                <div>
                    <span class="order-id">#{{ $order->id }}</span>
                    <div>
                        @if($order->status === 'Pending')
                            <span class="status-badge sb-pending"><span class="dot"></span> Pending</span>
                        @elseif($order->status === 'Assigned')
                            <span class="status-badge sb-assigned"><span class="dot"></span> Assigned</span>
                        @elseif($order->status === 'Picked Up')
                            <span class="status-badge sb-picked"><span class="dot"></span> Picked Up</span>
                        @endif
                    </div>
                </div>
                <div class="order-meta">
                    @if($order->table_id)
                        <span class="order-table"><i class="fas fa-chair"></i> Table #{{ $order->table->table_number ?? '?' }}</span>
                    @endif
                    <span class="order-type-badge badge-{{ strtolower(str_replace(' ', '', $order->order_type)) }}">{{ $order->order_type }}</span>
                </div>
            </div>

            {{-- Rider Info --}}
            @if($order->rider)
                <div class="rider-info">
                    <i class="fas fa-motorcycle"></i>
                    <span><strong>{{ $order->rider->name }}</strong> — {{ $order->rider->phone }}</span>
                    @if($order->status === 'Picked Up')
                        <span style="color:#c4b5fd;font-weight:700;font-size:12px;">📦 Out for delivery</span>
                    @endif
                </div>
            @endif

            <div class="timer-section">
                <div>
                    <div class="timer-display on-time" id="timer-{{ $order->id }}">
                        {{ sprintf('%02d:%02d', $totalPrepMinutes, 0) }}
                    </div>
                    <div class="timer-label"><i class="fas fa-stopwatch"></i> {{ $totalPrepMinutes }} min estimated</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px; color:#64748b;"><i class="fas fa-user"></i> {{ $order->customer_name }}</div>
                    <div style="font-size:11px; color:#475569;">{{ $order->created_at->format('h:i A') }}</div>
                </div>
            </div>

            <div class="timer-progress">
                <div class="timer-bar on-time" id="bar-{{ $order->id }}" style="width: 100%;"></div>
            </div>

            <div class="order-items">
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-name">
                            <span class="item-qty">×{{ $item->quantity }}</span>
                            <div>
                                {{ $item->food_name }}
                                @if($item->variant_name)<span class="item-variant">({{ $item->variant_name }})</span>@endif
                            </div>
                        </div>
                        <span class="item-prep"><i class="fas fa-clock"></i> {{ $item->food ? ($item->food->prep_time ?? 15) : 15 }}m</span>
                    </div>
                @endforeach
                @if($order->notes)
                    <div class="order-notes"><i class="fas fa-sticky-note"></i> {{ $order->notes }}</div>
                @endif
            </div>

            <div class="order-actions">
                @if(in_array($order->status, ['Pending', 'Assigned']))
                    <button class="action-btn btn-clear" onclick="clearOrder({{ $order->id }})">
                        <i class="fas fa-times"></i> Clear
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="no-orders"><i class="fas fa-coffee"></i><h2>No Active Orders</h2><p>New orders will appear here</p></div>
    @endforelse
</div>

{{-- PICKED UP (Out for Delivery) --}}
@if($pickedUpOrders->count() > 0)
    <div class="section-label"><i class="fas fa-motorcycle" style="color:#8b5cf6;"></i> Out for Delivery</div>
    <div class="orders-container">
        @foreach($pickedUpOrders as $order)
            <div class="order-card status-picked" id="order-{{ $order->id }}">
                <div class="order-header">
                    <div>
                        <span class="order-id">#{{ $order->id }}</span>
                        <div><span class="status-badge sb-picked"><span class="dot"></span> Picked Up</span></div>
                    </div>
                    <div class="order-meta">
                        @if($order->table_id)<span class="order-table"><i class="fas fa-chair"></i> Table #{{ $order->table->table_number ?? '?' }}</span>@endif
                        <span class="order-type-badge badge-{{ strtolower(str_replace(' ', '', $order->order_type)) }}">{{ $order->order_type }}</span>
                    </div>
                </div>
                @if($order->rider)
                    <div class="rider-info">
                        <i class="fas fa-motorcycle"></i>
                        <span><strong>{{ $order->rider->name }}</strong> — {{ $order->rider->phone }}</span>
                        <span style="color:#c4b5fd;font-weight:700;font-size:12px;">📦 On the way</span>
                    </div>
                @endif
                <div class="order-items">
                    @foreach($order->items as $item)
                        <div class="order-item">
                            <div class="item-name">
                                <span class="item-qty">×{{ $item->quantity }}</span>
                                <div>{{ $item->food_name }}@if($item->variant_name) <span class="item-variant">({{ $item->variant_name }})</span>@endif</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="order-actions">
                    <button class="action-btn btn-clear" onclick="clearOrder({{ $order->id }})">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- CANCELLED ORDERS --}}
@if($cancelledOrders->count() > 0)
    <div class="section-label"><i class="fas fa-times-circle" style="color:#ef4444;"></i> Cancelled</div>
    <div class="orders-container">
        @foreach($cancelledOrders as $order)
            <div class="order-card status-cancelled">
                <div class="order-header">
                    <div>
                        <span class="order-id">#{{ $order->id }}</span>
                        <div><span class="status-badge sb-cancelled"><span class="dot"></span> Cancelled</span></div>
                    </div>
                    <div class="order-meta">
                        <span class="order-type-badge badge-{{ strtolower(str_replace(' ', '', $order->order_type)) }}">{{ $order->order_type }}</span>
                    </div>
                </div>
                @if($order->rider)
                    <div class="rider-info" style="background:rgba(239,68,68,0.08);border-top-color:rgba(239,68,68,0.2);">
                        <i class="fas fa-motorcycle" style="color:#ef4444;"></i>
                        <span>Was with: <strong style="color:#fca5a5;">{{ $order->rider->name }}</strong></span>
                    </div>
                @endif
                <div class="cancelled-info">
                    <i class="fas fa-info-circle"></i>
                    Cancelled at {{ $order->updated_at->format('h:i A') }}
                    · Customer: {{ $order->customer_name }}
                    · Rs. {{ number_format($order->total_amount, 2) }}
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- STOCK CONTROL SIDEBAR -->
<div id="stockPanel" style="position:fixed;bottom:20px;right:20px;z-index:1000;">
    <button onclick="toggleStockPanel()" style="background:#16a34a;color:white;border:none;padding:12px 16px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 15px rgba(22,163,74,0.3);">
        <i class="fas fa-boxes-stacked"></i> Stock Control
    </button>
</div>

<div id="stockPanelContent" style="display:none;position:fixed;bottom:70px;right:20px;width:320px;max-height:60vh;background:#1e293b;border-radius:14px;border:2px solid #334155;overflow:hidden;z-index:999;box-shadow:0 10px 40px rgba(0,0,0,0.4);">
    <div style="padding:12px 16px;background:#16a34a;color:white;font-weight:700;display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fas fa-boxes-stacked"></i> Quick Stock Control</span>
        <button onclick="toggleStockPanel()" style="background:none;border:none;color:white;font-size:16px;cursor:pointer;">×</button>
    </div>
    <div id="stockList" style="padding:10px;overflow-y:auto;max-height:calc(60vh - 50px);">
        <div style="text-align:center;color:#64748b;padding:20px;">Loading items...</div>
    </div>
</div>

<script>
    function toggleStockPanel() {
        var panel = document.getElementById('stockPanelContent');
        if (panel.style.display === 'none') {
            panel.style.display = 'block';
            loadStockItems();
        } else {
            panel.style.display = 'none';
        }
    }

    function loadStockItems() {
        var stockList = document.getElementById('stockList');
        stockList.innerHTML = '<div style="text-align:center;color:#64748b;padding:15px;">Loading...</div>';

        fetch('/admin/inventory-json')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.foods || data.foods.length === 0) {
                    stockList.innerHTML = '<div style="text-align:center;color:#64748b;padding:20px;">No food items found</div>';
                    return;
                }
                var html = '';
                data.foods.forEach(function(food) {
                    var inStock = food.is_in_stock !== undefined ? food.is_in_stock : true;
                    var stockQty = food.stock_quantity !== undefined ? food.stock_quantity : -1;
                    var threshold = food.low_stock_threshold !== undefined ? food.low_stock_threshold : 5;
                    var statusColor = !inStock ? '#ef4444' : (stockQty >= 0 && stockQty <= threshold ? '#f59e0b' : '#10b981');
                    var statusText = !inStock ? 'OUT' : (stockQty == -1 ? '∞' : stockQty);
                    html += '<div style="display:flex;align-items:center;gap:8px;padding:8px;border-bottom:1px solid #334155;">
                        <div style="flex:1;font-size:13px;color:#e2e8f0;">' + food.name + '</div>
                        <div style="color:' + statusColor + ';font-weight:700;font-size:13px;min-width:30px;text-align:center;">' + statusText + '</div>
                        <button onclick="quickToggle(' + food.id + ', ' + (inStock ? 'false' : 'true') + ', this)" style="padding:4px 10px;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.2s;background:' + (inStock ? 'rgba(239,68,68,.15);color:#fca5a5' : 'rgba(16,185,129,.15);color:#6ee7b7') + ';">
                            ' + (inStock ? 'Disable' : 'Enable') + '
                        </button>
                    </div>';
                });
                stockList.innerHTML = html;
            })
            .catch(function(err) {
                console.error('Stock load error:', err);
                stockList.innerHTML = '<div style="text-align:center;color:#ef4444;padding:20px;">Error loading items<br><small>Make sure migration is run</small></div>';
            });
    }

    function quickToggle(foodId, inStock, btn) {
        if (btn) {
            btn.textContent = '...';
            btn.disabled = true;
        }
        fetch('/admin/food/' + foodId + '/toggle-stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_in_stock: inStock })
        })
        .then(function(r) { return r.json(); })
        .then(function() { loadStockItems(); })
        .catch(function(err) {
            console.error('Toggle error:', err);
            if (btn) { btn.textContent = 'Error'; btn.disabled = false; }
            alert('Error updating stock. Check console for details.');
        });
    }
</script>

<script>
    function updateTimers() {
        const now = Math.floor(Date.now() / 1000);
        document.querySelectorAll('.order-card[data-status]').forEach(card => {
            if (card.dataset.status === 'done' || card.dataset.status === 'Cancelled') return;
            const orderId = card.dataset.orderId;
            const created = parseInt(card.dataset.created);
            const prepTime = parseInt(card.dataset.prepTime) * 60;
            const elapsed = now - created;
            const remaining = prepTime - elapsed;
            const timerEl = document.getElementById('timer-' + orderId);
            const barEl = document.getElementById('bar-' + orderId);
            if (!timerEl || !barEl) return;
            if (remaining <= 0) {
                timerEl.innerHTML = '<i class="fas fa-check-circle"></i> READY!';
                timerEl.className = 'timer-display done';
                barEl.style.width = '0%';
                card.dataset.status = 'done';
            } else {
                const mins = Math.floor(remaining / 60);
                const secs = remaining % 60;
                timerEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                const pct = (remaining / prepTime) * 100;
                barEl.style.width = pct + '%';
                if (pct > 50) { timerEl.className = 'timer-display on-time'; barEl.className = 'timer-bar on-time'; }
                else if (pct > 20) { timerEl.className = 'timer-display warning'; barEl.className = 'timer-bar warning'; }
                else { timerEl.className = 'timer-display overdue'; barEl.className = 'timer-bar overdue'; }
            }
        });
    }
    setInterval(updateTimers, 1000);
    updateTimers();

    function clearOrder(orderId) {
        if (!confirm('Mark order #' + orderId + ' as cancelled?')) return;
        fetch('/admin/orders/' + orderId + '/cancel', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(r => r.json()).then(() => { location.reload(); }).catch(() => { alert('Failed.'); });
    }

    setInterval(() => { location.reload(); }, 30000);
</script>
</body>
</html>
