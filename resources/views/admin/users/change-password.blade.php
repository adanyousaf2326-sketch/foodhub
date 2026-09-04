<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - FoodHub</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #f4f6f9; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 5px 25px rgba(0,0,0,.08); }
        h1 { margin-bottom: 8px; font-size: 24px; color: #111827; }
        .subtitle { color: #6b7280; font-size: 14px; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 7px; font-weight: 700; color: #374151; font-size: 14px; }
        label i { color: #ff6b00; margin-right: 4px; }
        input {
            width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px;
            font-size: 14px; outline: none; transition: border-color 0.2s;
        }
        input:focus { border-color: #ff6b00; box-shadow: 0 0 0 3px rgba(255,107,0,.1); }
        .btn {
            width: 100%; padding: 14px; background: linear-gradient(135deg, #ff6b00, #e85f00);
            color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: transform 0.2s; margin-top: 10px;
        }
        .btn:hover { transform: translateY(-1px); }
        .back { display: inline-block; margin-top: 16px; color: #6b7280; text-decoration: none; font-size: 14px; }
        .back:hover { color: #111827; }
        .success { background: #dcfce7; color: #166534; padding: 14px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; }
        .error-box { background: #fee2e2; color: #991b1b; padding: 14px; border-radius: 10px; margin-bottom: 20px; }
        .error-box ul { margin-left: 16px; margin-top: 6px; }
        .user-info { display: flex; align-items: center; gap: 12px; padding: 14px; background: #f8fafc; border-radius: 10px; margin-bottom: 20px; }
        .user-avatar { width: 48px; height: 48px; border-radius: 50%; background: #ff6b00; color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; }
        .user-name { font-weight: 700; font-size: 16px; }
        .user-email { font-size: 13px; color: #6b7280; }
        .info-box { background: #eff6ff; color: #1e40af; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; line-height: 1.5; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
</head>
<body>

@include('admin.partials.topbar')

<div class="container">
    <div class="card">
        <h1><i class="fas fa-lock"></i> Change Password</h1>
        <p class="subtitle">Update your account password</p>

        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email">{{ $user->email }}</div>
            </div>
        </div>

        @if(session('success'))
            <div class="success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error-box">
                <strong><i class="fas fa-exclamation-circle"></i> Please fix:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            <i class="fas fa-info-circle"></i> Password must be at least 8 characters. You'll be logged out on other devices after changing.
        </div>

        <form method="POST" action="{{ route('admin.update-password') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Current Password</label>
                <input type="password" name="current_password" placeholder="Enter current password" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-key"></i> New Password</label>
                <input type="password" name="password" placeholder="Enter new password (min 8 characters)" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-check-double"></i> Confirm New Password</label>
                <input type="password" name="password_confirmation" placeholder="Re-enter new password" required>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-save"></i> Update Password
            </button>
        </form>

        <a href="{{ route('admin.users.index') }}" class="back">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
</div>

</body>
</html>
