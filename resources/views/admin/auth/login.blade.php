<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Login - Smart CMS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        * { box-sizing: border-box; }
        body {
            background: #0f0f0f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            width: 60px; height: 60px;
            background: #e63946;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 20px;
        }
        .form-control {
            background: #242424 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #fff !important;
            padding: 12px 15px;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: #e63946 !important;
            box-shadow: 0 0 0 3px rgba(230,57,70,0.2) !important;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.3) !important; }
        .form-label { color: rgba(255,255,255,0.7); font-size: 0.875rem; }
        .btn-login {
            background: #e63946;
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn-login:hover { background: #c1121f; }
        h4 { color: #fff; font-weight: 700; }
        p { color: rgba(255,255,255,0.5); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <i class="fas fa-car text-white"></i>
        </div>
        <h4 class="text-center mb-1">Smart CMS</h4>
        <p class="text-center mb-4 small">Admin Panel Login</p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control"
                       placeholder="admin@smartcms.com"
                       value="{{ old('email') }}" required/>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </form>
    </div>
</body>
</html>