<?php $page_title = 'Trator - Auth'; ?>

<?php include 'templates/header.php'; ?>

<!-- Auth Section -->
<section class="min-vh-100 d-flex align-items-center bg-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card bg-dark-surface border-0 shadow-lg overflow-hidden" style="border-radius: 24px;">
                    <div class="row g-0">
                        <!-- Left Side: Form -->
                        <div class="col-lg-6 p-5">
                            <div class="d-flex align-items-center mb-5">
                                <a href="/trator/home" style="color: var(--text-main);" class="text-main text-decoration-none d-flex align-items-center">
                                    <i class="ph ph-arrow-left me-2" ></i> Back to Home
                                </a>
                            </div>

                            <div class="mb-4">
                                <h2 style="color: var(--text-main);" class="fw-bold display-6 mb-2">Welcome <span style="color: var(--text-main);" class="text-primary-custom">Back</span></h2>
                                <p style="color: #777;" class="text-main">Please enter your details to sign in.</p>
                            </div>

                            <?php if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger bg-danger text-white border-0 fade show" role="alert">
                                    <i class="ph ph-warning-circle me-2" style="color: var(--text-main);"></i>
                                    <?php
                                    switch ($_GET['error']) {
                                        case 'invalid_credentials':
                                            echo 'Invalid username or password.';
                                            break;
                                        case 'registration_failed':
                                            echo 'Registration failed. Username already taken.';
                                            break;
                                        case 'password_mismatch':
                                            echo 'Passwords do not match.';
                                            break;
                                        default:
                                            echo 'An error occurred.';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- Login Form -->
                            <form id="loginForm" action="auth_process" method="POST" class="auth-form active">
                                <input type="hidden" name="action" value="login">
                                <div class="mb-3">
                                    <label style="color: var(--text-main);" class="form-label text-main">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-main"><i class="ph ph-user" style="color: #777;"></i></span>
                                        <input type="text" name="username" class="form-control bg-dark border-secondary text-white" placeholder="Enter your username" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label style="color: var(--text-main);" class="form-label text-main">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-main"><i class="ph ph-lock-key" style="color: #777;"></i></span>
                                        <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="Enter your password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-custom w-100 justify-content-center mb-4">
                                    Sign In <i class="ph ph-sign-in ms-2"></i>
                                </button>
                                <p class="text-center text-main" style="color: var(--text-main);">
                                    Don't have an account? <a href="#" class="text-primary-custom text-decoration-none" onclick="toggleAuth('register')">Sign up</a>
                                </p>
                            </form>

                            <!-- Register Form -->
                            <form id="registerForm" action="auth_process" method="POST" class="auth-form d-none">
                                <input type="hidden" name="action" value="register">
                                <div class="mb-3">
                                    <label style="color: var(--text-main);" class="form-label text-main">Username</label>
                                    <div class="input-group">
                                        <span style="color: var(--text-main);" class="input-group-text bg-dark border-secondary text-main"><i class="ph ph-user" style="color: #777;"></i></span>
                                        <input type="text" name="username" class="form-control bg-dark border-secondary text-white" placeholder="Choose a username" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label style="color: var(--text-main);" class="form-label text-main">Password</label>
                                    <div class="input-group">
                                        <span style="color: var(--text-main);" class="input-group-text bg-dark border-secondary text-main"><i class="ph ph-lock-key" style="color: #777;"></i></span>
                                        <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="Create a password" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label style="color: var(--text-main);" class="form-label text-main">Confirm Password</label>
                                    <div class="input-group">
                                        <span style="color: var(--text-main);" class="input-group-text bg-dark border-secondary text-main"><i class="ph ph-lock-key" style="color: #777;"></i></span>
                                        <input type="password" name="confirm_password" class="form-control bg-dark border-secondary text-white" placeholder="Confirm your password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-custom w-100 justify-content-center mb-4">
                                    Create Account <i class="ph ph-user-plus ms-2"></i>
                                </button>
                                <p class="text-center text-main" style="color: var(--text-main);">
                                    Already have an account? <a href="#" class="text-primary-custom text-decoration-none" onclick="toggleAuth('login')">Sign in</a>
                                </p>
                            </form>
                        </div>

                        <!-- Right Side: Image/Carousel -->
                        <div class="col-lg-6 d-none d-lg-block position-relative">
                            <div class="h-100 w-100 position-absolute top-0 start-0">
                                <img src="images/banner-img.png" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.7);" alt="Auth Banner">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-dark"></div>
                            </div>
                            <div class="position-relative h-100 d-flex flex-column justify-content-center p-5 text-white">
                                <h2 class="display-5 fw-bold mb-3">Drive the Future</h2>
                                <p class="lead text-main">Join our community of premium car enthusiasts and experience the road like never before.</p>
                                <div class="mt-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="ph ph-check-circle text-primary-custom fs-4"></i>
                                        <span>Instant Verification</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="ph ph-check-circle text-primary-custom fs-4"></i>
                                        <span>Premium Fleet Access</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ph ph-check-circle text-primary-custom fs-4"></i>
                                        <span>24/7 Concierge Support</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleAuth(type) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (type === 'register') {
        loginForm.classList.add('d-none');
        loginForm.classList.remove('active');
        registerForm.classList.remove('d-none');
        registerForm.classList.add('active');
    } else {
        registerForm.classList.add('d-none');
        registerForm.classList.remove('active');
        loginForm.classList.remove('d-none');
        loginForm.classList.add('active');
    }
}
</script>

<style>
.bg-gradient-dark {
    background: linear-gradient(to right, rgba(15,15,15,0.9), rgba(15,15,15,0.4));
}
.form-control:focus {
    background-color: #252525 !important;
    border-color: var(--primary) !important;
    color: white !important;
    box-shadow: none;
}
</style>

<?php include 'templates/footer.php'; ?>