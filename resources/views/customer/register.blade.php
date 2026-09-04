<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #ff6b00 0%, #ff8c33 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-card { background: white; border-radius: 20px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .auth-logo { text-align: center; margin-bottom: 30px; }
        .auth-logo .logo-icon { font-size: 48px; background: linear-gradient(135deg, #ff6b00, #ff8c33); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .auth-logo h1 { font-size: 28px; color: #111; margin-top: 8px; }
        .auth-logo h1 span { color: #ff6b00; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; outline: none; transition: .2s; }
        .form-group input:focus { border-color: #ff6b00; box-shadow: 0 0 0 3px rgba(255,107,0,.1); }
        .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, #ff6b00, #ff8c33); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; transition: .2s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,107,0,.3); }
        .auth-footer { text-align: center; margin-top: 20px; color: #6b7280; font-size: 14px; }
        .auth-footer a { color: #ff6b00; text-decoration: none; font-weight: 600; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 15px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <div class="logo-icon">🍽️</div>
            <h1>Food<span>Hub</span></h1>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('customer.register.submit') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Khan" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ahmed@email.com" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0300-1234567" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" placeholder="Min 6 characters" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat password" required>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('customer.login') }}">Login here</a>
        </div>
    </div>
</body>
</html>
