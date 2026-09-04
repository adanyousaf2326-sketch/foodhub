<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodHub</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ff6b00">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.svg') }}">
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
        .auth-logo p { color: #6b7280; font-size: 14px; margin-top: 6px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; font-size: 14px; }
        .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; outline: none; transition: .2s; }
        .form-group input:focus { border-color: #ff6b00; box-shadow: 0 0 0 3px rgba(255,107,0,.1); }
        .form-group input::placeholder { color: #9ca3af; }
        .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, #ff6b00, #ff8c33); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: .2s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,107,0,.3); }
        .auth-footer { text-align: center; margin-top: 20px; color: #6b7280; font-size: 14px; }
        .auth-footer a { color: #ff6b00; text-decoration: none; font-weight: 600; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 15px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .alert-info { background: #eff6ff; color: #1e40af; }
        .divider { text-align: center; margin: 20px 0; position: relative; }
        .divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e5e7eb; }
        .divider span { background: white; padding: 0 12px; color: #9ca3af; font-size: 13px; position: relative; }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 16px; }
        .input-icon input { padding-left: 42px; }
        .quick-info { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px; margin-bottom: 16px; font-size: 13px; color: #166534; text-align: center; }
        .quick-info i { margin-right: 4px; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <div class="logo-icon">🍽️</div>
            <h1>Food<span>Hub</span></h1>
            <p>Login with your phone number or email</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if(session('info'))
            <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
        @endif

        <div class="quick-info">
            <i class="fas fa-bolt"></i> No password needed! Just enter your phone or email to login.
        </div>

        <form method="POST" action="{{ route('customer.login.submit') }}">
            @csrf

            <div class="form-group">
                <label>Phone Number or Email</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="phone_or_email"
                           value="{{ old('phone_or_email') }}"
                           placeholder="0300-1234567 or email@email.com"
                           required autofocus>
                </div>
            </div>

            @error('phone_or_email')
                <div class="alert alert-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="divider"><span>or</span></div>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('customer.register') }}">Register here</a>
        </div>

        <div class="auth-footer" style="margin-top: 12px;">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Browse as Guest</a>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(function() {});
        }
    </script>
</body>
</html>
