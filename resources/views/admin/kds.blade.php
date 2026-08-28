<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>👨‍🍳 Kitchen Display - FoodHub Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: white; min-height: 100vh; }
        .kds-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; background: #1e293b; border-bottom: 2px solid #334155; }
        .kds-header h1 { font-size: 20px; }
        .kds-stats { display: flex; gap: 20px; }
        .kds-stat { padding: 6px 14px; border-radius: 8px; font-weight: bold; font-size: 13px; }
        .kds-stat.pending { background: #92400e; color: #fde68a; }
        .kds-stat.preparing { background: #1e40af; color: #bfdbfe; }
        .kds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; padding: 20px 24px; }
        .order-card { background: #1e293b; border-radius: 12px; border: 2px solid #334155; overflow: hidden; transition: all .3s; }
        .order-card.urgent { border-color: #ef4444; box-shadow: 0 0 20px rgba(239,68,68,.3); }
        .order-card.preparing { border-color: #3b82f6; }
        .order-card-header { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #0f172a; }
        .order-id { font-size: 18px; font-weight: 800; }
        .order-timer { font-size: 14px; font-weight: bold; padding: 4px 10px; border-radius: 20px; }
        .timer-normal { background: #166534; color: #86efac; }
        .timer-warning { background: #92400e; color: #fde68a; }
        .timer-critical { background: #991b1b; color: #fecaca; animation: pulse 1s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .6; } }
        .order-type-badge { font-size: 11px; padding: 3px 8px; border-radius: 6px; font-weight: bold; }
        .type-dinein { background: #7c2d12; color: #fed7aa; }
        .type-delivery { background: #1e3a5f; color: #bfdbfe; }
        .type-takeaway { background: #065f46; color: #a7f3d0; }
        .order-items { padding: 12px 16px; }
        .order-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #334155; font-size: 14px; }
        .order-item:last-child { border-bottom: none; }
        .order-item-qty { font-weight: 800; color: #ff6b00; min-width: 30px; }
        .order-item-name { flex: 1; padding-left: 8px; }
        .order-item-price { color: #9ca3af; font-size: 12px; }
        .order-customer { padding: 8px 16px; font-size: 12px; color: #94a3b8; border-top: 1px solid #334155; }
        .order-actions { display: flex; gap: 8px; padding: 12px 16px; }
        .kds-btn { flex: 1; padding: 10px; border: none; border-radius: 8px; font-weight: bold; font-size: 13px; cursor: pointer; transition: all .2s; }
        .btn-prepare { background: #2563eb; color: white; }
        .btn-prepare:hover { background: #1d4ed8; }
        .btn-done { background: #16a34a; color: white; }
        .btn-done:hover { background: #15803d; }
        .btn-skip { background: #374151; color: #9ca3af; }
        .btn-skip:hover { background: #4b5563; }
        .no-orders { text-align: center; padding: 80px 20px; color: #64748b; }
        .no-orders h2 { font-size: 48px; margin-bottom: 16px; }
        .kds-back { color: #94a3b8; text-decoration: none; font-size: 14px; font-weight: 600; }
        .kds-back:hover { color: white; }
        .notes { background: #1a1a2e; border-left: 3px solid #f59e0b; padding: 8px 12px; margin: 8px 16px; border-radius: 0 8px 8px 0; font-size: 12px; color: #fbbf24; }
        @media (max-width: 768px) { .kds-grid { grid-template-columns: 1fr; padding: 10px; } }
    </style>
</head>
<body>
    <div class="kds-header">
        <div style="display:flex;align-items:center;gap:16px;">
            <a href="{{ route('admin.dashboard') }}" class="kds-back">← Dashboard</a>
            <h1>👨‍🍳 Kitchen Display</h1>
        </div>
        <div class="kds-stats">
            <div class="kds-stat pending">🕐 Pending: <span id="pendingCount">{{ $pendingOrders->where('status', 'Pending')->count() }}</span></div>
            <div class="kds-stat preparing">👨‍🍳 Preparing: <span id="preparingCount">{{ $pendingOrders->where('status', 'Preparing')->count() }}</span></div>
        </div>
    </div>

    <div class="kds-grid" id="kdsGrid">
        @forelse($pendingOrders as $order)
            @php
                $elapsed = now()->diffInSeconds($order->created_at);
                $isUrgent = $elapsed > 600; // >10 min
                $isWarning = $elapsed > 300; // >5 min
                $timerClass = $isUrgent ? 'timer-critical' : ($isWarning ? 'timer-warning' : 'timer-normal');
                $cardClass = $isUrgent ? 'urgent' : ($order->status === 'Preparing' ? 'preparing' : '');
            @endphp
            <div class="order-card {{ $cardClass }}" id="order-{{ $order->id }}" data-created="{{ $order->created_at->timestamp }}">
                <div class="order-card-header">
                    <div>
                        <span class="order-id">#{{ $order->id }}</span>
                        <span class="order-type-badge @if($order->order_type === 'Dine In') type-dinein @elseif($order->order_type === 'Delivery') type-delivery @else type-takeaway @endif">
                            @if($order->order_type === 'Dine In') 🍽️ Dine In
                            @elseif($order->order_type === 'Delivery') 🛵 Delivery
                            @else 🥡 Takeaway @endif
                        </span>
                    </div>
                    <span class="order-timer {{ $timerClass }}" id="timer-{{ $order->id }}">00:00</span>
                </div>

                <div class="order-items">
                    @foreach($order->items as $item)
                        <div class="order-item">
                            <span class="order-item-qty">×{{ $item->quantity }}</span>
                            <span class="order-item-name">{{ $item->food_name }}</span>
                            <span class="order-item-price">Rs. {{ number_format($item->subtotal, 0) }}</span>
                        </div>
                    @endforeach
                </div>

                @if($order->notes)
                    <div class="notes">📝 {{ $order->notes }}</div>
                @endif

                <div class="order-customer">
                    👤 {{ $order->customer_name }} · 📞 {{ $order->phone }} · Rs. {{ number_format($order->total_amount, 0) }}
                </div>

                <div class="order-actions">
                    @if($order->status === 'Pending')
                        <button class="kds-btn btn-prepare" onclick="updateOrderStatus({{ $order->id }}, 'Preparing')">👨‍🍳 Start Preparing</button>
                    @else
                        <button class="kds-btn btn-done" onclick="updateOrderStatus({{ $order->id }}, 'Completed')">✅ Done</button>
                    @endif
                    <button class="kds-btn btn-skip" onclick="updateOrderStatus({{ $order->id }}, 'Cancelled')">✕ Skip</button>
                </div>
            </div>
        @empty
            <div class="no-orders" style="grid-column:1/-1;">
                <h2>🎉</h2>
                <p style="font-size:18px;">All caught up! No pending orders.</p>
            </div>
        @endforelse
    </div>

    <script>
        function updateOrderStatus(orderId, status) {
            var token = document.querySelector('meta[name="csrf-token"]').content;
            fetch('/admin/orders/' + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ status: status })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var card = document.getElementById('order-' + orderId);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(function() { card.remove(); updateCounts(); }, 300);
                }
            })
            .catch(function() {
                // Fallback: reload page
                location.reload();
            });
        }

        function updateCounts() {
            var cards = document.querySelectorAll('.order-card');
            var pending = 0, preparing = 0;
            cards.forEach(function(c) {
                if (c.querySelector('.btn-prepare')) pending++;
                else preparing++;
            });
            document.getElementById('pendingCount').textContent = pending;
            document.getElementById('preparingCount').textContent = preparing;
        }

        // Update timers every second
        setInterval(function() {
            var cards = document.querySelectorAll('.order-card');
            cards.forEach(function(card) {
                var created = parseInt(card.getAttribute('data-created'));
                var now = Math.floor(Date.now() / 1000);
                var elapsed = now - created;
                var mins = Math.floor(elapsed / 60);
                var secs = elapsed % 60;
                var timer = card.querySelector('.order-timer');
                if (timer) {
                    timer.textContent = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
                    timer.className = 'order-timer ' + (elapsed > 600 ? 'timer-critical' : elapsed > 300 ? 'timer-warning' : 'timer-normal');
                }
                // Auto-urgent styling
                if (elapsed > 600) card.classList.add('urgent');
            });
        }, 1000);

        // Poll for new orders every 10 seconds
        setInterval(function() {
            fetch('/admin/kds-json', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                data.orders.forEach(function(order) {
                    if (!document.getElementById('order-' + order.id)) {
                        location.reload();
                    }
                });
            })
            .catch(function() {});
        }, 10000);
    </script>
</body>
</html>
