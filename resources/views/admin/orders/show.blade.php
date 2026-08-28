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
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>


<body>


@include('admin.partials.topbar')
