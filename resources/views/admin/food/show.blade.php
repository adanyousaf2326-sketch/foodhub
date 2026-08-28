<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $food->name }} - FoodHub</title>

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
            max-width: 900px;
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
            color: #222;
        }

        .subtitle {
            color: #777;
            margin-top: 5px;
        }

        .btn {
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary {
            background: #ff6b00;
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .food-top {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            padding: 30px;
        }

       .food-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    object-position: center;
    border-radius: 10px;
    background: #eee;
    display: block;
}

        .image-placeholder {
            width: 100%;
            height: 280px;
            border-radius: 15px;
            background: #fff7ed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
        }

        .food-name {
            font-size: 32px;
            margin-bottom: 10px;
            color: #222;
        }

        .category {
            color: #777;
            margin-bottom: 20px;
        }

        .price {
            font-size: 28px;
            font-weight: bold;
            color: #ff6b00;
            margin-bottom: 20px;
        }

        .description {
            color: #555;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .status {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .available {
            background: #dcfce7;
            color: #166534;
        }

        .unavailable {
            background: #fee2e2;
            color: #991b1b;
        }

        .details {
            border-top: 1px solid #eee;
            padding: 25px 30px;
        }

        .details h2 {
            margin-bottom: 20px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .detail {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .detail-label {
            color: #777;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: bold;
            color: #222;
        }

        .actions {
            padding: 25px 30px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        @media(max-width: 700px) {

            body {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .food-top {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>

<body>

@include('admin.partials.topbar')
