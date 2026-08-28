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
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>

<body>

@include('admin.partials.topbar')
