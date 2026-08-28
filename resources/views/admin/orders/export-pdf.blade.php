<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub Orders Report</title>
    <style>
    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap");
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background: #f4f6f9; color: #222; padding: 30px; }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #111827;
        }
        .report-header h1 { font-size: 28px; color: #111827; }
        .report-header .brand { color: #ff6b00; font-weight: bold; }
        .report-header p { color: #777; margin-top: 5px; font-size: 14px; }
        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .summary-box {
            flex: 1;
            min-width: 150px;
            background: white;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            text-align: center;
        }
        .summary-box .label { color: #777; font-size: 12px; margin-bottom: 5px; }
        .summary-box .value { font-size: 22px; font-weight: bold; color: #111827; }
        .summary-box .value.orange { color: #ff6b00; }
        .summary-box .value.green { color: #16a34a; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #eee; font-size: 13px; }
        th { background: #111827; color: white; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
        tr:hover { background: #fafafa; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .pending { background: #fef3c7; color: #92400e; }
        .preparing { background: #dbeafe; color: #1d4ed8; }
        .completed { background: #dcfce7; color: #166534; }
        .delivered { background: #dbeafe; color: #1e40af; }
        .cancelled { background: #fee2e2; color: #991b1b; }
        .print-btn {
            display: inline-block;
            padding: 12px 24px;
            background: #ff6b00;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .print-btn:hover { background: #e85f00; }
        .footer-note {
            text-align: center;
            margin-top: 25px;
            color: #999;
            font-size: 12px;
        }
        @media print {
            body { padding: 0; background: white; }
            .print-btn { display: none; }
            .report-header { border-bottom-color: #000; }
            th { background: #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table { box-shadow: none; border: 1px solid #ddd; }
            .summary-box { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>

    <div class="report-header">
        <h1>🍔 <span class="brand">FoodHub</span> Orders Report</h1>
        <p>Date Range: {{ $dateRange }}</p>
        <p>Generated: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="label">Total Orders</div>
            <div class="value">{{ $orders->count() }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Pending</div>
            <div class="value" style="color:#d97706">{{ $orders->where('status', 'Pending')->count() }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Completed</div>
            <div class="value green">{{ $orders->whereIn('status', ['Completed', 'Delivered'])->count() }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Revenue</div>
            <div class="value orange">Rs. {{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date / Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                @php
                    $statusClass = match($order->status) {
                        'Completed' => 'completed',
                        'Delivered' => 'delivered',
                        'Cancelled' => 'cancelled',
                        'Preparing' => 'preparing',
                        default => 'pending',
                    };
                @endphp
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->order_type }}</td>
                    <td><strong>Rs. {{ number_format($order->total_amount, 2) }}</strong></td>
                    <td>{{ $order->payment_method }}</td>
                    <td><span class="status {{ $statusClass }}">{{ $order->status }}</span></td>
                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:#777;padding:40px;">No orders found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        🍔 FoodHub Hotel &mdash; Report auto-generated on {{ now()->format('d M Y h:i A') }}
    </div>

</div>
</div>
    <script src="{{ asset('js/scroll-animations.js') }}"></script>
</body>
</html>
