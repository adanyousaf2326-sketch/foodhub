<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Food - FoodHub</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 0 0 40px 0;
        }

        .container {
            max-width: 850px;
            margin: auto;
            padding: 30px 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        h1 {
            color: #222;
        }

        .subtitle {
            color: #777;
            margin-top: 6px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #ff6b00;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox input {
            width: auto;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-primary {
            background: #ff6b00;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .current-image {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #666;
        }

        @media(max-width: 700px) {
            .row {
                grid-template-columns: 1fr;
            }

            body {
                padding: 15px;
            }

            .card {
                padding: 20px;
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
