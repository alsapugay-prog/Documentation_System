<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DSB Documentation System</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #060d1f;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 1rem;
        }

        .wave-left {
            position: fixed; left: -60px; top: 50%; transform: translateY(-50%);
            width: 320px; height: 500px; opacity: 0.35; pointer-events: none; z-index: 0;
        }
        .wave-right {
            position: fixed; right: -60px; top: 50%; transform: translateY(-50%);
            width: 320px; height: 500px; opacity: 0.35; pointer-events: none; z-index: 0;
        }

        .login-card {
            position: relative; z-index: 10;
            background: rgba(15, 23, 45, 0.92);
            border: 1px solid rgba(59, 130, 246, 0.18);
            border-radius: 20px;
            padding: 2.5rem 2.5rem 2rem;
            width: 100%; max-width: 460px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.04);
        }

        .card-logo { text-align: center; margin-bottom: 1.25rem; }

        /* DSB Logo circle */
       .dsb-logo-wrap {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white; /* Optional: lagyan ng background kung transparent ang image */
            border-radius: 50%;
            border: 2.5px solid rgba(59, 130, 246, 0.35);
            box-shadow: 0 0 24px rgba(59, 130, 246, 0.2);
            overflow: hidden;
        }

        .dsb-logo-wrap img {
            width: 100%;       /* Gawing 100% lang para hindi ma-stretch nang sobra */
            height: 100%;
            object-fit: cover; /* Pinupuno nito ang container */
            object-position: center; /* Siguradong nasa gitna ang focus */
            display: block;
        }
        .card-title { font-size: 1.5rem; font-weight: 700; color: #f0f4ff; text-align: center; letter-spacing: -0.3px; }
        .card-subtitle { font-size: 0.875rem; color: #8899bb; text-align: center; margin-top: 0.25rem; }

        .welcome-section { text-align: center; margin: 1.25rem 0 1.5rem; }
        .welcome-title { font-size: 1.05rem; font-weight: 700; color: #eef2ff; }
        .welcome-sub { font-size: 0.8rem; color: #6b7fa3; margin-top: 0.2rem; }

        .form-group { margin-bottom: 1.1rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 500; color: #a0b0cc; margin-bottom: 0.5rem; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #4a607f; font-size: 0.9rem; pointer-events: none; }
        .form-input {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 10px;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            font-size: 0.875rem; color: #cdd8ee; outline: none;
            transition: border-color 0.2s, background 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-input::placeholder { color: #3d5070; }
        .form-input:focus { border-color: rgba(99, 149, 255, 0.5); background: rgba(255,255,255,0.06); }

        .password-toggle {
            position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
            color: #4a607f; cursor: pointer; font-size: 0.9rem;
            background: none; border: none; padding: 0; transition: color 0.2s;
        }
        .password-toggle:hover { color: #7a9acc; }

        .forgot-row { text-align: right; margin-top: 0.6rem; }
        .forgot-link { font-size: 0.8rem; color: #4a90d9; text-decoration: none; font-weight: 500; }
        .forgot-link:hover { color: #6aaef5; text-decoration: underline; }

        .btn-signin {
            width: 100%;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: #fff; font-size: 0.95rem; font-weight: 600;
            padding: 0.9rem 1rem; border: none; border-radius: 10px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            gap: 0.5rem; margin-top: 1.5rem; transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.35);
        }
        .btn-signin:hover {
            background: linear-gradient(135deg, #1d4fd8 0%, #2563eb 100%);
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.45);
            transform: translateY(-1px);
        }

        .or-divider { display: flex; align-items: center; gap: 0.75rem; margin: 1.25rem 0; }
        .or-line { flex: 1; height: 1px; background: rgba(255,255,255,0.08); }
        .or-text { font-size: 0.75rem; color: #4a607f; font-weight: 500; }

        .btn-google {
            width: 100%; background: transparent;
            border: 1px solid rgba(255,255,255,0.1); color: #c8d4ed;
            font-size: 0.875rem; font-weight: 500;
            padding: 0.85rem 1rem; border-radius: 10px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.6rem;
            transition: all 0.2s; font-family: 'Inter', sans-serif;
            text-decoration: none;
        }
        .btn-google:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.18); }
        .google-icon { width: 18px; height: 18px; }

        .alert-error {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.25rem;
            font-size: 0.8rem; color: #fca5a5;
        }
        .alert-success {
            background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3);
            border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.25rem;
            font-size: 0.8rem; color: #86efac;
        }

        .footer-text { position: relative; z-index: 10; font-size: 0.75rem; color: #2d4060; margin-top: 1.5rem; text-align: center; }
    </style>
</head>
<body>

    <svg class="wave-left" viewBox="0 0 300 500" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M280 50 C200 120, 80 80, 60 200 C40 320, 160 340, 100 450" stroke="#1d4ed8" stroke-width="1.2" fill="none" opacity="0.8"/>
        <path d="M260 30 C180 110, 60 70, 40 190 C20 310, 140 330, 80 440" stroke="#3b82f6" stroke-width="0.8" fill="none" opacity="0.5"/>
        <path d="M300 80 C220 150, 100 110, 80 230 C60 350, 180 370, 120 470" stroke="#60a5fa" stroke-width="0.6" fill="none" opacity="0.4"/>
        <path d="M240 10 C160 90, 40 50, 20 170 C0 290, 120 310, 60 420" stroke="#93c5fd" stroke-width="0.5" fill="none" opacity="0.3"/>
    </svg>

    <svg class="wave-right" viewBox="0 0 300 500" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform: translateY(-50%) scaleX(-1);">
        <path d="M280 50 C200 120, 80 80, 60 200 C40 320, 160 340, 100 450" stroke="#1d4ed8" stroke-width="1.2" fill="none" opacity="0.8"/>
        <path d="M260 30 C180 110, 60 70, 40 190 C20 310, 140 330, 80 440" stroke="#3b82f6" stroke-width="0.8" fill="none" opacity="0.5"/>
        <path d="M300 80 C220 150, 100 110, 80 230 C60 350, 180 370, 120 470" stroke="#60a5fa" stroke-width="0.6" fill="none" opacity="0.4"/>
        <path d="M240 10 C160 90, 40 50, 20 170 C0 290, 120 310, 60 420" stroke="#93c5fd" stroke-width="0.5" fill="none" opacity="0.3"/>
    </svg>

    <div class="login-card">

        <!-- DSB Logo -->
        <div class="card-logo">
            <div class="dsb-logo-wrap">
                <img src="{{ asset('images/dsb-logo.png') }}" alt="DSB Logo">
            </div>
            <h2 class="card-title">DSB Documentation</h2>
            <p class="card-subtitle">Documentation & Services System</p>
        </div>

        <div class="welcome-section">
            <div class="welcome-title">Welcome back!</div>
            <div class="welcome-sub">Please sign in to your account to continue</div>
        </div>

        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <div style="display:flex;align-items:center;gap:0.4rem;font-weight:600;margin-bottom:0.25rem;">
                    <i class="fa-solid fa-circle-exclamation"></i> Login Failed
                </div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           required autofocus placeholder="Enter your email address"
                           class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password"
                           required placeholder="Enter your password"
                           class="form-input" style="padding-right:2.75rem;">
                    <button type="button" class="password-toggle" onclick="togglePassword()" id="pwToggle">
                        <i class="fa-regular fa-eye" id="pwIcon"></i>
                    </button>
                </div>
                @if (Route::has('password.request'))
                <div class="forgot-row">
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot your password?</a>
                </div>
                @endif
            </div>

            <button type="submit" class="btn-signin">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div class="or-divider">
            <div class="or-line"></div>
            <span class="or-text">or continue with</span>
            <div class="or-line"></div>
        </div>

        {{-- Google Sign-in — redirect to Google OAuth --}}
        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg class="google-icon" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Sign in with Google
        </a>

    </div>

    <p class="footer-text">© 2024 DSB Documentation and Services. All rights reserved.</p>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-regular fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-regular fa-eye';
            }
        }
    </script>
</body>
</html>