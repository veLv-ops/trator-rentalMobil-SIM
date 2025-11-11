<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Trator</title>
    <link rel="stylesheet" href="/trator/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .login-container { max-width: 400px; margin: 100px auto; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header text-center">
                <h4>Admin Login</h4>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_GET['error'] == 'invalid' ? 'Username atau password salah!' : 'Terjadi kesalahan!'; ?>
                    </div>
                <?php endif; ?>
                
                <form action="/trator/admin/login_process" method="POST">
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="/trator/home" class="text-muted">← Kembali ke Website</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>