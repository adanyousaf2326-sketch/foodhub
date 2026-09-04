<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Registration - FoodHub</title>
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
        .register-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .register-header {
            background: linear-gradient(135deg, #16a34a, #15803d);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .register-header .rider-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
        }
        .register-header h1 { font-size: 24px; margin-bottom: 4px; }
        .register-header p { font-size: 13px; opacity: 0.8; }
        .register-body { padding: 30px; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-weight: 700;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-group label i { color: #16a34a; margin-right: 4px; }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22,163,74,0.1);
        }
        .form-group textarea { min-height: 70px; resize: vertical; }
        .register-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .register-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,0.3); }
        .login-link {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #6b7280;
        }
        .login-link a { color: #16a34a; font-weight: 700; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            border: 1px solid #fecaca;
        }
        .success-box {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            border: 1px solid #bbf7d0;
        }
        .info-note {
            background: #eff6ff;
            color: #1e40af;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        @media(max-width: 500px) {
            .register-body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <div class="rider-icon">🛵</div>
            <h1>Join as Rider</h1>
            <p>Register to deliver food with FoodHub</p>
        </div>
        <div class="register-body">
            @if($errors->any())
                <div class="error-box">
                    <strong><i class="fas fa-exclamation-circle"></i> Please fix:</strong>
                    <ul style="margin-left:16px;margin-top:6px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="success-box"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <div class="info-note">
                <i class="fas fa-info-circle"></i> After registration, admin will review and approve your account. You can then login and start delivering!
            </div>

            <form method="POST" action="{{ route('rider.register.submit') }}">
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Muhammad Ali" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0300-1234567" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> CNIC Number</label>
                    <input type="text" name="cnic" value="{{ old('cnic') }}" placeholder="35202-1234567-1" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Address</label>
                    <textarea name="address" placeholder="Your full address">{{ old('address') }}</textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-camera"></i> Photo URL (optional)</label>
                    <input type="url" name="photo" value="{{ old('photo') }}" placeholder="https://example.com/photo.jpg">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="Min 6 characters" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Re-enter password" required>
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i> Register as Rider
                </button>
            </form>

            <div class="login-link">
                Already registered? <a href="{{ route('rider.login') }}">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>
