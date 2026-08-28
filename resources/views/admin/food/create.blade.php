<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Food - FoodHub Admin</title>


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
            max-width: 750px;

            margin: auto;

            padding: 35px 20px;
        }



        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 30px;
            margin-bottom: 7px;
        }

        .subtitle {
            color: #777;
        }



        .card {
            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow: 0 5px 25px rgba(0,0,0,.08);

            border: 1px solid #e5e7eb;
        }



        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #374151;
        }

        input,
        textarea,
        select {
            width: 100%;

            padding: 13px;

            border: 1px solid #d1d5db;

            border-radius: 9px;

            font-size: 15px;

            outline: none;

            background: white;

            transition: .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #ff6b00;

            box-shadow:
                0 0 0 3px rgba(255,107,0,.10);
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        select {
            cursor: pointer;
        }



        .image-help {
            display: block;

            margin-top: 8px;

            color: #777;

            font-size: 13px;

            line-height: 1.5;
        }

        .image-example {
            margin-top: 10px;

            padding: 10px 12px;

            background: #f8fafc;

            border: 1px solid #e5e7eb;

            border-radius: 8px;

            font-size: 13px;

            color: #475569;

            word-break: break-all;
        }



        .checkbox {
            display: flex;

            gap: 10px;

            align-items: center;

            background: #f8fafc;

            padding: 13px;

            border-radius: 9px;

            border: 1px solid #e5e7eb;
        }

        .checkbox input {
            width: 18px;

            height: 18px;

            accent-color: #ff6b00;

            cursor: pointer;
        }

        .checkbox label {
            margin: 0;

            cursor: pointer;
        }



        .buttons {
            display: flex;

            gap: 10px;

            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            text-decoration: none;

            font-weight: bold;

            font-size: 14px;
        }

        .btn-primary {
            background: #ff6b00;

            color: white;
        }

        .btn-primary:hover {
            background: #e85f00;
        }

        .btn-secondary {
            background: #e5e7eb;

            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }



        .errors {
            background: #fee2e2;

            padding: 15px;

            border-radius: 9px;

            margin-bottom: 20px;

            color: #991b1b;

            border: 1px solid #fecaca;
        }

        .errors ul {
            margin-left: 20px;

            margin-top: 8px;
        }

        .errors li {
            margin-bottom: 4px;
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


        @media(max-width: 700px) {

            .container {
                padding: 25px 15px;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .card {
                padding: 22px;
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

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;

                text-align: center;
            }

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>

<body>

@include('admin.partials.topbar')
