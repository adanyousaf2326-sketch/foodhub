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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body {
            min-height: 100vh;
            background: #0a0a0a;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
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
            background: radial-gradient(ellipse at 30% 50%, rgba(255,107,0,.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 50%, rgba(255,140,51,.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(220,38,38,.08) 0%, transparent 40%);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }
        @keyframes bgPulse {
            0% { transform: scale(1) rotate(0deg); }
            100% { transform: scale(1.1) rotate(3deg); }
        }

        /* Floating food emojis */
        .floating-food {
            position: absolute;
            font-size: 40px;
            opacity: 0.12;
            animation: floatUp linear infinite;
            pointer-events: none;
        }
        @keyframes floatUp {
            0% { transform: translateY(110vh) rotate(0deg) scale(0.5); opacity: 0; }
            10% { opacity: 0.12; }
            90% { opacity: 0.12; }
            100% { transform: translateY(-10vh) rotate(360deg) scale(1.2); opacity: 0; }
        }

        /* Glow orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: orbFloat 12s ease-in-out infinite alternate;
        }
        .glow-orb:nth-child(1) { width: 300px; height: 300px; background: #ff6b00; top: 10%; left: 10%; }
        .glow-orb:nth-child(2) { width: 250px; height: 250px; background: #dc2626; bottom: 10%; right: 10%; animation-delay: -4s; }
        .glow-orb:nth-child(3) { width: 200px; height: 200px; background: #f59e0b; top: 50%; left: 50%; animation-delay: -8s; }
        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.1); }
            66% { transform: translate(-20px, 30px) scale(0.9); }
            100% { transform: translate(10px, -10px) scale(1.05); }
        }

        /* Grid lines */
        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,107,0,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,107,0,.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* ===== LOGIN CARD ===== */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: cardEntry 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        @keyframes cardEntry {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 25px 60px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.05);
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ff6b00, #ff8c33, #ff6b00, transparent);
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Logo */
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
            animation: logoEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes logoEntry {
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #ff6b00, #ff8c33);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: 0 auto 16px;
            box-shadow: 0 10px 30px rgba(255,107,0,.3);
            animation: logoPulse 3s ease-in-out infinite;
            position: relative;
        }
        .logo-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 23px;
            background: linear-gradient(135deg, #ff6b00, transparent, #ff8c33);
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
            font-size: 32px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
        }
        .logo-section h1 span {
            background: linear-gradient(135deg, #ff6b00, #ff8c33);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .logo-section p {
            color: rgba(255,255,255,.5);
            font-size: 14px;
            margin-top: 6px;
        }

        /* Info badge */
        .info-badge {
            background: rgba(255,107,0,.1);
            border: 1px solid rgba(255,107,0,.2);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: badgeEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        @keyframes badgeEntry {
            to { opacity: 1; transform: translateY(0); }
        }
        .info-badge i { color: #ff6b00; font-size: 16px; }
        .info-badge span { color: rgba(255,255,255,.7); font-size: 13px; line-height: 1.4; }

        /* Form */
        .form-group {
            margin-bottom: 20px;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        .form-group:nth-child(2) { animation-delay: 0.6s; }
        .form-group:nth-child(3) { animation-delay: 0.7s; }
        @keyframes formEntry {
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: rgba(255,255,255,.6);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.3);
            font-size: 16px;
            transition: all 0.3s;
            z-index: 2;
        }
        .input-wrap input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            background: rgba(255,255,255,.05);
            border: 2px solid rgba(255,255,255,.08);
            border-radius: 14px;
            font-size: 15px;
            color: white;
            outline: none;
            transition: all 0.3s;
        }
        .input-wrap input::placeholder {
            color: rgba(255,255,255,.25);
        }
        .input-wrap input:focus {
            border-color: #ff6b00;
            background: rgba(255,107,0,.05);
            box-shadow: 0 0 0 4px rgba(255,107,0,.1);
        }
        .input-wrap input:focus + i,
        .input-wrap input:focus ~ i {
            color: #ff6b00;
        }
        .input-wrap:focus-within i {
            color: #ff6b00;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff6b00, #ff8c33);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            animation: btnEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.8s forwards;
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
            box-shadow: 0 10px 30px rgba(255,107,0,.4);
        }
        .submit-btn:hover::before {
            left: 100%;
        }
        .submit-btn:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.9s forwards;
            opacity: 0;
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

        /* Footer links */
        .footer-links {
            text-align: center;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 1s forwards;
            opacity: 0;
        }
        .footer-links a {
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .footer-links a:hover {
            color: #ff6b00;
        }
        .footer-links a.highlight {
            color: #ff6b00;
            font-weight: 600;
        }
        .footer-links a.highlight:hover {
            text-decoration: underline;
        }
        .footer-links p {
            margin-top: 12px;
            color: rgba(255,255,255,.25);
            font-size: 12px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .login-card { padding: 30px 20px; }
            .logo-section h1 { font-size: 26px; }
            .logo-icon { width: 65px; height: 65px; font-size: 30px; }
        }

        /* ===== PARTICLES ===== */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #ff6b00;
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

        /* Sparkle effect on logo hover */
        .logo-icon:hover {
            animation: logoPulse 0.5s ease;
            box-shadow: 0 15px 40px rgba(255,107,0,.5);
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

    <!-- Floating Food Emojis -->
    <div id="floatingFood"></div>

    <!-- Login Card -->
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Shimmer top line is CSS ::before -->

            <!-- Logo -->
            <div class="logo-section">
                <div class="logo-icon">🍽️</div>
                <h1>Food<span>Hub</span></h1>
                <p>Welcome back! Login to order delicious food</p>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                </div>
            @endif

            <!-- Quick Info -->
            <div class="info-badge">
                <i class="fas fa-bolt"></i>
                <span>No password needed! Just enter your phone or email to login instantly.</span>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('customer.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label>Phone Number or Email</label>
                    <div class="input-wrap">
                        <input type="text" name="phone_or_email"
                               value="{{ old('phone_or_email') }}"
                               placeholder="0300-1234567 or email@email.com"
                               required autofocus>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                @error('phone_or_email')
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="divider"><span>or</span></div>

            <div class="footer-links">
                <p>Don't have an account? <a href="{{ route('customer.register') }}" class="highlight">Create one now →</a></p>
                <p style="margin-top:10px;"><a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Browse as Guest</a></p>
            </div>
        </div>
    </div>

    <script>
        // ===== Floating Food Emojis =====
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

        // Create initial batch
        for (var i = 0; i < 15; i++) {
            setTimeout(createFood, i * 400);
        }
        // Continuously add more
        setInterval(createFood, 2000);

        // ===== Input Focus Particles =====
        document.querySelectorAll('.input-wrap input').forEach(function(input) {
            input.addEventListener('focus', function() {
                var wrap = this.closest('.input-wrap');
                for (var j = 0; j < 6; j++) {
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

        // ===== Service Worker =====
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(function() {});
        }
    </script>
</body>
</html>
