<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #2563eb);
            font-family: Arial, sans-serif;
        }
        .login-card {
            max-width: 420px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            overflow: hidden;
            background: #fff;
        }
        .login-header {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff;
            padding: 28px 24px;
            text-align: center;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }
        .btn-login {
            border-radius: 999px;
            padding: 12px 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-card w-100">
            <div class="login-header">
                <h3 class="mb-1">Login Admin</h3>
                <p class="mb-0 opacity-75">Absensi Online</p>
            </div>
            <div class="p-4 p-md-5">
                @if(session('error'))
                    <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="admin" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="admin" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
