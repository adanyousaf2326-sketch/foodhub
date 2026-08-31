<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bill #{{ $order->id }} - FoodHub</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            color: #111827;
        }

        .container {
            max-width: 900px;
            margin: 35px auto;
            padding: 20px;
        }

        .bill-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
        }

        .bill-header {
            padding: 25px;
            background: #111827;
            color: white;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
        }

        .bill-header h1 {
            margin: 0 0 7px;
            font-size: 25px;
        }

        .bill-header p {
            margin: 0;
            color: #d1d5db;
        }

        .content {
            padding: 25px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
        }

        .info-label {
            color: #777;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .info-value {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 13px 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f8fafc;
            color: #555;
            font-size: 13px;
        }

        .right {
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 22px;
            padding: 20px;
            background: #fff7ed;
            border-radius: 10px;
            color: #c2410c;
            font-size: 22px;
            font-weight: bold;
        }

        .payment-box {
            margin-top: 25px;
            padding: 22px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .payment-box label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .payment-box input {
            width: 100%;
            padding: 13px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 17px;
        }

        .change-box {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding: 15px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 22px;
        }

        .btn {
            border: none;
            padding: 13px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-close {
            background: #16a34a;
            color: white;
        }

        .btn-back {
            background: #6b7280;
            color: white;
        }

        .error {
            margin-top: 12px;
            color: #b91c1c;
            font-size: 13px;
        }

        @media (max-width: 650px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .bill-header {
                flex-direction: column;
                align-items: flex-start;
            }

            table {
                min-width: 600px;
            }

            .table-wrap {
                overflow-x: auto;
            }
        }
        @media print {
        body { background: white; }
        .topbar, .actions, .payment-box, .cart-overlay, .draggable-cart-btn, .toast { display: none !important; }
        .container { margin: 0; padding: 15px; max-width: 100%; }
        .bill-card { box-shadow: none; border-radius: 0; border: none; }
        .bill-header { background: #111827 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .content { padding: 15px; }
        .total-row { background: #fff7ed !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        table { min-width: auto; }
    }
</style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>

<body>

@include('admin.partials.topbar')

<div class="container">
    <div class="bill-card">

        <div class="bill-header">
            <div>
                <h1>🧾 FoodHub Bill</h1>
                <p>Order #{{ $order->id }}</p>
            </div>

            <div>
                {{ $order->created_at->format('d M Y, h:i A') }}
            </div>
        </div>

        <div class="content">

            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Customer</div>
                    <div class="info-value">{{ $order->customer_name }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $order->phone }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Order Type</div>
                    <div class="info-value">{{ $order->order_type }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">{{ $order->payment_method }}</div>
                </div>

                @if($order->order_type === 'Dine In')
                    <div class="info-box">
                        <div class="info-label">Table</div>
                        <div class="info-value">
                            Table #{{ $order->table_id }}
                        </div>
                    </div>
                @endif

                @if($order->address)
                    <div class="info-box">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $order->address }}</div>
                    </div>
                @endif
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="right">Price</th>
                            <th class="right">Quantity</th>
                            <th class="right">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                            @php
                                $unitPrice = $item->price ?? $item->food->price ?? 0;
                                $subtotal = $unitPrice * $item->quantity;
                            @endphp

                            <tr>
                                <td>
                                    {{ $item->food_name }}
                                    @if($item->size_name)
                                        <small style="color:#6b7280;"> ({{ $item->size_name }})</small>
                                    @endif
                                </td>

                                <td class="right">
                                    Rs. {{ number_format($unitPrice, 2) }}
                                </td>

                                <td class="right">
                                    {{ $item->quantity }}
                                </td>

                                <td class="right">
                                    Rs. {{ number_format($subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="total-row">
                <span>Total Bill</span>
                <span id="totalAmount" data-total="{{ $order->total_amount }}">
                    Rs. {{ number_format($order->total_amount, 2) }}
                </span>
            </div>

            <form
                method="POST"
                action="{{ route('admin.orders.complete-payment', $order) }}"
            >
                @csrf

                <div class="payment-box">
                    <label for="paidAmount">
                        Cash Received
                    </label>

                    <input
                        id="paidAmount"
                        type="number"
                        name="paid_amount"
                        min="{{ $order->total_amount }}"
                        step="0.01"
                        placeholder="Enter received amount"
                        required
                    >

                    @error('paid_amount')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="change-box">
                        <span>Return / Change:</span>
                        <span id="changeAmount">Rs. 0.00</span>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn" onclick="window.print()" style="background:#2563eb;color:white;">🖨️ Print Bill</button>
                    <button type="submit" class="btn btn-close">
                        ✅ Close Bill & Add Sale
                    </button>

                    <a
                        href="{{ route('admin.orders.index', ['type' => $order->order_type]) }}"
                        class="btn btn-back"
                    >
                        ← Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const paidAmount = document.getElementById('paidAmount');
    const totalAmount = parseFloat(
        document.getElementById('totalAmount').dataset.total
    );

    const changeAmount = document.getElementById('changeAmount');

    paidAmount.addEventListener('input', function () {
        const paid = parseFloat(this.value) || 0;

        const change = paid - totalAmount;

        changeAmount.textContent = 'Rs. ' +
            Math.max(change, 0).toFixed(2);
    });
</script>

</body>
</html>
