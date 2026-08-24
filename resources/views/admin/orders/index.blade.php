<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orders - FoodHub Admin</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

.action-btn {
    border: none;
    padding: 8px 11px;
    border-radius: 7px;
    text-decoration: none;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    display: inline-block;
}

.view-btn {
    background: #dbeafe;
    color: #1d4ed8;
}

.close-btn {
    background: #dcfce7;
    color: #15803d;
}

.cancel-btn {
    background: #fee2e2;
    color: #dc2626;
}

.action-btn:hover {
    opacity: .85;
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


        /* ACTIVE PAGE */

        .nav .active {
            background: #ff6b00;
            color: white;
        }


        /* WEBSITE */

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
            max-width: 1400px;
            margin: auto;

            padding: 30px;
        }



        .header {
            display: flex;

            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 30px;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #777;
        }

.table-badge {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 20px;
    background: #fff7ed;
    color: #c2410c;
    font-size: 13px;
    font-weight: bold;
}

        .back-btn {
            text-decoration: none;

            background: #111827;

            color: white;

            padding: 11px 18px;

            border-radius: 8px;

            display: inline-block;

            font-weight: bold;
        }

        .back-btn:hover {
            background: #1f2937;
        }



        .alert {
            background: #dcfce7;

            color: #166534;

            padding: 14px 18px;

            border-radius: 10px;

            margin-bottom: 20px;
        }



        .stats {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 18px;

            margin-bottom: 25px;
        }


        .stat-card {
            background: white;

            padding: 22px;

            border-radius: 14px;

            box-shadow: 0 5px 25px rgba(0,0,0,.06);
        }


        .stat-title {
            color: #777;

            font-size: 14px;

            margin-bottom: 8px;
        }


        .stat-number {
            font-size: 28px;

            font-weight: bold;
        }


        .orange {
            color: #ff6b00;
        }

        .blue {
            color: #2563eb;
        }

        .green {
            color: #16a34a;
        }

        .red {
            color: #dc2626;
        }



        .card {
            background: white;

            border-radius: 15px;

            overflow: hidden;

            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }


        .card-header {
            padding: 20px;

            border-bottom: 1px solid #eee;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }


        .card-header h2 {
            font-size: 20px;
        }



        .table-wrapper {
            overflow-x: auto;
        }


        table {
            width: 100%;

            border-collapse: collapse;
        }


        th,
        td {
            padding: 15px;

            text-align: left;

            border-bottom: 1px solid #eee;

            white-space: nowrap;
        }


        th {
            background: #f8f9fa;

            color: #555;

            font-size: 14px;
        }


        tbody tr:hover {
            background: #fafafa;
        }


        .order-id {
            font-weight: bold;

            color: #ff6b00;
        }


        .customer {
            font-weight: bold;
        }


        .phone {
            color: #777;

            font-size: 13px;

            margin-top: 4px;
        }


        .amount {
            font-weight: bold;
        }



        .status {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 20px;

            font-size: 13px;

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


        .out-for-delivery {
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



        .btn {
            display: inline-block;

            padding: 9px 14px;

            border-radius: 7px;

            text-decoration: none;

            border: none;

            cursor: pointer;

            font-size: 13px;

            font-weight: bold;
        }


        .btn-view {
            background: #2563eb;

            color: white;
        }


        .btn-view:hover {
            background: #1d4ed8;
        }



        .empty {
            padding: 60px 20px;

            text-align: center;

            color: #777;
        }


        .empty-icon {
            font-size: 55px;

            margin-bottom: 15px;
        }


        .empty h2 {
            color: #333;

            margin-bottom: 8px;
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


            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media(max-width: 700px) {

            .container {
                padding: 20px 15px;
            }


            .header {
                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }


            .stats {
                grid-template-columns: 1fr;
            }


            .header h1 {
                font-size: 24px;
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

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
</head>


<body>



@include('admin.partials.topbar')



<div class="container">


    <!-- HEADER -->

    <div class="header">


        <div>

            <h1>
                🧾 Orders
            </h1>

           

        </div>


        <a
            href="{{ route('admin.food.index') }}"
            class="back-btn"
        >
            🍔 Food Items
        </a>


    </div>


    <!-- ================================================= -->
    <!-- SUCCESS MESSAGE -->
    <!-- ================================================= -->

    @if(session('success'))

        <div class="alert">

            ✅ {{ session('success') }}

        </div>

    @endif


    <!-- ================================================= -->
    <!-- STATISTICS -->
    <!-- ================================================= -->

    @php

        $totalOrders =
            $orders->count();

        $pendingOrders =
            $orders->where('status', 'Pending')->count();

        $preparingOrders =
            $orders->where('status', 'Preparing')->count();

        $deliveredOrders =
            $orders->where('status', 'Delivered')->count();

    @endphp


    <div class="stats">


        <!-- TOTAL -->

        <div class="stat-card">

            <div class="stat-title">
                Total Orders
            </div>

            <div class="stat-number orange">
                {{ $totalOrders }}
            </div>

        </div>


        <!-- PENDING -->

        <div class="stat-card">

            <div class="stat-title">
                Pending
            </div>

            <div class="stat-number red">
                {{ $pendingOrders }}
            </div>

        </div>


        <!-- PREPARING -->

        <div class="stat-card">

            <div class="stat-title">
                Preparing
            </div>

            <div class="stat-number blue">
                {{ $preparingOrders }}
            </div>

        </div>


        <!-- DELIVERED -->

        <div class="stat-card">

            <div class="stat-title">
                Delivered
            </div>

            <div class="stat-number green">
                {{ $deliveredOrders }}
            </div>

        </div>


    </div>


    <!-- ================================================= -->
    <!-- ORDERS TABLE -->
    <!-- ================================================= -->

    <div class="card">


        <div class="card-header">

            <h2>
                All Orders
            </h2>


            <span style="color:#777;font-size:14px;">

                {{ $totalOrders }} Orders

            </span>

        </div>


        @if($orders->count())


            <div class="table-wrapper">


                <table>


                    <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Address
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date
                            </th>
<th>Table</th>
                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($orders as $order)


                        <tr>


                            <!-- ORDER ID -->

                            <td>

                                <span class="order-id">

                                    #{{ $order->id }}

                                </span>

                            </td>


                            <!-- CUSTOMER -->

                            <td>

                                <div class="customer">

                                    {{ $order->customer_name }}

                                </div>


                                <div class="phone">

                                    📞 {{ $order->phone }}

                                </div>

                            </td>


                            <!-- ADDRESS -->

                            <td>

                                <div
                                    style="
                                        max-width:220px;
                                        white-space:normal;
                                    "
                                >

                                    {{ Str::limit($order->address, 50) }}

                                </div>

                            </td>


                            <!-- TOTAL -->

                            <td>

                                <span class="amount">

                                    Rs.
                                    {{ number_format($order->total_amount, 2) }}

                                </span>

                            </td>


                            <!-- PAYMENT -->

                            <td>

                                {{ $order->payment_method }}

                            </td>


                            <!-- STATUS -->

                            <td>


                                @php

                                    $statusClass = match($order->status) {

                                        'Pending' =>
                                            'pending',

                                        'Preparing' =>
                                            'preparing',

                                        'Out for Delivery' =>
                                            'out-for-delivery',

                                        'Delivered' =>
                                            'delivered',

                                        'Cancelled' =>
                                            'cancelled',

                                        default =>
                                            'pending'

                                    };

                                @endphp


                                <span
                                    class="status {{ $statusClass }}"
                                >

                                    {{ $order->status }}

                                </span>


                            </td>


                            <!-- DATE -->

                            <td>

                                {{ $order->created_at->format('d M Y') }}

                                <br>

                                <small style="color:#777;">

                                    {{ $order->created_at->format('h:i A') }}

                                </small>

                            </td>


                            <!-- ACTION -->
<td>
    @if($order->order_type === 'Dine In' && $order->table_id)
        <span class="table-badge">
            🍽️ Table {{ $order->table_id }}
        </span>
    @else
        —
    @endif
</td>
                          <td>
    <div style="display:flex; gap:7px; align-items:center;">

        {{-- VIEW --}}
        <a href="{{ route('admin.orders.show', $order) }}"
           class="action-btn view-btn">
         View
        </a>

        {{-- CLOSE --}}
        @if($order->order_type === 'Dine In' && $order->status !== 'Completed' && $order->status !== 'Cancelled')

            <form action="{{ route('admin.orders.close', $order) }}"
                  method="POST"
                  style="display:inline;">
                @csrf

                <button type="submit"
                        class="action-btn close-btn"
                        onclick="return confirm('Close this order and free the table?')">
                    ✅ Close
                </button>
            </form>

        @endif

        {{-- CANCEL --}}
        @if($order->status !== 'Completed' && $order->status !== 'Cancelled')

            <form action="{{ route('admin.orders.cancel', $order) }}"
                  method="POST"
                  style="display:inline;">

                @csrf

                <button type="submit"
                        class="action-btn cancel-btn"
                        onclick="return confirm('Are you sure you want to cancel this order?')">
                    ❌ Cancel
                </button>

            </form>

        @endif

    </div>
</td>


                        </tr>


                    @endforeach


                    </tbody>


                </table>


            </div>


        @else


            <!-- EMPTY -->

            <div class="empty">


                <div class="empty-icon">
                    🧾
                </div>


                <h2>
                    No Orders Yet
                </h2>


                <p>
                    Customer orders will appear here.
                </p>


            </div>


        @endif


    </div>


</div>


</body>
</html>
