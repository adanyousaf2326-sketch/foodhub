<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body {
            min-height: 100vh;
            background: #050510;
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
            background: radial-gradient(ellipse at 25% 40%, rgba(139,92,246,.12) 0%, transparent 50%),
                        radial-gradient(ellipse at 75% 60%, rgba(255,107,0,.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 90%, rgba(239,68,68,.06) 0%, transparent 40%);
            animation: bgPulse 10s ease-in-out infinite alternate;
        }
        @keyframes bgPulse {
            0% { transform: scale(1) rotate(0deg); }
            100% { transform: scale(1.1) rotate(-4deg); }
        }

        /* Floating kitchen items */
        .floating-item {
            position: absolute;
            font-size: 40px;
            opacity: 0.08;
            animation: floatUp linear infinite;
            pointer-events: none;
        }
        @keyframes floatUp {
            0% { transform: translateY(110vh) rotate(0deg) scale(0.5); opacity: 0; }
            10% { opacity: 0.08; }
            90% { opacity: 0.08; }
            100% { transform: translateY(-10vh) rotate(360deg) scale(1.2); opacity: 0; }
        }

        /* Glow orbs */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.2;
            animation: orbFloat 14s ease-in-out infinite alternate;
        }
        .glow-orb:nth-child(1) { width: 350px; height: 350px; background: #8b5cf6; top: 5%; left: 5%; }
        .glow-orb:nth-child(2) { width: 280px; height: 280px; background: #ff6b00; bottom: 5%; right: 5%; animation-delay: -5s; }
        .glow-orb:nth-child(3) { width: 200px; height: 200px; background: #ef4444; top: 60%; left: 50%; animation-delay: -10s; }
        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -30px) scale(1.15); }
            66% { transform: translate(-30px, 40px) scale(0.85); }
            100% { transform: translate(15px, -15px) scale(1.05); }
        }

        /* Grid lines */
        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(139,92,246,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139,92,246,.025) 1px, transparent 1px);
            background-size: 70px 70px;
            animation: gridMove 25s linear infinite;
        }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(70px, 70px); }
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
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 30px 70px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.05);
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
            background: linear-gradient(90deg, transparent, #8b5cf6, #a78bfa, #8b5cf6, transparent);
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
            border-radius: 22px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 16px;
            box-shadow: 0 12px 35px rgba(139,92,246,.35);
            animation: logoPulse 3s ease-in-out infinite;
            position: relative;
        }
        .logo-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 25px;
            background: linear-gradient(135deg, #8b5cf6, transparent, #a78bfa);
            z-index: -1;
            opacity: 0.4;
            animation: logoSpin 8s linear infinite;
        }
        @keyframes logoPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.06); }
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
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .logo-section p {
            color: rgba(255,255,255,.4);
            font-size: 14px;
            margin-top: 6px;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(139,92,246,.15);
            border: 1px solid rgba(139,92,246,.25);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 11px;
            color: #a78bfa;
            margin-top: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
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
        .alert-success { background: rgba(22,163,74,.12); color: #4ade80; border: 1px solid rgba(22,163,74,.2); }
        .alert-error { background: rgba(239,68,68,.12); color: #f87171; border: 1px solid rgba(239,68,68,.2); }

        /* Form */
        .form-group {
            margin-bottom: 20px;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(12px);
        }
        .form-group:nth-child(1) { animation-delay: 0.4s; }
        .form-group:nth-child(2) { animation-delay: 0.5s; }
        .form-group:nth-child(3) { animation-delay: 0.6s; }
        @keyframes formEntry {
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 12px;
            color: rgba(255,255,255,.5);
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
            color: rgba(255,255,255,.25);
            font-size: 15px;
            transition: all 0.3s;
            z-index: 2;
        }
        .input-wrap input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            background: rgba(255,255,255,.04);
            border: 2px solid rgba(255,255,255,.07);
            border-radius: 14px;
            font-size: 15px;
            color: white;
            outline: none;
            transition: all 0.3s;
        }
        .input-wrap input::placeholder { color: rgba(255,255,255,.2); }
        .input-wrap input:focus {
            border-color: #8b5cf6;
            background: rgba(139,92,246,.04);
            box-shadow: 0 0 0 4px rgba(139,92,246,.1);
        }
        .input-wrap:focus-within i { color: #8b5cf6; }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.65s forwards;
            opacity: 0;
        }
        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #8b5cf6;
            cursor: pointer;
        }
        .remember-row label {
            color: rgba(255,255,255,.4);
            font-size: 13px;
            cursor: pointer;
        }

        /* Submit */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
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
            box-shadow: 0 12px 35px rgba(139,92,246,.4);
        }
        .submit-btn:hover::before { left: 100%; }

        /* Footer */
        .footer-link {
            text-align: center;
            margin-top: 24px;
            animation: formEntry 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.8s forwards;
            opacity: 0;
        }
        .footer-link a {
            color: rgba(255,255,255,.35);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .footer-link a:hover { color: #8b5cf6; }

        /* Particles */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #8b5cf6;
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
            .login-card { padding: 30px 20px; }
            .logo-section h1 { font-size: 24px; }
            .logo-icon { width: 65px; height: 65px; font-size: 30px; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>
<body>
    <!-- Background -->
    <div class="bg-animation">
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
        <div class="grid-lines"></div>
    </div>

    <div id="floatingItems"></div>

    <!-- Login Card -->
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">👨‍💼</div>
                <h1>Food<span>Hub</span></h1>
                <p>Admin Control Panel</p>
                <div class="role-badge"><i class="fas fa-shield-alt"></i> Authorized Personnel Only</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@foodhub.com" required autofocus>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                </button>
            </form>

            <div class="footer-link">
                <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Back to Website</a>
            </div>
        </div>
    </div>

    <script>
        // Floating kitchen items
        var items = ['👨‍🍳','🍳','🔪','📊','📋','✅','🛡️','⚙️','📦','🍕','🍔','🍜','☕','🏷️','💰','🛵','🔥','📋'];
        var container = document.getElementById('floatingItems');

        function createItem() {
            var el = document.createElement('div');
            el.className = 'floating-item';
            el.textContent = items[Math.floor(Math.random() * items.length)];
            el.style.left = Math.random() * 100 + '%';
            el.style.fontSize = (20 + Math.random() * 28) + 'px';
            el.style.animationDuration = (10 + Math.random() * 15) + 's';
            el.style.animationDelay = Math.random() * 6 + 's';
            container.appendChild(el);
            setTimeout(function() { el.remove(); }, 30000);
        }
        for (var i = 0; i < 14; i++) { setTimeout(createItem, i * 500); }
        setInterval(createItem, 3000);

        // Particles on focus
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
    </script>
</body>
</html>
