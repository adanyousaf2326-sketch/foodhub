<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Users - FoodHub</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            color: #222;
        }

        .topbar {
            background: #111827;
            color: white;
            min-height: 70px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .logo span {
            color: #ff6b00;
        }

        .nav {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .nav a,
        .logout-btn {
            color: #d1d5db;
            text-decoration: none;
            padding: 11px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .nav a:hover,
        .nav .active {
            background: #ff6b00;
            color: white;
        }

        .logout-btn {
            background: #dc2626;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 30px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 30px;
        }

        .subtitle {
            color: #777;
            margin-top: 6px;
        }

        .add-btn {
            background: #ff6b00;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
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
        }

        th {
            background: #f8f9fa;
        }

        .role {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .admin {
            background: #ffedd5;
            color: #c2410c;
        }

        .manager {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .edit {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        .delete {
            background: #dc2626;
            color: white;
            border: none;
            padding: 7px 11px;
            border-radius: 6px;
            cursor: pointer;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media(max-width: 800px) {
            .topbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }

            .nav {
                width: 100%;
                overflow-x: auto;
            }

            .container {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
