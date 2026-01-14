<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tahograf Servis</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #E8F0FE;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h1 {
            font-size: 28px;
            color: #1A73E8;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #666;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #CCCCCC;
            border-radius: 10px;
            background-color: white;
            transition: all 0.2s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #1A73E8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password a {
            color: #1A73E8;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background-color: #1A73E8;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .login-button:hover {
            background-color: #0d62d9;
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #eee;
        }

        .register-link a {
            color: #1A73E8;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .demo-access {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            font-size: 14px;
        }

        .demo-access h3 {
            color: #555;
            font-size: 15px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .demo-access ul {
            list-style: none;
        }

        .demo-access li {
            margin-bottom: 8px;
            color: #666;
        }

        .demo-access .role {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }

        .role-client {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .role-serviser {
            background-color: #f0f9ff;
            color: #0c4a6e;
        }

        .role-admin {
            background-color: #eff6ff;
            color: #1e40af;
        }

        .error-message {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 15px;
            border-left: 4px solid #dc2626;
        }

        @media (max-width: 500px) {
            .login-container {
                padding: 30px 25px;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
            }

            .forgot-password {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Prijava</h1>
            <p>Sistem za servisiranje tahografa</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="status-message" style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #10b981;">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="error-message">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="vas.email@primer.com">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Lozinka</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Unesite lozinku">
            </div>

            <!-- Remember Me -->
            <div class="remember-forgot">
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me" style="margin-bottom:0; color: #666; font-size: 14px;">{{ __('Zapamti me') }}</label>
                </div>

                @if (Route::has('password.request'))
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">{{ __('Zaboravili ste lozinku?') }}</a>
                    </div>
                @endif
            </div>

            <button type="submit" class="login-button">
                {{ __('Prijavi se') }}
            </button>
        </form>

        <div class="register-link">
            <p>Nemate nalog? <a href="{{ route('register') }}">Registrujte se</a></p>
        </div>
    </div>
</body>
</html>