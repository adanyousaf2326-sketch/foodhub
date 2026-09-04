<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Login - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header {
            background: linear-gradient(135deg, #ff6b00, #e85f00);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .login-header .rider-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 28px;
        }
        .login-header h1 { font-size: 22px; }
        .login-header p { font-size: 13px; opacity: 0.8; margin-top: 4px; }
        .login-body { padding: 30px; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 6px;
        }
        .form-group label i { color: #ff6b00; margin-right: 4px; }
        .form-group input {
            width: 100%; padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 10px;
            font-size: 14px; outline: none; transition: border-color 0.2s;
        }
        .form-group input:focus { border-color: #ff6b00; box-shadow: 0 0 0 3px rgba(255,107,0,0.1); }
        .login-btn {
            width: 100%; padding: 14px; background: linear-gradient(135deg, #ff6b00, #e85f00);
            color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 700;
            cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,0,0.3); }
        .register-link { text-align: center; margin-top: 16px; font-size: 14px; color: #6b7280; }
        .register-link a { color: #16a34a; font-weight: 700; text-decoration: none; }
        .error-box {
            background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px;
            margin-bottom: 16px; font-size: 13px; border: 1px solid #fecaca;
        }
        .success-box {
            background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px;
            margin-bottom: 16px; font-size: 13px; border: 1px solid #bbf7d0;
        }
        .home-link { text-align: center; margin-top: 12px; }
        .home-link a { color: #6b7280; font-size: 13px; text-decoration: none; }
        .home-link a:hover { color: #111827; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="rider-icon">🛵</div>
            <h1>Rider Login</h1>
            <p>Login to manage your deliveries</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif
            @if(session('success'))
                <div class="success-box"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('rider.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0300-1234567" required autofocus>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div class="register-link">New rider? <a href="{{ route('rider.register') }}">Register here</a></div>
            <div class="home-link"><a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Back to FoodHub</a></div>
        </div>
    </div>
</body>
</html>
