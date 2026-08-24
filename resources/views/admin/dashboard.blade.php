<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FoodHub</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            color: #222;
        }

        .topbar {
            width: 100%;
            background: #111827;
            color: white;
            padding: 0 30px;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            white-space: nowrap;
        }

        .logo span {
            color: #ff6b00;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav a {
            text-decoration: none;
            color: #d1d5db;
            padding: 11px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .nav a:hover,
        .nav .active {
            background: #ff6b00;
            color: white;
        }

        .nav .website-btn {
            background: #16a34a;
            color: white;
        }

        .container {
            max-width: 1250px;
            margin: auto;
            padding: 30px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 30px;
        }

        .header p {
            color: #777;
            margin-top: 7px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 18px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .stat-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-title {
            color: #777;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 27px;
            font-weight: bold;
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 27px;
        }

        .icon-orange { background: #ffedd5; }
        .icon-blue { background: #dbeafe; }
        .icon-green { background: #dcfce7; }
        .icon-purple { background: #ede9fe; }

        .section-title {
            font-size: 21px;
            margin: 30px 0 18px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .action {
            background: white;
            color: #222;
            padding: 20px;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 5px 20px rgba(0,0,0,.06);
            transition: .2s;
        }

        .action:hover {
            transform: translateY(-3px);
        }

        .action-icon {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .action h3 {
            margin-bottom: 5px;
        }

        .action p {
            color: #777;
            font-size: 14px;
        }

        .order-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 12px;
            background: white;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,.06);
        }

        .order-filter input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
        }

        .order-filter input[name="search"] {
            min-width: 255px;
        }

        .order-filter label {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .filter-btn,
        .clear-filter {
            border: none;
            padding: 11px 15px;
            border-radius: 7px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .filter-btn {
            background: #ff6b00;
        }

        .clear-filter {
            background: #6b7280;
        }

        .filter-result {
            color: #555;
            margin-bottom: 15px;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
        }

        .status,
        .order-type {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .preparing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .completed {
            background: #dcfce7;
            color: #166534;
        }

        .delivered {
            background: #dbeafe;
            color: #1e40af;
        }

        .cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .dine-in {
            background: #fef3c7;
            color: #92400e;
        }

        .takeaway {
            background: #dcfce7;
            color: #166534;
        }

        .delivery {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .view {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .empty {
            padding: 50px 20px;
            text-align: center;
            color: #777;
        }

        @media (max-width: 1000px) {
            .topbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .container {
                padding: 15px;
            }

            .cards,
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .nav {
                justify-content: flex-start;
                flex-wrap: nowrap;
                overflow-x: auto;
            }

            .nav a {
                white-space: nowrap;
            }

            .order-filter input[name="search"] {
                min-width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
</head>

<body>

@include('admin.partials.topbar')

<div class="container">

    <div class="header">
        <h1>🍔 FoodHub Hotel</h1>
    </div>

    <div class="cards">
        <div class="card stat-card">
            <div>
                <div class="stat-title">Total Categories</div>
                <div class="stat-number">{{ $totalCategories }}</div>
            </div>

            <div class="stat-icon icon-orange">📂</div>
        </div>

        <div class="card stat-card">
            <div>
                <div class="stat-title">Total Food</div>
                <div class="stat-number">{{ $totalFood }}</div>
            </div>

            <div class="stat-icon icon-blue">🍔</div>
        </div>

        <div class="card stat-card">
            <div>
                <div class="stat-title">Total Orders</div>
                <div class="stat-number">{{ $totalOrders }}</div>
            </div>

            <div class="stat-icon icon-green">🛒</div>
        </div>

        <div class="card stat-card">
            <div>
                <div class="stat-title">Today Revenue</div>
                <div class="stat-number">
                    Rs. {{ number_format($todayRevenue, 0) }}
                </div>
            </div>

            <div class="stat-icon icon-purple">💰</div>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <div class="stat-title">Available Food</div>
            <div class="stat-number">{{ $availableFood }}</div>
        </div>

        <div class="card">
            <div class="stat-title">Pending Orders</div>
            <div class="stat-number">{{ $pendingOrders }}</div>
        </div>

        <div class="card">
            <div class="stat-title">Completed Orders</div>
            <div class="stat-number">{{ $completedOrders }}</div>
        </div>

        <div class="card">
            <div class="stat-title">Total Revenue</div>
            <div class="stat-number">
                Rs. {{ number_format($totalRevenue, 0) }}
            </div>
        </div>
    </div>

    <h2 class="section-title">Quick Actions</h2>

    <div class="quick-actions">
        <a href="{{ route('admin.orders.index', ['type' => 'Dine In']) }}"
           class="action">
            <div class="action-icon">🍽️</div>
            <h3>Dine In Orders</h3>
            <p>{{ $dineInOrders }} dine-in orders</p>
        </a>

        <a href="{{ route('admin.orders.index', ['type' => 'Takeaway']) }}"
           class="action">
            <div class="action-icon">🥡</div>
            <h3>Takeaway Orders</h3>
            <p>{{ $takeAwayOrders }} takeaway orders</p>
        </a>

        <a href="{{ route('admin.orders.index', ['type' => 'Delivery']) }}"
           class="action">
            <div class="action-icon">🛵</div>
            <h3>Delivery Orders</h3>
            <p>{{ $deliveryOrders }} delivery orders</p>
        </a>
    </div>

    <h2 class="section-title">Search Orders</h2>

    <form method="GET" action="{{ route('admin.dashboard') }}"
          class="order-filter">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Order ID, customer, phone, status..."
        >

        <div>
            <label>From Date</label>
            <input
                type="date"
                name="from_date"
                value="{{ request('from_date') }}"
            >
        </div>

        <div>
            <label>From Time</label>
            <input
                type="time"
                name="from_time"
                value="{{ request('from_time') }}"
            >
        </div>

        <div>
            <label>To Date</label>
            <input
                type="date"
                name="to_date"
                value="{{ request('to_date') }}"
            >
        </div>

        <div>
            <label>To Time</label>
            <input
                type="time"
                name="to_time"
                value="{{ request('to_time') }}"
            >
        </div>

        <button type="submit" class="filter-btn">
            🔍 Search
        </button>

        <a href="{{ route('admin.dashboard') }}" class="clear-filter">
            Clear
        </a>
    </form>

    <p class="filter-result">
        Selected period / search result:
        <strong>{{ $filteredOrdersCount }}</strong> orders
    </p>

    <div class="table-card">
        <div class="table-header">
            <h2>Recent Orders</h2>

            <span>
                {{ $filteredOrdersCount }} Orders
            </span>
        </div>

        @if($recentOrders->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Order Type</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date / Time</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentOrders as $order)
                        @php
                            $statusClass = match($order->status) {
                                'Completed' => 'completed',
                                'Delivered' => 'delivered',
                                'Cancelled' => 'cancelled',
                                'Preparing' => 'preparing',
                                default => 'pending',
                            };

                            $typeClass = match($order->order_type) {
                                'Dine In' => 'dine-in',
                                'Delivery' => 'delivery',
                                default => 'takeaway',
                            };
                        @endphp

                        <tr>
                            <td>#{{ $order->id }}</td>

                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                            </td>

                            <td>{{ $order->phone }}</td>

                            <td>
                                @if($order->order_type === 'Dine In')
                                    <span class="order-type dine-in">
                                        🍽️ Dine In
                                    </span>
                                @elseif($order->order_type === 'Delivery')
                                    <span class="order-type delivery">
                                        🛵 Delivery
                                    </span>
                                @else
                                    <span class="order-type takeaway">
                                        🥡 Takeaway
                                    </span>
                                @endif
                            </td>

                            <td>
                                Rs. {{ number_format($order->total_amount, 2) }}
                            </td>

                            <td>{{ $order->payment_method }}</td>

                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td>
                                {{ $order->created_at->format('d M Y') }}
                                <br>
                                <small>{{ $order->created_at->format('h:i A') }}</small>
                            </td>

                            <td>
                               @if(!in_array($order->status, ['Completed', 'Delivered', 'Cancelled']))
    <a href="{{ route('admin.orders.bill', $order) }}" class="view">
        💳 Bill
    </a>
@else
    <a href="{{ route('admin.orders.show', $order) }}" class="view">
        View
    </a>
@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <div style="
    display:flex;
    justify-content:flex-end;
    gap:12px;
    padding:18px 20px;
    font-size:18px;
    font-weight:bold;
    background:#fff7ed;
    color:#c2410c;
">
    <span>Total Amount:</span>

    <span>
        Rs. {{ number_format($filteredOrdersTotal, 2) }}
    </span>
</div>
        @else
        
            <div class="empty">
                <h2>📋 No Orders Found</h2>
                <p>Search ya selected date/time range mein koi order nahi mila.</p>
            </div>
        @endif
    </div>
</div>

</body>
</html>
