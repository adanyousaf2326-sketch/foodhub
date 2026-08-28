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


            .card {
                overflow-x: auto;
            }


            table {
                min-width: 950px;
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
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>


<body>


    

@include('admin.partials.topbar')

    

<div class="container">


    <!-- HEADER -->

    <div class="header">


        <div>

            <h1>
                🍔 Food Items
            </h1>


        </div>


        <!-- ADD FOOD -->

        <a
            href="{{ route('admin.food.create') }}"
            class="btn btn-primary"
        >
            + Add Food
        </a>


    </div>


    @if(session('success'))

        <div class="alert">

            ✅ {{ session('success') }}

        </div>

    @endif



    <div class="card">


        @if($foods->count())


            <table>


                <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Image
                        </th>

                        <th>
                            Food
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>


                @foreach($foods as $food)


                    <tr>


                        <!-- NUMBER -->

                        <td>

                            {{ $loop->iteration }}

                        </td>


                        <!-- IMAGE -->

                        <td>

                            <div class="food-image-wrapper">


                                @if($food->image)

                                    <img
                                        src="{{ $food->image }}"
                                        class="food-image"
                                        alt="{{ $food->name }}"
                                        loading="lazy"
                                        onerror="
                                            this.style.display='none';
                                            this.nextElementSibling.style.display='flex';
                                        "
                                    >


                                    <!-- FALLBACK -->

                                    <div
                                        class="food-placeholder"
                                        style="display:none;"
                                    >
                                        🍔
                                    </div>


                                @else


                                    <div class="food-placeholder">

                                        🍔

                                    </div>


                                @endif


                            </div>

                        </td>


                        <!-- FOOD -->

                        <td>

                            <strong>

                                {{ $food->name }}

                            </strong>


                            @if($food->description)

                                <div
                                    style="
                                        color:#777;
                                        font-size:13px;
                                        margin-top:5px;
                                    "
                                >

                                    {{ Str::limit($food->description, 50) }}

                                </div>

                            @endif


                        </td>


                        <!-- CATEGORY -->

                        <td>

                            {{ $food->category->name ?? 'No Category' }}

                        </td>


                        <!-- PRICE -->

                        <td>

                            <strong>

                                Rs.
                                {{ number_format($food->price, 2) }}

                            </strong>

                        </td>


                        <!-- STATUS -->

                        <td>


                            @if($food->is_available)


                                <span class="status available">

                                    Available

                                </span>


                            @else


                                <span class="status unavailable">

                                    Unavailable

                                </span>


                            @endif


                        </td>


                        <!-- ACTIONS -->

                        <td>


                            <div class="actions">


                                <!-- VIEW -->

                                <a
                                    href="{{ route('admin.food.show', $food) }}"
                                    class="btn btn-view"
                                >
                                    👁 View
                                </a>


                                <!-- EDIT -->

                                <a
                                    href="{{ route('admin.food.edit', $food) }}"
                                    class="btn btn-edit"
                                >
                                    ✏️ Edit
                                </a>


                                <!-- DELETE -->

                                <form
                                    action="{{ route('admin.food.destroy', $food) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this food item?')"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="btn btn-delete"
                                    >
                                        🗑 Delete
                                    </button>

                                </form>


                            </div>


                        </td>


                    </tr>


                @endforeach


                </tbody>


            </table>


        @else


            <!-- EMPTY -->

            <div class="empty">


                <h2>
                    No Food Items Yet 🍔
                </h2>


                <p
                    style="
                        margin:10px 0 20px;
                    "
                >
                    Start by adding your first food item.
                </p>


                <a
                    href="{{ route('admin.food.create') }}"
                    class="btn btn-primary"
                >
                    + Add Food
                </a>


            </div>


        @endif


    </div>


</div>


</body>

</html>
