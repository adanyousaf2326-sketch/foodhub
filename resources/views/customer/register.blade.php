<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FoodHub</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ff6b00">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body {
            min-height: 100vh;
            background: #0a0a0a;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-animation {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }
        .bg-animation::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            top: -25%;
            left: -25%;
            background: radial-gradient(ellipse at 30% 50%, rgba(22,163,74,.12) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 50%, rgba(255,107,0,.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(245,158,11,.08) 0%, transparent 40%);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }
        @keyframes bgPulse {
            0% { transform: scale(1) rotate(0deg); }
            100% { transform: scale(1.1) rotate(-3deg); }
        }

        .floating-food {
            position: absolute;
            font-size: 40px;
            opacity: 0.1;
            animation: floatUp linear infinite;
            pointer-events: none;
        }
        @keyframes floatUp {
            0% { transform: translateY(110vh) rotate(0deg) scale(0.5); opacity: 0; }
            10% { opacity: 0.1; }
            90% { opacity: 0.1; }
            100% { transform: translateY(-10vh) rotate(360deg) scale(1.2); opacity: 0; }
        }

        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            animation: orbFloat 12s ease-in-out infinite alternate;
        }
        .glow-orb:nth-child(1) { width: 300px; height: 300px; background: #16a34a; top: 10%; right: 10%; }
        .glow-orb:nth-child(2) { width: 250px; height: 250px; background: #ff6b00; bottom: 10%; left: 10%; animation-delay: -4s; }
        .glow-orb:nth-child(3) { width: 200px; height: 200px; background: #f59e0b; top: 60%; left: 40%; animation-delay: -8s; }
        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.1); }
            66% { transform: translate(-20px, 30px) scale(0.9); }
            100% { transform: translate(10px, -10px) scale(1.05); }
        }

        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(22,163,74,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(22,163,74,.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* ===== REGISTER CARD ===== */
        .register-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            animation: cardEntry 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        @keyframes cardEntry {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .register-card {
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 25px 60px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.05);
            position: relative;
            overflow: hidden;
        }
        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #16a34a, #4ade80, #16a34a, transparent);
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Logo */
        .logo-section {
            text-align: center;
            margin-bottom: 28px;
            animation: logoEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes logoEntry {
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-icon {
            width: 75px;
            height: 75px;
            border-radius: 20px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin: 0 auto 14px;
            box-shadow: 0 10px 30px rgba(22,163,74,.3);
            animation: logoPulse 3s ease-in-out infinite;
            position: relative;
        }
        .logo-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 23px;
            background: linear-gradient(135deg, #16a34a, transparent, #4ade80);
            z-index: -1;
            opacity: 0.5;
            animation: logoSpin 6s linear infinite;
        }
        @keyframes logoPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes logoSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-section h1 {
            font-size: 28px;
            font-weight: 800;
            color: white;
        }
        .logo-section h1 span {
            background: linear-gradient(135deg, #16a34a, #4ade80);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .logo-section p {
            color: rgba(255,255,255,.5);
            font-size: 14px;
            margin-top: 4px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            animation: alertEntry 0.4s ease forwards;
        }
        @keyframes alertEntry {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-success { background: rgba(22,163,74,.15); color: #4ade80; border: 1px solid rgba(22,163,74,.2); }
        .alert-error { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.2); }
        .alert-info { background: rgba(59,130,246,.15); color: #60a5fa; border: 1px solid rgba(59,130,246,.2); }

        /* Form */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group.full { grid-column: 1 / -1; }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 11px;
            color: rgba(255,255,255,.5);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.25);
            font-size: 14px;
            transition: all 0.3s;
            z-index: 2;
        }
        .input-wrap input {
            width: 100%;
            padding: 14px 14px 14px 42px;
            background: rgba(255,255,255,.05);
            border: 2px solid rgba(255,255,255,.08);
            border-radius: 12px;
            font-size: 14px;
            color: white;
            outline: none;
            transition: all 0.3s;
        }
        .input-wrap input::placeholder {
            color: rgba(255,255,255,.2);
        }
        .input-wrap input:focus {
            border-color: #16a34a;
            background: rgba(22,163,74,.05);
            box-shadow: 0 0 0 4px rgba(22,163,74,.1);
        }
        .input-wrap:focus-within i {
            color: #16a34a;
        }

        /* Stagger animation for form groups */
        .form-group { animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(12px); }
        .form-group:nth-child(1) { animation-delay: 0.3s; }
        .form-group:nth-child(2) { animation-delay: 0.35s; }
        .form-group:nth-child(3) { animation-delay: 0.4s; }
        .form-group:nth-child(4) { animation-delay: 0.45s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.55s; }
        .form-group:nth-child(7) { animation-delay: 0.6s; }
        @keyframes formEntry {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Submit */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            margin-top: 8px;
            animation: btnEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.7s forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        @keyframes btnEntry {
            to { opacity: 1; transform: translateY(0); }
        }
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            transition: left 0.5s;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(22,163,74,.4);
        }
        .submit-btn:hover::before { left: 100%; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.1);
        }
        .divider span {
            color: rgba(255,255,255,.3);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Footer */
        .footer-links {
            text-align: center;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.8s forwards;
            opacity: 0;
        }
        .footer-links a {
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .footer-links a:hover { color: #16a34a; }
        .footer-links a.highlight { color: #16a34a; font-weight: 600; }
        .footer-links a.highlight:hover { text-decoration: underline; }
        .footer-links p { color: rgba(255,255,255,.25); font-size: 12px; margin-top: 8px; }

        /* Particles */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #16a34a;
            border-radius: 50%;
            opacity: 0;
            animation: particleFloat 4s ease-in-out infinite;
        }
        @keyframes particleFloat {
            0% { opacity: 0; transform: translateY(0) scale(0); }
            20% { opacity: 0.6; transform: translateY(-20px) scale(1); }
            80% { opacity: 0.6; }
            100% { opacity: 0; transform: translateY(-100px) scale(0); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .register-card { padding: 24px 18px; }
            .logo-section h1 { font-size: 24px; }
            .logo-icon { width: 60px; height: 60px; font-size: 28px; }
            .form-row { grid-template-columns: 1fr; }
            .form-row .form-group { grid-column: auto; }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
        <div class="grid-lines"></div>
    </div>

    <div id="floatingFood"></div>

    <!-- Register Card -->
    <div class="register-wrapper">
        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">🚀</div>
                <h1>Join <span>FoodHub</span></h1>
                <p>Create your account and start ordering</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('customer.register.submit') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <div class="input-wrap">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Khan" required>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-wrap">
                            <input type="text" name="phone" value="{{ old('phone', session('prefill_phone')) }}" placeholder="0300-1234567" required>
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <input type="email" name="email" value="{{ old('email', session('prefill_email')) }}" placeholder="ahmed@email.com" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password" placeholder="Min 6 characters" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="divider"><span>or</span></div>

            <div class="footer-links">
                <p>Already have an account? <a href="{{ route('customer.login') }}" class="highlight">Login here →</a></p>
                <p><a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Browse as Guest</a></p>
            </div>
        </div>
    </div>

    <script>
        // Floating Food
        var foods = ['🍕','🍔','🍟','🌮','🍜','🍣','🍩','🧁','🍰','☕','🥤','🍗','🥗','🍝','🍦','🫓','🌯','🧆'];
        var container = document.getElementById('floatingFood');

        function createFood() {
            var el = document.createElement('div');
            el.className = 'floating-food';
            el.textContent = foods[Math.floor(Math.random() * foods.length)];
            el.style.left = Math.random() * 100 + '%';
            el.style.fontSize = (20 + Math.random() * 30) + 'px';
            el.style.animationDuration = (8 + Math.random() * 12) + 's';
            el.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(el);
            setTimeout(function() { el.remove(); }, 25000);
        }

        for (var i = 0; i < 12; i++) {
            setTimeout(createFood, i * 500);
        }
        setInterval(createFood, 2500);

        // Particles on input focus
        document.querySelectorAll('.input-wrap input').forEach(function(input) {
            input.addEventListener('focus', function() {
                var wrap = this.closest('.input-wrap');
                for (var j = 0; j < 5; j++) {
                    var p = document.createElement('div');
                    p.className = 'particle';
                    p.style.left = (20 + Math.random() * 60) + '%';
                    p.style.bottom = '0';
                    p.style.animationDelay = (j * 0.1) + 's';
                    wrap.appendChild(p);
                    setTimeout(function(el) { el.remove(); }, 4000);
                }
            });
        });

        // Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(function() {});
        }
    </script>
</body>
</html>
