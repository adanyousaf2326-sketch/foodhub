<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order Placed - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            max-width: 550px;
            width: 100%;
            padding: 40px;
            text-align: center;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }

        .icon {
            font-size: 70px;
            margin-bottom: 15px;
        }

        h1 {
            color: #16a34a;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .order-number {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 10px;
            margin: 25px 0;
            font-weight: bold;
        }

        .total {
            color: #ff6b00;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            background: #ff6b00;
            color: white;
            padding: 13px 22px;
            border-radius: 8px;
            text-decoration: none;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">

<body>

<div class="card">

    <div class="icon">
        <i class="fas fa-check-circle" style="font-size:60px;color:#16a34a;"></i>
    </div>

    <h1>
        Order Placed!
    </h1>

    <p>
        Thank you for ordering from FoodHub.
        Your order has been received successfully.
    </p>


    <div class="order-number">

        Order #{{ $order->id }}

    </div>


    <div class="total">

        Rs. {{ number_format($order->total_amount, 2) }}

    </div>


    <p>
        Status:
        <strong>
            {{ $order->status }}
        </strong>
    </p>


    <br>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('payment.page', $order) }}" class="btn" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);box-shadow:0 4px 15px rgba(37,99,235,0.3);">
            💳 Pay Online (Rs. {{ number_format($order->total_amount, 2) }})
        </a>
        <a href="{{ route('track.order.search', ['order_number' => $order->id]) }}" class="btn" style="background:#111827;">
            📍 Track Your Order
        </a>
    </div>

    <br>

    <a
        href="{{ route('home') }}"
        class="btn"
    >
        🍔 Order More Food
    </a>

</div>

</body>

</html>
