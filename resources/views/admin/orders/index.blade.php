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

        



        

        



        

        

        


        /* ACTIVE PAGE */

        


        /* WEBSITE */

        

        



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


            


            

        }


        @media(max-width: 500px) {

            


            

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>


<body>



@include('admin.partials.topbar')
