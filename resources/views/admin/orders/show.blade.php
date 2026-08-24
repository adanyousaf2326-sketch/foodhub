<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order #{{ $order->id }} - FoodHub Admin</title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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

            position: sticky;
            top: 0;
            z-index: 1000;
        }


        .logo {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 22px;
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

            transition: .2s;

            white-space: nowrap;
        }


        .nav a:hover {
            background: #ff6b00;
            color: white;
        }


        .nav .active {
            background: #ff6b00;
            color: white;
        }


        .website-btn {
            background: #16a34a !important;
            color: white !important;
        }


        .website-btn:hover {
            background: #15803d !important;
        }



        body {
            background: #f4f6f9;
            color: #222;
        }


        .container {
            max-width: 1200px;
            margin: auto;
            padding: 30px 20px;
        }



        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }


        h1 {
            color: #222;
        }


        .subtitle {
            color: #777;
            margin-top: 6px;
        }


        .btn {
            padding: 10px 16px;

            border-radius: 8px;

            text-decoration: none;

            border: none;

            cursor: pointer;

            display: inline-block;

            font-size: 14px;

            font-weight: bold;
        }


        .back {
            background: #374151;
            color: white;
        }


        .back:hover {
            background: #1f2937;
        }


        .update {
            background: #ff6b00;
            color: white;
        }


        .update:hover {
            background: #e85f00;
        }


        .grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
        }



        .card {
            background: white;

            border-radius: 15px;

            padding: 25px;

            box-shadow: 0 5px 25px rgba(0,0,0,.07);

            margin-bottom: 20px;
        }


        .card h2 {
            margin-bottom: 20px;

            color: #222;

            font-size: 20px;
        }



        .info-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 18px;
        }


        .info label {
            display: block;

            font-size: 12px;

            color: #888;

            margin-bottom: 5px;

            text-transform: uppercase;
        }


        .info strong {
            color: #222;

            line-height: 1.5;
        }



        .item {
            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 15px 0;

            border-bottom: 1px solid #eee;

            gap: 20px;
        }


        .item:last-child {
            border-bottom: none;
        }


        .item-name {
            font-weight: bold;

            color: #222;
        }


        .item-details {
            color: #777;

            font-size: 13px;

            margin-top: 5px;
        }


        .item-total {
            font-weight: bold;

            color: #16a34a;

            white-space: nowrap;
        }


        .total-box {
            margin-top: 20px;

            padding-top: 18px;

            border-top: 2px solid #eee;

            display: flex;

            justify-content: space-between;

            font-size: 20px;

            font-weight: bold;
        }


        .total {
            color: #ff6b00;
        }



        .status-box {
            text-align: center;

            padding: 20px;

            border-radius: 12px;

            background: #fff7ed;

            margin-bottom: 20px;
        }


        .status-box .label {
            color: #777;

            font-size: 13px;

            margin-bottom: 8px;
        }


        .status {
            display: inline-block;

            padding: 7px 14px;

            border-radius: 20px;

            font-weight: bold;
        }


        .pending {
            background: #fef3c7;
            color: #92400e;
        }


        .confirmed {
            background: #dbeafe;
            color: #1e40af;
        }


        .preparing {
            background: #ede9fe;
            color: #6d28d9;
        }


        .delivered {
            background: #dcfce7;
            color: #166534;
        }


        .cancelled {
            background: #fee2e2;
            color: #991b1b;
        }



        .form-group {
            margin-bottom: 18px;
        }


        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-weight: bold;

            color: #444;
        }


        select,
        textarea {
            width: 100%;

            padding: 11px 12px;

            border: 1px solid #ddd;

            border-radius: 8px;

            outline: none;

            background: white;

            font-size: 14px;
        }


        select:focus,
        textarea:focus {
            border-color: #ff6b00;

            box-shadow: 0 0 0 3px rgba(255,107,0,.10);
        }


        textarea {
            resize: vertical;

            min-height: 90px;
        }



        .notes {
            background: #f8f9fa;

            padding: 15px;

            border-radius: 10px;

            color: #555;

            line-height: 1.6;
        }



        .success {
            background: #dcfce7;

            color: #166534;

            padding: 14px;

            border-radius: 8px;

            margin-bottom: 20px;

            border: 1px solid #bbf7d0;
        }



        @media(max-width: 1000px) {

            .topbar {
                flex-direction: column;

                padding: 15px 20px;

                gap: 15px;
            }


            .nav {
                width: 100%;

                justify-content: center;

                flex-wrap: wrap;
            }

        }


        @media(max-width: 850px) {

            .grid {
                grid-template-columns: 1fr;
            }


            .info-grid {
                grid-template-columns: 1fr;
            }


            .top {
                gap: 15px;

                flex-direction: column;

                align-items: flex-start;
            }

        }


        @media(max-width: 700px) {

            .container {
                padding: 25px 15px;
            }


            .nav {
                justify-content: flex-start;

                flex-wrap: nowrap;

                overflow-x: auto;

                padding-bottom: 5px;
            }


            .nav a {
                flex-shrink: 0;
            }

        }


        @media(max-width: 500px) {

            .logo {
                font-size: 19px;
            }


            .topbar {
                align-items: flex-start;
            }


            .item {
                align-items: flex-start;

                flex-direction: column;
            }


            .item-total {
                align-self: flex-end;
            }

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
</head>


<body>


@include('admin.partials.topbar')


<div class="container">


    <!-- HEADER -->

    <div class="top">

        <div>

            <h1>
                📦 Order #{{ $order->id }}
            </h1>


        </div>


        <a
            href="{{ route('admin.orders.index') }}"
            class="btn back"
        >
            ← Back to Orders
        </a>

    </div>


    <!-- SUCCESS MESSAGE -->

    @if(session('success'))

        <div class="success">

            ✅ {{ session('success') }}

        </div>

    @endif


    <div class="grid">



        <div>


            <!-- CUSTOMER -->

            <div class="card">

                <h2>
                    👤 Customer Information
                </h2>


                <div class="info-grid">


                    <div class="info">

                        <label>
                            Name
                        </label>

                        <strong>
                            {{ $order->customer_name }}
                        </strong>

                    </div>


                    <div class="info">

                        <label>
                            Phone
                        </label>

                        <strong>
                            {{ $order->phone }}
                        </strong>

                    </div>


                    <div class="info">

                        <label>
                            Address
                        </label>

                        <strong>
                            {{ $order->address }}
                        </strong>

                    </div>


                    <div class="info">

                        <label>
                            Payment
                        </label>

                        <strong>
                            {{ $order->payment_method }}
                        </strong>

                    </div>


                </div>

            </div>


            <!-- ORDER ITEMS -->

            <div class="card">

                <h2>
                    🍔 Ordered Items
                </h2>


                @forelse($order->items as $item)


                    <div class="item">


                        <div>

                            <div class="item-name">

                                {{ $item->food_name }}

                            </div>


                            <div class="item-details">

                                Qty:
                                {{ $item->quantity }}

                                ×

                                Rs.
                                {{ number_format($item->price, 2) }}

                            </div>

                        </div>


                        <div class="item-total">

                            Rs.
                            {{ number_format($item->price * $item->quantity, 2) }}

                        </div>


                    </div>


                @empty


                    <p style="color:#777;">
                        No items found in this order.
                    </p>


                @endforelse


                <!-- TOTAL -->

                <div class="total-box">

                    <span>
                        Total
                    </span>


                    <span class="total">

                        Rs.
                        {{ number_format($order->total_amount, 2) }}

                    </span>

                </div>


            </div>


            <!-- NOTES -->

            @if($order->notes)


                <div class="card">

                    <h2>
                        📝 Customer Notes
                    </h2>


                    <div class="notes">

                        {{ $order->notes }}

                    </div>

                </div>


            @endif


        </div>



        <div>


            <!-- CURRENT STATUS -->

            @php

                $statusClass = strtolower($order->status);

                if ($statusClass === 'out for delivery') {
                    $statusClass = 'preparing';
                }

            @endphp


            <div class="status-box">


                <div class="label">
                    CURRENT ORDER STATUS
                </div>


                <span class="status {{ $statusClass }}">

                    {{ $order->status }}

                </span>


            </div>


            <!-- UPDATE ORDER -->

            <div class="card">


                <h2>
                    ⚙️ Update Order
                </h2>


                <form
                    action="{{ route('admin.orders.update', $order) }}"
                    method="POST"
                >

                    @csrf

                    @method('PUT')


                    <!-- STATUS -->

                    <div class="form-group">

                        <label>
                            Order Status
                        </label>


                        <select name="status">


                            <option
                                value="Pending"
                                {{ $order->status == 'Pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>


                            <option
                                value="Confirmed"
                                {{ $order->status == 'Confirmed' ? 'selected' : '' }}
                            >
                                Confirmed
                            </option>


                            <option
                                value="Preparing"
                                {{ $order->status == 'Preparing' ? 'selected' : '' }}
                            >
                                Preparing
                            </option>


                            <option
                                value="Out for Delivery"
                                {{ $order->status == 'Out for Delivery' ? 'selected' : '' }}
                            >
                                Out for Delivery
                            </option>


                            <option
                                value="Delivered"
                                {{ $order->status == 'Delivered' ? 'selected' : '' }}
                            >
                                Delivered
                            </option>


                            <option
                                value="Cancelled"
                                {{ $order->status == 'Cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>


                        </select>

                    </div>


                    <!-- NOTES -->

                    <div class="form-group">

                        <label>
                            Notes
                        </label>


                        <textarea
                            name="notes"
                            placeholder="Add order note..."
                        >{{ $order->notes }}</textarea>

                    </div>


                    <!-- UPDATE -->

                    <button
                        type="submit"
                        class="btn update"
                        style="width:100%;"
                    >

                        💾 Update Order

                    </button>


                </form>


            </div>


            <!-- ORDER INFORMATION -->

            <div class="card">


                <h2>
                    🕒 Order Information
                </h2>


                <div class="info">

                    <label>
                        Order Date
                    </label>


                    <strong>
                        {{ $order->created_at->format('d M Y') }}
                    </strong>

                </div>


                <br>


                <div class="info">

                    <label>
                        Order Time
                    </label>


                    <strong>
                        {{ $order->created_at->format('h:i A') }}
                    </strong>

                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>
