<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cart - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            color: #222;
            padding: 0 0 40px 0;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding: 30px 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 32px;
        }

        .back {
            text-decoration: none;
            background: #111827;
            color: white;
            padding: 11px 18px;
            border-radius: 8px;
        }

        .alert {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
            margin-bottom: 20px;
        }

        .item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .item:last-child {
            border-bottom: none;
        }

        .image {
            width: 80px;
            height: 80px;
            background: #eee;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            overflow: hidden;
        }

        .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info {
            flex: 1;
        }

        .info h3 {
            margin-bottom: 6px;
        }

        .price {
            color: #ff6b00;
            font-weight: bold;
        }

        .quantity-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .update {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .remove {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b00;
        }

        .checkout {
            display: inline-block;
            background: #ff6b00;
            color: white;
            text-decoration: none;
            padding: 13px 22px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .clear {
            background: #6b7280;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 7px;
            cursor: pointer;
        }

        .empty {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 70px;
            margin-bottom: 15px;
        }

        @media(max-width: 700px) {

            body {
                padding: 15px;
            }

            .item {
                flex-wrap: wrap;
            }

            .quantity-form {
                width: 100%;
            }

            .summary {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>

<body>

<nav>
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon"><i class="fas fa-utensils"></i></span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div>
        <a href="{{ url('/') }}">
            <span>🏠</span> Home
        </a>

        <a href="{{ url('/#categories') }}">
            <i class="fas fa-th-large"></i> Categories
        </a>

        <a href="{{ url('/#full-menu') }}">
            <i class="fas fa-utensils"></i> Menu
        </a>

        <a href="{{ url('/#announcement') }}" class="announcement-nav">
            <i class="fas fa-tags"></i> New Deals
        </a>

        <a href="{{ route('track.order') }}">
            <i class="fas fa-map-marker-alt"></i> Track Order
        </a>

        <a href="{{ route('cart') }}" class="cart-nav">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count" id="navCartCount">{{ collect(session()->get('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<div class="container">

    <div class="header">

        <h1>
            <i class="fas fa-shopping-cart"></i> Your Cart
        </h1>

        <a
            href="{{ route('home') }}"
            class="back"
        >
            ← Continue Shopping
        </a>

    </div>


    @if(session('success'))

        <div class="alert">
            {{ session('success') }}
        </div>

    @endif


    @if(count($cart))

        <div class="card">

            @foreach($cart as $item)

                <div class="item">

                    <div class="image">

                        @if($item['image'])

                            <img
                                src="{{ $item['image'] }}"
                            >

                        @else

                            🍔

                        @endif

                    </div>


                    <div class="info">

                        <h3>
                            {{ $item['name'] }}
                        </h3>

                        @if($item['is_deal'] ?? false)
                            <small>Deal items: {{ $item['included_items'] ?? 'Complete bundle' }}</small>
                        @endif

                        <div class="price">
                            Rs. {{ number_format($item['price'], 2) }}
                        </div>

                    </div>


                    <form
                        action="{{ route('cart.update', $item['id']) }}"
                        method="POST"
                        class="quantity-form"
                    >

                        @csrf

                        <input
                            type="number"
                            name="quantity"
                            value="{{ $item['quantity'] }}"
                            min="1"
                            class="quantity"
                        >

                        <button
                            type="submit"
                            class="update"
                        >
                            Update
                        </button>

                    </form>


                    <form
                        action="{{ route('cart.remove', $item['id']) }}"
                        method="POST"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="remove"
                        >
                            Remove
                        </button>

                    </form>

                </div>

            @endforeach

        </div>


        <div class="card">

            <div class="summary">

                <div>

                    <h3>
                        Total Amount
                    </h3>

                    <div class="total">
                        Rs. {{ number_format($total, 2) }}
                    </div>

                </div>


                <div>

                    <form
                        action="{{ route('cart.clear') }}"
                        method="POST"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="clear"
                        >
                            Clear Cart
                        </button>

                    </form>

                   <a
    href="{{ route('checkout') }}"
    class="checkout"
>
    Proceed to Checkout →
</a>

                </div>

            </div>

        </div>

    @else

        <div class="card empty">

            <div class="empty-icon">
                🛒
            </div>

            <h2>
                Your Cart is Empty
            </h2>

            <p style="color:#777;margin:10px 0 25px;">
                Add some delicious food to your cart.
            </p>

            <a
                href="{{ route('home') }}"
                class="checkout"
            >
                <i class="fas fa-utensils"></i> Browse Food
            </a>

        </div>

    @endif

</div>

</body>

</html>
