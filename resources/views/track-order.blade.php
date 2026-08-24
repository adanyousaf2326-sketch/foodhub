<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - FoodHub</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #222;
        }

        nav {
            background: #111827;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            color: #ff6b00;
            font-size: 26px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo-icon {
            margin-right: 4px;
        }

        .hub-brand {
            color: white;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            padding: 10px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        nav a:hover {
            background: #ff6b00;
        }

        .track-active {
            background: #2563eb;
        }

        .announcement-nav {
            background: #16a34a;
        }

        .cart-nav {
            background: #ff6b00;
        }

        .cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 21px;
            height: 21px;
            padding: 0 6px;
            margin-left: 4px;
            border-radius: 50%;
            background: white;
            color: #ff6b00;
            font-size: 12px;
            font-weight: bold;
        }

        .container {
            max-width: 700px;
            margin: 60px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .title .icon {
            font-size: 55px;
            margin-bottom: 10px;
        }

        .title h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .title p {
            color: #777;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, .10);
        }

        .track-btn,
        .cancel-btn,
        .edit-order-btn {
            width: 100%;
            border: none;
            color: white;
            padding: 14px;
            border-radius: 9px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .track-btn {
            background: #ff6b00;
        }

        .track-btn:hover {
            background: #e85f00;
            transform: translateY(-1px);
        }

        .edit-order-btn {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            margin-top: 15px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }

        .edit-order-btn:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
        }

        .cancel-btn {
            background: #dc2626;
            margin-top: 10px;
        }

        .cancel-btn:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .order-box {
            margin-top: 30px;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
        }

        .order-box.cancelled-order {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .order-box h2 {
            margin-bottom: 16px;
        }

        .order-box p {
            margin-top: 11px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-preparing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-delivered {
            background: #dcfce7;
            color: #166534;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .cancel-time {
            margin-top: 18px;
            padding: 14px;
            background: #fff7ed;
            color: #c2410c;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
            border: 1px solid #fed7aa;
        }

        .cancel-expired {
            margin-top: 18px;
            padding: 14px;
            background: #f3f4f6;
            color: #4b5563;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
        }

        .countdown-box {
            margin-top: 15px;
            padding: 15px;
            background: #ffffff;
            border: 1px solid #fde68a;
            border-radius: 10px;
            text-align: center;
        }

        .countdown-label {
            display: block;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .countdown {
            font-size: 25px;
            font-weight: bold;
            color: #dc2626;
            letter-spacing: 1px;
        }

        .info {
            margin-top: 30px;
            padding: 18px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .info h3 {
            margin-bottom: 10px;
        }

        .info p {
            color: #666;
            line-height: 1.6;
        }

        .success {
            margin-bottom: 20px;
            padding: 14px;
            background: #dcfce7;
            color: #166534;
            border-radius: 10px;
        }

        .error {
            margin-bottom: 20px;
            padding: 14px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 10px;
        }

        @media(max-width:700px) {
            nav {
                padding: 15px 5%;
                flex-direction: column;
                gap: 15px;
            }

            nav div:last-child {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            nav a {
                margin: 4px;
                font-size: 13px;
            }

            .container {
                margin: 30px auto;
            }

            .card {
                padding: 25px 20px;
            }

            .title h1 {
                font-size: 27px;
            }

            .countdown {
                font-size: 22px;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>

<body>

<nav>

    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon">🍔</span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div>

        <a href="{{ url('/') }}">
            <span>🏠</span> Home
        </a>

        <a href="{{ url('/#categories') }}">
            <span>📂</span> Categories
        </a>

        <a href="{{ url('/#full-menu') }}">
            <span>📋</span> Menu
        </a>

        <a href="{{ url('/#announcement') }}" class="announcement-nav">
            <span>📣</span> New Deals
        </a>

        <a href="{{ route('track.order') }}" class="track-active">
            <span>📍</span> Track Order
        </a>

        <a href="{{ route('cart') }}" class="cart-nav">
            <span>🛒</span>
            Cart
            <span class="cart-count" id="navCartCount">
                {{ collect(session()->get('cart', []))->sum('quantity') }}
            </span>
        </a>

    </div>

</nav>

<div class="container">

    <div class="card">

        <div class="title">

            <div class="icon">
                📦
            </div>

            <h1>
                Track Your Order
            </h1>

            <p>
                Enter your order number to check your order status.
            </p>

        </div>

        @if(session('success'))

            <div class="success">
                ✅ {{ session('success') }}
            </div>

        @endif

        @if(session('error'))

            <div class="error">
                ❌ {{ session('error') }}
            </div>

        @endif

        <form
            action="{{ route('track.order.search') }}"
            method="GET"
        >

            <div class="form-group">

                <label>
                    Order Number
                </label>

                <input
                    type="text"
                    name="order_number"
                    value="{{ request('order_number') }}"
                    placeholder="Enter order number e.g. 5"
                    required
                >

            </div>

            <button
                type="submit"
                class="track-btn"
            >
                🔍 Track Order
            </button>

        </form>

        @if(request()->has('order_number'))

            @if($order)

                @php
                    $isCancelled = $order->status === 'Cancelled';
                    $isCompleted = $order->status === 'Completed';
                    $isDelivered = $order->status === 'Delivered';

                    $orderDeadline = $order->created_at
                        ->copy()
                        ->addMinutes(15);

                    $canModifyOrder =
                        !$isCancelled
                        && !$isCompleted
                        && !$isDelivered
                        && now()->lt($orderDeadline);

                    $statusClass = match($order->status) {
                        'Preparing' => 'status-preparing',
                        'Delivered' => 'status-delivered',
                        'Completed' => 'status-completed',
                        'Cancelled' => 'status-cancelled',
                        default => 'status-pending',
                    };
                @endphp

                <div class="order-box {{ $isCancelled ? 'cancelled-order' : '' }}">

                    <h2>
                        📦 Order #{{ $order->id }}
                    </h2>

                    <p>
                        <strong>Status:</strong>

                        <span class="status {{ $statusClass }}">
                            {{ $order->status }}
                        </span>
                    </p>

                    <p>
                        <strong>Order Type:</strong>
                        {{ $order->order_type }}
                    </p>

                    <p>
                        <strong>Total:</strong>
                        Rs. {{ number_format($order->total_amount, 2) }}
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </p>

                    @if($isCancelled)

                        <div class="cancel-expired">
                            ❌ This order has been cancelled.
                        </div>

                    @elseif($isDelivered)

                        <div class="cancel-expired">
                            🚚 This order has already been delivered.
                            <br>
                            You can no longer update or cancel this order.
                        </div>

                    @elseif($isCompleted)

                        <div class="cancel-expired">
                            ✅ This bill is closed and cannot be updated or cancelled.
                        </div>

                    @elseif($canModifyOrder)

                        <div class="cancel-time">
                            ⏰ You can update or cancel this order until
                            <strong>
                                {{ $orderDeadline->format('h:i A') }}
                            </strong>.
                        </div>

                        <div class="countdown-box">

                            <span class="countdown-label">
                                Time remaining for update/cancellation
                            </span>

                            <span
                                class="countdown"
                                id="orderCountdown"
                                data-deadline="{{ $orderDeadline->timestamp * 1000 }}"
                            >
                                Loading...
                            </span>

                        </div>

                        <a
                            href="{{ route('track.order.edit', $order) }}"
                            class="edit-order-btn"
                        >
                            ✏️ Update My Order
                        </a>

                        <form
                            method="POST"
                            action="{{ route('track.order.cancel', $order) }}"
                            onsubmit="return confirm('Are you sure you want to cancel this order?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="cancel-btn"
                            >
                                ❌ Cancel My Order
                            </button>

                        </form>

                    @else

                        <div class="cancel-expired">
                            ⏰ The 15-minute modification time has expired.
                            <br>
                            This order can no longer be updated or cancelled.
                        </div>

                    @endif

                </div>

            @else

                <div
                    class="error"
                    style="margin-top:30px;"
                >
                    ❌ Order #{{ request('order_number') }} not found.
                    Please check your order number.
                </div>

            @endif

        @endif

        <div class="info">

            <h3>
                📋 How does tracking work?
            </h3>

            <p>
                Enter your order number to see its current status.
                You can update or cancel an active order only within
                15 minutes of placing it.
                Updating your order will not restart the 15-minute timer.
                Cancelled orders will not be delivered or added to sales.
            </p>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const countdown =
        document.getElementById("orderCountdown");

    if (!countdown) {
        return;
    }

    const deadline =
        Number(countdown.dataset.deadline);

    function updateCountdown() {

        const now =
            new Date().getTime();

        const remaining =
            deadline - now;

        if (remaining <= 0) {

            countdown.textContent =
                "00:00";

            const editButton =
                document.querySelector(".edit-order-btn");

            if (editButton) {

                editButton.style.pointerEvents =
                    "none";

                editButton.style.opacity =
                    "0.5";

                editButton.textContent =
                    "🔒 Update Time Expired";
            }

            const cancelButton =
                document.querySelector(".cancel-btn");

            if (cancelButton) {

                cancelButton.disabled =
                    true;

                cancelButton.style.opacity =
                    "0.5";

                cancelButton.style.cursor =
                    "not-allowed";

                cancelButton.textContent =
                    "🔒 Cancel Time Expired";
            }

            clearInterval(timer);

            return;
        }

        const totalSeconds =
            Math.floor(remaining / 1000);

        const minutes =
            Math.floor(totalSeconds / 60);

        const seconds =
            totalSeconds % 60;

        countdown.textContent =
            String(minutes).padStart(2, "0")
            +
            ":"
            +
            String(seconds).padStart(2, "0");
    }

    updateCountdown();

    const timer =
        setInterval(updateCountdown, 1000);

});

</script>

</body>

</html>