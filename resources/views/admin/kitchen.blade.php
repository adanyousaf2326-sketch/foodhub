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
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #0f172a; color: white; min-height: 100vh; padding-bottom: 80px; }

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

        .cancelled-info { padding: 8px 16px; font-size: 12px; color: #94a3b8; }
        .cancelled-info i { color: #ef4444; }

        .no-orders { text-align: center; padding: 60px 20px; color: #475569; grid-column: 1 / -1; }
        .no-orders i { font-size: 50px; margin-bottom: 10px; display: block; }

        /* ===== STOCK CONTROL PANEL ===== */
        #stockPanelWrap {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99999;
        }
        #stockPanelContent {
            position: fixed;
            bottom: 70px;
            right: 20px;
            width: 380px;
            max-height: 70vh;
            background: #1e293b;
            border-radius: 14px;
            border: 2px solid #334155;
            overflow: hidden;
            z-index: 99998;
            box-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }
        .stock-header { padding: 12px 16px; background: #16a34a; color: white; font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
        .stock-header button { background: none; border: none; color: white; font-size: 18px; cursor: pointer; padding: 4px; }
        .stock-search { padding: 10px 12px; border-bottom: 1px solid #334155; }
        .stock-search input { width: 100%; padding: 10px 12px 10px 36px; background: #0f172a; border: 1px solid #475569; border-radius: 8px; color: #e2e8f0; font-size: 13px; outline: none; }
        .stock-search input:focus { border-color: #ff6b00; }
        .stock-search-wrap { position: relative; }
        .stock-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 13px; }
        .stock-item { display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-bottom: 1px solid #334155; transition: background 0.2s; }
        .stock-item:hover { background: rgba(255,255,255,.04); }
        .stock-item-name { flex: 1; font-size: 13px; color: #e2e8f0; font-weight: 600; }
        .stock-item-time { font-size: 10px; color: #f59e0b; margin-top: 2px; }
        .stock-item-status { font-weight: 700; font-size: 12px; min-width: 28px; text-align: center; }
        .stock-btn-disable { padding: 5px 10px; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; background: rgba(239,68,68,.12); color: #fca5a5; transition: all 0.2s; }
        .stock-btn-disable:hover { background: rgba(239,68,68,.3); }
        .stock-btn-enable { padding: 5px 10px; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; background: rgba(16,185,129,.12); color: #6ee7b7; transition: all 0.2s; }
        .stock-btn-enable:hover { background: rgba(16,185,129,.3); }
        .stock-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 99997; }
        .stock-overlay.show { display: block; }

        .time-quick-btn {
            padding: 5px 10px;
            background: #1e293b;
            border: 1px solid #475569;
            border-radius: 6px;
            color: #94a3b8;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .time-quick-btn:hover { border-color: #ff6b00; color: #ff6b00; background: rgba(255,107,0,.1); }
        .time-quick-btn.selected { border-color: #16a34a; color: #4ade80; background: rgba(22,163,74,.15); }

        .time-modal { display: none; padding: 12px; border-bottom: 1px solid #334155; background: #0f172a; }
        .time-modal.show { display: block; }

        #stockList { padding: 6px; overflow-y: auto; max-height: calc(70vh - 140px); }

        @media (max-width: 768px) {
            .orders-container { grid-template-columns: 1fr 1fr; padding: 0 8px 8px; gap: 8px; }
            .kitchen-topbar { flex-wrap: wrap; gap: 8px; padding: 8px 12px; }
            .kitchen-topbar .stats { flex-wrap: wrap; gap: 6px; }
            .stat-item { padding: 4px 8px; font-size: 11px; }
            .stat-item .count { font-size: 14px; }
            .order-header { padding: 8px 12px; }
            .order-id { font-size: 16px; }
            .order-items { font-size: 10px; padding: 6px 12px; }
            .order-actions { padding: 6px 12px; }
            .action-btn { padding: 6px 8px; font-size: 11px; }
            .section-label { padding: 10px 12px 6px; font-size: 12px; }
            #stockPanelContent { width: calc(100% - 20px); right: 10px; bottom: 70px; }
        }
        @media (max-width: 480px) {
            .orders-container { grid-template-columns: 1fr; gap: 6px; }
            .order-card { border-radius: 10px; }
            .order-header { padding: 6px 10px; }
            .order-id { font-size: 14px; }
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
        <div class="stat-item stat-pending"><i class="fas fa-clock"></i><span class="count" id="countPending">{{ $pendingOrders->count() }}</span><span>Pending</span></div>
        <div class="stat-item stat-picked"><i class="fas fa-motorcycle"></i><span class="count" id="countPicked">{{ $pickedUpOrders->count() }}</span><span>Picked Up</span></div>
        <div class="stat-item stat-delivered"><i class="fas fa-check-circle"></i><span class="count" id="countReady">{{ $readyOrders->count() }}</span><span>Ready</span></div>
        @if($cancelledOrders->count() > 0)
            <div class="stat-item stat-cancelled"><i class="fas fa-times-circle"></i><span class="count">{{ $cancelledOrders->count() }}</span><span>Cancelled</span></div>
        @endif
    </div>
</div>

{{-- ACTIVE ORDERS --}}
<div id="ordersSection">
<div class="section-label"><i class="fas fa-fire" style="color:#ff6b00;"></i> Active Orders</div>
<div class="orders-container" id="ordersContainer">
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
</div>

{{-- STOCK CONTROL PANEL --}}
<div class="stock-overlay" id="stockOverlay" onclick="closeStockPanel()"></div>
<div id="stockPanelWrap">
    <button id="stockToggleBtn" onclick="openStockPanel()" style="background:#16a34a;color:white;border:none;padding:12px 16px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 15px rgba(22,163,74,0.4);display:flex;align-items:center;gap:8px;transition:transform .2s;">
        <i class="fas fa-boxes-stacked"></i> <span>Stock Control</span>
    </button>
</div>

<div id="stockPanelContent">
    <div class="stock-header">
        <span><i class="fas fa-boxes-stacked"></i> Stock Control</span>
        <button onclick="closeStockPanel()" title="Close">&times;</button>
    </div>
    <div class="stock-search">
        <div class="stock-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="stockSearch" placeholder="🔍 Search items..." oninput="filterStockItems()">
        </div>
    </div>
    <div class="time-modal" id="timeModal">
        <div style="font-size:12px;color:#94a3b8;margin-bottom:8px;">⏰ When will this item be available again?</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
            <button onclick="setTimeQuick(30, this)" class="time-quick-btn">30 min</button>
            <button onclick="setTimeQuick(60, this)" class="time-quick-btn">1 hour</button>
            <button onclick="setTimeQuick(120, this)" class="time-quick-btn">2 hours</button>
            <button onclick="setTimeQuick(240, this)" class="time-quick-btn">4 hours</button>
            <button onclick="setTimeQuick(480, this)" class="time-quick-btn">8 hours</button>
            <button onclick="setTimeQuick(1440, this)" class="time-quick-btn">Tomorrow</button>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <label style="font-size:11px;color:#94a3b8;white-space:nowrap;">Or set time:</label>
            <input type="datetime-local" id="availableAt" style="flex:1;padding:6px 8px;background:#1e293b;border:1px solid #475569;border-radius:6px;color:#e2e8f0;font-size:12px;">
        </div>
        <div style="display:flex;gap:8px;margin-top:8px;">
            <button onclick="confirmDisable()" style="flex:1;padding:8px;background:#ef4444;color:white;border:none;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">✅ Confirm Disable</button>
            <button onclick="cancelDisable()" style="padding:8px 12px;background:#334155;color:#94a3b8;border:none;border-radius:6px;font-size:12px;cursor:pointer;">Cancel</button>
        </div>
    </div>
    <div id="stockList">
        <div style="text-align:center;color:#64748b;padding:20px;">Click "Stock Control" to load items</div>
    </div>
</div>

<script>
/* ===== STOCK CONTROL ===== */
var stockAllItems = [];
var pendingDisableId = null;
var selectedMinutes = null;
var stockPanelOpen = false;

function openStockPanel() {
    stockPanelOpen = true;
    document.getElementById('stockPanelContent').style.display = 'block';
    document.getElementById('stockOverlay').classList.add('show');
    document.getElementById('stockToggleBtn').style.display = 'none';
    loadStockItems();
    console.log('Stock panel opened');
}

function closeStockPanel() {
    stockPanelOpen = false;
    document.getElementById('stockPanelContent').style.display = 'none';
    document.getElementById('stockOverlay').classList.remove('show');
    document.getElementById('timeModal').style.display = 'none';
    document.getElementById('stockToggleBtn').style.display = 'flex';
    pendingDisableId = null;
    selectedMinutes = null;
}

function loadStockItems() {
    var stockList = document.getElementById('stockList');
    stockList.innerHTML = '<div style="text-align:center;color:#64748b;padding:15px;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

    var token = document.querySelector('meta[name="csrf-token"]');
    console.log('Fetching /admin/inventory-json...');

    fetch('/admin/inventory-json', {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json' }
    })
    .then(function(r) {
        console.log('Response status:', r.status);
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(function(data) {
        console.log('Stock data received:', data);
        stockAllItems = data.foods || [];
        renderStockList(stockAllItems);
    })
    .catch(function(err) {
        console.error('Stock load error:', err);
        stockList.innerHTML = '<div style="text-align:center;color:#ef4444;padding:20px;"><i class="fas fa-exclamation-triangle"></i><br>Error loading items<br><small>' + err.message + '</small></div>';
    });
}

function renderStockList(items) {
    var stockList = document.getElementById('stockList');
    if (!items || !items.length) {
        stockList.innerHTML = '<div style="text-align:center;color:#64748b;padding:20px;"><i class="fas fa-inbox"></i><br>No food items found</div>';
        return;
    }
    var html = '';
    items.forEach(function(food) {
        var inStock = food.is_in_stock !== undefined ? food.is_in_stock : true;
        var stockQty = food.stock_quantity !== undefined ? food.stock_quantity : -1;
        var statusColor = !inStock ? '#ef4444' : '#10b981';
        var statusText = !inStock ? 'OUT' : 'OK';
        var availMsg = '';
        if (!inStock && food.available_at) {
            var availDate = new Date(food.available_at);
            var now = new Date();
            if (availDate > now) {
                var diffMs = availDate - now;
                var diffMins = Math.ceil(diffMs / 60000);
                var hours = Math.floor(diffMins / 60);
                var mins = diffMins % 60;
                availMsg = hours > 0 ? '⏰ Available in ' + hours + 'h ' + mins + 'm' : '⏰ Available in ' + mins + ' min';
            } else {
                availMsg = '⏰ Available now (restart needed)';
            }
        } else if (!inStock) {
            availMsg = '⏰ Currently unavailable';
        }

        html += '<div class="stock-item">';
        html += '  <div class="stock-item-name">' + food.name;
        if (availMsg) html += '<div class="stock-item-time">' + availMsg + '</div>';
        html += '  </div>';
        html += '  <div class="stock-item-status" style="color:' + statusColor + ';">' + statusText + '</div>';
        if (inStock) {
            html += '  <button class="stock-btn-disable" onclick="startDisable(' + food.id + ', \'' + food.name.replace(/'/g, "\\'") + '\')">Disable</button>';
        } else {
            html += '  <button class="stock-btn-enable" onclick="quickEnable(' + food.id + ', this)">Enable</button>';
        }
        html += '</div>';
    });
    stockList.innerHTML = html;
}

function filterStockItems() {
    var query = document.getElementById('stockSearch').value.toLowerCase();
    var filtered = stockAllItems.filter(function(f) {
        return f.name.toLowerCase().indexOf(query) !== -1;
    });
    renderStockList(filtered);
}

function startDisable(foodId, foodName) {
    pendingDisableId = foodId;
    selectedMinutes = null;
    document.getElementById('availableAt').value = '';
    document.querySelectorAll('.time-quick-btn').forEach(function(b) { b.classList.remove('selected'); });
    document.getElementById('timeModal').style.display = 'block';
}

function cancelDisable() {
    pendingDisableId = null;
    document.getElementById('timeModal').style.display = 'none';
}

function setTimeQuick(minutes, btn) {
    selectedMinutes = minutes;
    document.querySelectorAll('.time-quick-btn').forEach(function(b) { b.classList.remove('selected'); });
    if (btn) btn.classList.add('selected');
    var dt = new Date(Date.now() + minutes * 60000);
    var formatted = dt.getFullYear() + '-' + String(dt.getMonth()+1).padStart(2,'0') + '-' + String(dt.getDate()).padStart(2,'0') + 'T' + String(dt.getHours()).padStart(2,'0') + ':' + String(dt.getMinutes()).padStart(2,'0');
    document.getElementById('availableAt').value = formatted;
}

function confirmDisable() {
    if (!pendingDisableId) return;
    var btn = event.target;
    btn.textContent = 'Disabling...';
    btn.disabled = true;

    var body = { is_in_stock: false };
    var dtVal = document.getElementById('availableAt').value;

    if (selectedMinutes) {
        body.available_in_minutes = selectedMinutes;
    } else if (dtVal) {
        body.available_at = dtVal;
    }

    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch('/admin/food/' + pendingDisableId + '/toggle-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(body),
        credentials: 'same-origin'
    })
    .then(function(r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(function(data) {
        console.log('Disable success:', data);
        document.getElementById('timeModal').style.display = 'none';
        pendingDisableId = null;
        selectedMinutes = null;
        loadStockItems();
        btn.textContent = '✅ Confirm Disable';
        btn.disabled = false;
    })
    .catch(function(err) {
        console.error('Toggle error:', err);
        btn.textContent = '✅ Confirm Disable';
        btn.disabled = false;
        alert('Error disabling item: ' + err.message);
    });
}

function quickEnable(foodId, btn) {
    if (btn) { btn.textContent = '...'; btn.disabled = true; }

    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch('/admin/food/' + foodId + '/toggle-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ is_in_stock: true }),
        credentials: 'same-origin'
    })
    .then(function(r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(function(data) {
        console.log('Enable success:', data);
        loadStockItems();
    })
    .catch(function(err) {
        console.error('Enable error:', err);
        if (btn) { btn.textContent = 'Enable'; btn.disabled = false; }
        alert('Error enabling item: ' + err.message);
    });
}

/* ===== TIMER COUNTDOWN ===== */
function updateTimers() {
    var now = Math.floor(Date.now() / 1000);
    document.querySelectorAll('.order-card[data-status]').forEach(function(card) {
        var st = card.dataset.status;
        if (st === 'done' || st === 'Cancelled') return;
        var orderId = card.dataset.orderId;
        var created = parseInt(card.dataset.created);
        var prepTime = parseInt(card.dataset.prepTime) * 60;
        var elapsed = now - created;
        var remaining = prepTime - elapsed;
        var timerEl = document.getElementById('timer-' + orderId);
        var barEl = document.getElementById('bar-' + orderId);
        if (!timerEl || !barEl) return;
        if (remaining <= 0) {
            timerEl.innerHTML = '<i class="fas fa-check-circle"></i> READY!';
            timerEl.className = 'timer-display done';
            barEl.style.width = '0%';
            card.dataset.status = 'done';
        } else {
            var mins = Math.floor(remaining / 60);
            var secs = remaining % 60;
            timerEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            var pct = (remaining / prepTime) * 100;
            barEl.style.width = pct + '%';
            if (pct > 50) { timerEl.className = 'timer-display on-time'; barEl.className = 'timer-bar on-time'; }
            else if (pct > 20) { timerEl.className = 'timer-display warning'; barEl.className = 'timer-bar warning'; }
            else { timerEl.className = 'timer-display overdue'; barEl.className = 'timer-bar overdue'; }
        }
    });
}
setInterval(updateTimers, 1000);
updateTimers();

/* ===== CLEAR ORDER ===== */
function clearOrder(orderId) {
    if (!confirm('Mark order #' + orderId + ' as cancelled?')) return;
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    fetch('/admin/orders/' + orderId + '/cancel', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    }).then(function(r) { return r.json(); }).then(function() {
        location.reload();
    }).catch(function() { alert('Failed to cancel order.'); });
}

/* ===== AUTO REFRESH (AJAX, no page reload) ===== */
setInterval(function() {
    if (stockPanelOpen) return;
    fetch('/admin/kitchen', { credentials: 'same-origin', headers: { 'Accept': 'text/html' } })
    .then(function(r) { return r.text(); })
    .then(function(html) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var newContainer = doc.getElementById('ordersContainer');
        var oldContainer = document.getElementById('ordersContainer');
        if (newContainer && oldContainer) {
            var newCount = newContainer.querySelectorAll('.order-card').length;
            var oldCount = oldContainer.querySelectorAll('.order-card').length;
            if (newCount !== oldCount) {
                // Auto-print new orders before reload
                autoPrintNewOrders(doc, oldContainer);
                location.reload();
            }
        }
        var newPending = doc.getElementById('countPending');
        var newPicked = doc.getElementById('countPicked');
        var newReady = doc.getElementById('countReady');
        if (newPending) document.getElementById('countPending').textContent = newPending.textContent;
        if (newPicked) document.getElementById('countPicked').textContent = newPicked.textContent;
        if (newReady) document.getElementById('countReady').textContent = newReady.textContent;
    })
    .catch(function() {});
}, 15000);

/* ===== THERMAL PRINTER AUTO-PRINT ===== */
var printerEnabled = localStorage.getItem('kitchen_printer') === 'true';
var printedOrderIds = JSON.parse(localStorage.getItem('printed_orders') || '[]');

function togglePrinter() {
    printerEnabled = !printerEnabled;
    localStorage.setItem('kitchen_printer', printerEnabled);
    var btn = document.getElementById('printerToggle');
    if (btn) {
        btn.style.background = printerEnabled ? '#16a34a' : 'rgba(255,255,255,.07)';
        btn.title = printerEnabled ? 'Printer ON - Auto printing' : 'Printer OFF - Click to enable';
    }
    if (printerEnabled) {
        // Test print to detect printer
        printReceipt({ id: 0, items: [{qty: 1, name: 'PRINTER TEST', variant: null}], type: 'Dine In', table: null, customer: 'Test', time: new Date().toLocaleTimeString(), notes: 'Printer connected!' });
    }
}

function autoPrintNewOrders(newDoc, oldContainer) {
    if (!printerEnabled) return;
    var oldIds = Array.from(oldContainer.querySelectorAll('.order-card')).map(function(c) { return c.dataset.orderId; });
    var newCards = newDoc.querySelectorAll('.order-card');
    newCards.forEach(function(card) {
        var id = card.dataset.orderId;
        if (oldIds.indexOf(id) === -1 && printedOrderIds.indexOf(id) === -1) {
            // New order found - build receipt data
            var items = [];
            card.querySelectorAll('.order-item').forEach(function(item) {
                var qty = item.querySelector('.item-qty');
                var name = item.querySelector('.item-name div');
                items.push({
                    qty: qty ? qty.textContent.replace('×', '') : '1',
                    name: name ? name.textContent.trim() : 'Unknown',
                    variant: null
                });
            });
            var orderData = {
                id: id,
                items: items,
                type: card.querySelector('.order-type-badge') ? card.querySelector('.order-type-badge').textContent : 'Dine In',
                table: card.querySelector('.order-table') ? card.querySelector('.order-table').textContent.trim() : null,
                customer: card.querySelector('[style*="color:#64748b"]') ? card.querySelector('[style*="color:#64748b"]').textContent.trim() : 'Customer',
                time: new Date().toLocaleTimeString(),
                notes: card.querySelector('.order-notes') ? card.querySelector('.order-notes').textContent.trim() : null
            };
            printReceipt(orderData);
            printedOrderIds.push(id);
            localStorage.setItem('printed_orders', JSON.stringify(printedOrderIds));
        }
    });
}

function printReceipt(order) {
    var lines = [];
    lines.push('================================');
    lines.push('         FOODHUB KITCHEN');
    lines.push('================================');
    lines.push('Order #' + order.id);
    lines.push('Type: ' + order.type);
    if (order.table) lines.push('Table: ' + order.table);
    lines.push('Customer: ' + order.customer);
    lines.push('Time: ' + order.time);
    lines.push('--------------------------------');
    order.items.forEach(function(item) {
        lines.push('x' + item.qty + '  ' + item.name);
        if (item.variant) lines.push('      (' + item.variant + ')');
    });
    lines.push('--------------------------------');
    if (order.notes) lines.push('NOTES: ' + order.notes);
    lines.push('================================');
    lines.push('');

    var receiptText = lines.join('\n');

    // Method 1: Web Print API (auto-detects thermal printer)
    var printWindow = window.open('', '_blank', 'width=300,height=600');
    if (printWindow) {
        printWindow.document.write('<html><head><title>Order #' + order.id + '</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: 80mm auto; margin: 2mm; }');
        printWindow.document.write('body { font-family: monospace; font-size: 12px; white-space: pre; margin: 0; padding: 4px; width: 72mm; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write(receiptText);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        setTimeout(function() { printWindow.print(); }, 500);
        setTimeout(function() { printWindow.close(); }, 2000);
    } else {
        // Fallback: use iframe
        var iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.top = '-9999px';
        iframe.style.left = '-9999px';
        iframe.style.width = '80mm';
        document.body.appendChild(iframe);
        var doc = iframe.contentWindow.document;
        doc.open();
        doc.write('<html><head><style>@page{size:80mm auto;margin:2mm;}body{font-family:monospace;font-size:12px;white-space:pre;margin:0;padding:4px;width:72mm;}</style></head><body>');
        doc.write(receiptText);
        doc.write('</body></html>');
        doc.close();
        setTimeout(function() { iframe.contentWindow.print(); }, 500);
        setTimeout(function() { document.body.removeChild(iframe); }, 3000);
    }
}
</script>

<!-- Printer toggle button -->
<div style="position:fixed;top:0;right:80px;z-index:10001;">
    <button id="printerToggle" onclick="togglePrinter()" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);color:white;padding:6px 10px;border-radius:8px;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:6px;" title="Toggle Auto-Print">
        <i class="fas fa-print"></i> <span style="font-size:11px;">Printer</span>
    </button>
</div>

<script>
// Set initial printer button state from localStorage
(function() {
    var pe = localStorage.getItem('kitchen_printer') === 'true';
    var btn = document.getElementById('printerToggle');
    if (btn && pe) {
        btn.style.background = '#16a34a';
        btn.title = 'Printer ON - Auto printing new orders';
    }
})();
</script>
</body>
</html>
