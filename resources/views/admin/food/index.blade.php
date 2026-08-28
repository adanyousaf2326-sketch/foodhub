<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Food Items - FoodHub</title>


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



        .container {
            max-width: 1200px;
            margin: auto;
            padding: 30px;
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


        .btn-view {
            background: #16a34a;
            color: white;
        }


        .btn-view:hover {
            background: #15803d;
        }



        .alert {
            background: #dcfce7;
            color: #166534;

            padding: 14px;

            border-radius: 8px;

            margin-bottom: 20px;

            border: 1px solid #bbf7d0;
        }



        .card {
            background: white;

            border-radius: 15px;

            overflow: hidden;

            box-shadow: 0 5px 25px rgba(0,0,0,.07);
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

            vertical-align: middle;
        }


        th {
            background: #f8f9fa;

            font-size: 14px;

            color: #444;
        }


        tbody tr:hover {
            background: #fafafa;
        }



        .food-image-wrapper {
            width: 60px;
            height: 60px;

            border-radius: 10px;

            overflow: hidden;

            background: #f1f5f9;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        .food-image {
            width: 60px;
            height: 60px;

            object-fit: cover;

            object-position: center;

            border-radius: 10px;

            display: block;
        }


        .food-placeholder {
            width: 60px;
            height: 60px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 27px;

            background: #f1f5f9;

            border-radius: 10px;
        }



        .status {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 13px;

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



        .actions {
            display: flex;

            gap: 6px;

            align-items: center;

            flex-wrap: wrap;
        }


        .actions form {
            margin: 0;
        }



        .empty {
            padding: 50px;

            text-align: center;

            color: #777;
        }


        .empty h2 {
            color: #333;

            margin-bottom: 10px;
        }



        .image-url {
            max-width: 220px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            color: #777;

            font-size: 12px;
        }


        @media(max-width: 1000px) {

            


            

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


            .card {
                overflow-x: auto;
            }


            table {
                min-width: 950px;
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
