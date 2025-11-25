<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Trator</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/trator/css/style.css">
    
    <style>
        body {
            background-color: var(--dark-surface);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .form-control {
            background-color: var(--dark);
            border-color: var(--border-color);
            color: var(--text-main);
            padding: 12px 16px;
        }
        .form-control:focus {
            background-color: var(--dark);
            border-color: var(--dark-surface);
            color: var(--text-main);
            box-shadow: none;
        }
        .btn-primary-custom {
            width: 100%;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-white mb-1">Admin Login</h2>
            <p class="text-main" style="color: var(--text-main);">Access the dashboard</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 text-center mb-4">
                <?php echo $_GET['error'] == 'invalid' ? 'Invalid username or password' : 'An error occurred'; ?>
            </div>
        <?php endif; ?>
        
        <form action="/trator/admin/login_process" method="POST">
            <div class="mb-3">
                <label class="form-label text-main" style="color: var(--text-main);">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark text-main" style="color: var(--text-main); border-color: var(--border-color);"><i class="ph ph-user" style="color: #777;"></i></span>
                    <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Enter username" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label text-main" style="color: var(--text-main);">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark  text-main" style="color: var(--text-main); border-color: var(--border-color);"><i class="ph ph-lock" style="color: #777;"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Enter password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary-custom mb-4" style="justify-content: center;">
                Sign In <i class="ph ph-sign-in ms-2"></i>
            </button>
        </form>
        
        <div class="text-center">
            <a href="/trator/home" class="text-main text-decoration-none d-flex align-items-center justify-content-center gap-2" style="color: var(--text-main);">
                <i class="ph ph-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>
</body>
</html>