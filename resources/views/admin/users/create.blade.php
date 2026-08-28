<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add User - FoodHub</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0,0,0,.08);
        }

        h1 {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button,
        .back {
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        button {
            background: #ff6b00;
            color: white;
        }

        .back {
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
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>

<body>

@include('admin.partials.sidebar')
n<div class="admin-layout">
<div class="admin-main">
    <div class="mobile-header no-print" style="display:none;background:#0f172a;padding:12px 16px;align-items:center;gap:12px;position:sticky;top:0;z-index:50;">
        <button onclick="openSidebar()" style="background:rgba(255,255,255,.1);border:none;color:white;width:40px;height:40px;border-radius:10px;font-size:20px;cursor:pointer;">☰</button>
        <span style="color:white;font-weight:700;font-size:16px;">🍔 FoodHub</span>
    </div>

<div class="container">

    <div class="card">

        <h1>➕ Add New User</h1>

        @if($errors->any())
            <div class="error">
                <ul style="padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">

            @csrf

            <div class="form-group">
                <label>Name</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter name"
                    required
                >
            </div>

            <div class="form-group">
                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter email"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Minimum 8 characters"
                    required
                >
            </div>

            <div class="form-group">
                <label>Confirm Password</label>

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Repeat password"
                    required
                >
            </div>

            <div class="form-group">
                <label>Role</label>

                <select name="role" required>

                    <option value="Admin">
                        Admin
                    </option>

                    <option value="Manager">
                        Manager
                    </option>

                </select>
            </div>

            <div class="buttons">

                <button type="submit">
                    💾 Create User
                </button>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="back"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</div>
</div>
    <script src="{{ asset('js/scroll-animations.js') }}"></script>
</body>
</html>
