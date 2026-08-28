<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Categories - FoodHub Admin</title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }


        /* ================= TOP BAR ================= */

        .topbar {
            width: 100%;
            background: #111827;
            color: white;

            min-height: 70px;
            padding: 0 30px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            box-shadow: 0 4px 20px rgba(0,0,0,.15);

            position: sticky;
            top: 0;
            z-index: 1000;
        }


        /* ================= LOGO ================= */

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


        /* ================= NAV ================= */

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

        .nav a.active {
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


        /* ================= BODY ================= */

        body {
            background: #f4f6f9;
            color: #222;
        }


        /* ================= CONTAINER ================= */

        .container {
            max-width: 1200px;

            margin: auto;

            padding: 30px;
        }


        /* ================= HEADER ================= */

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


        /* ================= BUTTONS ================= */

        .btn {
            display: inline-block;

            padding: 11px 18px;

            border: none;

            border-radius: 8px;

            text-decoration: none;

            cursor: pointer;

            font-size: 14px;

            font-weight: bold;

            transition: .2s;
        }


        .btn-primary {
            background: #ff6b00;
            color: white;
        }

        .btn-primary:hover {
            background: #e85f00;
        }


        .btn-edit {
            background: #2563eb;
            color: white;
        }

        .btn-edit:hover {
            background: #1d4ed8;
        }


        .btn-delete {
            background: #dc2626;
            color: white;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }


        /* ================= ALERT ================= */

        .alert {
            background: #dcfce7;

            color: #166534;

            padding: 14px 16px;

            border-radius: 9px;

            margin-bottom: 20px;

            border: 1px solid #bbf7d0;
        }


        /* ================= CARD ================= */

        .card {
            background: white;

            border-radius: 15px;

            overflow: hidden;

            box-shadow: 0 5px 25px rgba(0,0,0,.07);

            border: 1px solid #e5e7eb;
        }


        /* ================= CARD HEADER ================= */

        .card-header {
            padding: 20px 22px;

            border-bottom: 1px solid #eee;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }

        .card-header h2 {
            font-size: 20px;
        }

        .count {
            background: #fff7ed;

            color: #ea580c;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;
        }


        /* ================= TABLE ================= */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 750px;
        }

        th {
            background: #111827;

            color: white;

            padding: 15px;

            text-align: left;

            font-size: 14px;
        }

        td {
            padding: 15px;

            border-bottom: 1px solid #eee;

            vertical-align: middle;
        }

        tbody tr {
            transition: .15s;
        }

        tbody tr:hover {
            background: #fafafa;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }


        /* ================= CATEGORY NAME ================= */

        .category-name {
            display: flex;

            align-items: center;

            gap: 12px;
        }

        .category-icon {
            width: 42px;
            height: 42px;

            border-radius: 10px;

            background: #fff7ed;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .category-name strong {
            font-size: 15px;
        }


        /* ================= DESCRIPTION ================= */

        .description {
            color: #666;

            max-width: 350px;

            line-height: 1.5;

            font-size: 14px;
        }


        /* ================= STATUS ================= */

        .status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 7px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;
        }

        .status.active {
            background: #dcfce7;

            color: #166534;
        }

        .status.inactive {
            background: #fee2e2;

            color: #991b1b;
        }


        /* ================= ACTIONS ================= */

        .actions {
            display: flex;

            align-items: center;

            gap: 7px;
        }

        .actions form {
            margin: 0;
        }


        /* ================= EMPTY ================= */

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

        .empty p {
            margin-bottom: 20px;
        }


        /* ================= MOBILE ================= */

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


        @media(max-width: 700px) {

            .container {
                padding: 20px 15px;
            }

            .header {
                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }

            .header h1 {
                font-size: 25px;
            }

            .header .btn {
                width: 100%;

                text-align: center;
            }

            .card-header {
                padding: 16px;
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

            .actions {
                flex-direction: column;

                align-items: stretch;
            }

            .actions .btn {
                text-align: center;
            }

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
