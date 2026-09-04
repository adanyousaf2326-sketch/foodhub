<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
        }

        /* Top Bar */
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
        .kitchen-topbar .logo {
            font-size: 20px;
            font-weight: 800;
            color: #ff6b00;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .kitchen-topbar .stats {
            display: flex;
            gap: 20px;
            font-size: 13px;
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
        }
        .stat-item .count {
            font-weight: 800;
            font-size: 18px;
        }
        .stat-pending .count { color: #f59e0b; }
        .stat-ready .count { color: #10b981; }
        .stat-overdue .count { color: #ef4444; }
        .back-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .back-link:hover { color: white; }

        /* Orders Grid */
        .orders-container {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 16px;
        }

        /* Order Card */
        .order-card {
            background: #1e293b;
            border-radius: 14px;
            border: 2px solid #334155;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeIn 0.4s ease;
        }
        .order-card.urgent {
            border-color: #ef4444;
            animation: fadeIn 0.4s ease, urgentPulse 2s infinite;
        }
        .order-card.ready {
            border-color: #10b981;
            background: #064e3b;
        }
        .order-card.preparing {
            border-color: #f59e0b;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes urgentPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 20px 4px rgba(239, 68, 68, 0.2); }
        }

        /* Order Header */
        .order-header {
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .order-id {
            font-size: 18px;
            font-weight: 800;
        }
        .order-meta {
            text-align: right;
        }
        .order-table {
            font-size: 12px;
            color: #94a3b8;
        }
        .order-type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-dinein { background: #7c3aed20; color: #a78bfa; border: 1px solid #7c3aed40; }
        .badge-delivery { background: #3b82f620; color: #93c5fd; border: 1px solid #3b82f640; }
        .badge-takeaway { background: #f59e0b20; color: #fcd34d; border: 1px solid #f59e0b40; }

        /* Timer */
        .timer-section {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .timer-display {
            font-size: 32px;
            font-weight: 900;
            font-variant-numeric: tabular-nums;
            letter-spacing: 2px;
        }
        .timer-display.on-time { color: #10b981; }
        .timer-display.warning { color: #f59e0b; }
        .timer-display.overdue { color: #ef4444; animation: blink 1s infinite; }
        .timer-display.done { color: #10b981; }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .timer-label {
            font-size: 11px;
            color: #64748b;
        }
        .timer-progress {
            width: 100%;
            height: 4px;
            background: #334155;
            border-radius: 2px;
            overflow: hidden;
            margin: 0 16px;
        }
        .timer-bar {
            height: 100%;
            border-radius: 2px;
            transition: width 1s linear, background 0.3s;
        }
        .timer-bar.on-time { background: linear-gradient(90deg, #10b981, #34d399); }
        .timer-bar.warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .timer-bar.overdue { background: linear-gradient(90deg, #ef4444, #f87171); }

        /* Items List */
        .order-items {
            padding: 8px 16px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 13px;
        }
        .order-item:last-child { border-bottom: none; }
        .item-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .item-qty {
            background: #ff6b0020;
            color: #ff6b00;
            padding: 2px 7px;
            border-radius: 5px;
            font-weight: 800;
            font-size: 12px;
            min-width: 24px;
            text-align: center;
        }
        .item-prep {
            font-size: 11px;
            color: #64748b;
        }
        .item-variant {
            font-size: 11px;
            color: #94a3b8;
            font-style: italic;
        }

        /* Action Buttons */
        .order-actions {
            padding: 10px 16px;
            display: flex;
            gap: 8px;
        }
        .action-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-ready {
            background: #10b981;
            color: white;
        }
        .btn-ready:hover { background: #059669; transform: translateY(-1px); }
        .btn-done {
            background: #6366f1;
            color: white;
        }
        .btn-done:hover { background: #4f46e5; }

        /* Ready Overlay */
        .ready-overlay {
            position: absolute;
            inset: 0;
            background: rgba(16, 185, 129, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            pointer-events: none;
        }
        .ready-badge {
            background: #10b981;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 800;
            animation: bounceIn 0.5s ease;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        /* No Orders */
        .no-orders {
            text-align: center;
            padding: 80px 20px;
            color: #475569;
        }
        .no-orders i { font-size: 60px; margin-bottom: 15px; display: block; }
        .no-orders h2 { color: #64748b; }

        /* Responsive */
        @media (max-width: 768px) {
            .orders-container { grid-template-columns: 1fr; padding: 12px; }
            .kitchen-topbar { flex-wrap: wrap; gap: 10px; padding: 10px 14px; }
            .kitchen-topbar .stats { flex-wrap: wrap; gap: 8px; }
            .timer-display { font-size: 26px; }
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="kitchen-topbar">
    <a href="{{ route('admin.dashboard') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Dashboard
    </a>
    <div class="logo">
        <i class="fas fa-fire"></i> Kitchen Display
    </div>
    <div class="stats">
        <div class="stat-item stat-pending">
            <i class="fas fa-clock"></i>
            <span class="count" id="pendingCount">{{ $pendingOrders->count() }}</span>
            <span>Pending</span>
        </div>
        <div class="stat-item stat-ready">
            <i class="fas fa-check-circle"></i>
            <span class="count" id="readyCount">0</span>
            <span>Ready</span>
        </div>
    </div>
</div>

<!-- Orders -->
<div class="orders-container" id="ordersContainer">
    @forelse($pendingOrders as $order)
        @php
            // Calculate total prep time — take the MAX among all items (kitchen cooks in parallel)
            $maxPrepTime = 0;
            foreach ($order->items as $item) {
                $food = $item->food;
                $itemPrep = $food ? ($food->prep_time ?? 15) : 15;
                $maxPrepTime = max($maxPrepTime, $itemPrep * $item->quantity);
            }
            $maxPrepTime = max($maxPrepTime, 5); // minimum 5 min
            $createdAt = $order->created_at;
            $estimatedEnd = $createdAt->copy()->addMinutes($maxPrepTime);
            $totalPrepMinutes = $maxPrepTime;
        @endphp
        <div class="order-card preparing"
             id="order-{{ $order->id }}"
             data-order-id="{{ $order->id }}"
             data-created="{{ $createdAt->timestamp }}"
             data-prep-time="{{ $totalPrepMinutes }}"
             data-status="preparing">

            <div class="order-header">
                <div>
                    <span class="order-id">#{{ $order->id }}</span>
                </div>
                <div class="order-meta">
                    @if($order->table_id)
                        <span class="order-table"><i class="fas fa-chair"></i> Table #{{ $order->restaurant_table->table_number ?? '?' }}</span>
                    @endif
                    <span class="order-type-badge badge-{{ strtolower(str_replace(' ', '', $order->order_type)) }}">
                        {{ $order->order_type }}
                    </span>
                </div>
            </div>

            <div class="timer-section">
                <div>
                    <div class="timer-display on-time" id="timer-{{ $order->id }}">
                        {{ sprintf('%02d:%02d', $totalPrepMinutes, 0) }}
                    </div>
                    <div class="timer-label">
                        <i class="fas fa-stopwatch"></i> {{ $totalPrepMinutes }} min estimated
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px; color:#64748b;">
                        <i class="fas fa-user"></i> {{ $order->customer_name }}
                    </div>
                    <div style="font-size:11px; color:#475569;">
                        {{ $created_atFormatted ?? $order->created_at->format('h:i A') }}
                    </div>
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
                                @if($item->variant_name)
                                    <span class="item-variant">({{ $item->variant_name }})</span>
                                @endif
                            </div>
                        </div>
                        <span class="item-prep">
                            <i class="fas fa-clock"></i>
                            {{ $item->food ? ($item->food->prep_time ?? 15) : 15 }}m
                        </span>
                    </div>
                @endforeach
                @if($order->notes)
                    <div style="margin-top:6px; padding:6px 8px; background:#1a1a2e; border-radius:6px; font-size:11px; color:#fbbf24;">
                        <i class="fas fa-sticky-note"></i> {{ $order->notes }}
                    </div>
                @endif
            </div>

            <div class="order-actions">
                <button class="action-btn btn-ready" onclick="markReady({{ $order->id }})">
                    <i class="fas fa-check"></i> Mark Ready
                </button>
            </div>
        </div>
    @empty
        <div class="no-orders" style="grid-column: 1 / -1;">
            <i class="fas fa-coffee"></i>
            <h2>No Pending Orders</h2>
            <p>New orders will appear here automatically</p>
        </div>
    @endforelse
</div>

<script>
    // Countdown Timer System
    const orders = document.querySelectorAll('.order-card[data-status="preparing"]');
    const readyCount = document.getElementById('readyCount');
    const pendingCount = document.getElementById('pendingCount');
    let readyTotal = 0;

    function updateTimers() {
        const now = Math.floor(Date.now() / 1000);

        orders.forEach(card => {
            if (card.dataset.status === 'done') return;

            const orderId = card.dataset.orderId;
            const created = parseInt(card.dataset.created);
            const prepTime = parseInt(card.dataset.prepTime) * 60; // convert to seconds
            const elapsed = now - created;
            const remaining = prepTime - elapsed;

            const timerEl = document.getElementById('timer-' + orderId);
            const barEl = document.getElementById('bar-' + orderId);

            if (!timerEl || !barEl) return;

            if (remaining <= 0) {
                // TIME'S UP — Order ready!
                timerEl.textContent = '00:00';
                timerEl.className = 'timer-display done';
                timerEl.innerHTML = '<i class="fas fa-check-circle"></i> READY!';
                barEl.style.width = '0%';
                card.dataset.status = 'done';
                card.className = 'order-card ready';

                // Play sound
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.value = 880;
                    gain.gain.value = 0.3;
                    osc.start();
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                    osc.stop(ctx.currentTime + 0.5);
                } catch(e) {}

                readyTotal++;
                readyCount.textContent = readyTotal;
                pendingCount.textContent = Math.max(0, orders.length - readyTotal);
            } else {
                const mins = Math.floor(remaining / 60);
                const secs = remaining % 60;
                timerEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');

                // Color based on time remaining
                const pct = (remaining / prepTime) * 100;
                barEl.style.width = pct + '%';

                if (pct > 50) {
                    timerEl.className = 'timer-display on-time';
                    barEl.className = 'timer-bar on-time';
                    card.className = 'order-card preparing';
                } else if (pct > 20) {
                    timerEl.className = 'timer-display warning';
                    barEl.className = 'timer-bar warning';
                    card.className = 'order-card preparing';
                } else {
                    timerEl.className = 'timer-display overdue';
                    barEl.className = 'timer-bar overdue';
                    card.className = 'order-card urgent';
                }
            }
        });
    }

    // Update every second
    setInterval(updateTimers, 1000);
    updateTimers();

    // Mark Ready
    function markReady(orderId) {
        if (!confirm('Mark order #' + orderId + ' as READY?')) return;

        fetch('/admin/orders/' + orderId + '/close', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            const card = document.getElementById('order-' + orderId);
            if (card) {
                card.style.transition = 'all 0.5s ease';
                card.style.transform = 'scale(0.9)';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 500);
            }
            readyTotal++;
            readyCount.textContent = readyTotal;
            pendingCount.textContent = Math.max(0, orders.length - readyTotal);
        }).catch(() => {
            alert('Failed to mark order. Please try again.');
        });
    }

    // Auto-refresh every 30 seconds for new orders
    setInterval(() => {
        location.reload();
    }, 30000);
</script>

</body>
</html>
