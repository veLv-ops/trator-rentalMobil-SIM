<?php $page_title = 'Trator - Auth'; ?>
<?php $page_style = 'css/login.css'; ?>
<?php include 'templates/header.php'; ?>

<div class="wrapper">
    <!-- LEFT: AUTH CONTAINER -->
    <div class="auth-container">
        <button class="back-btn" onclick="window.location.href='/home'">
            ← Back
        </button>
        <h2 class="title">Nongki</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
                <?php
                switch ($_GET['error']) {
                    case 'invalid_credentials':
                        echo 'Username atau password salah!';
                        break;
                    case 'registration_failed':
                        echo 'Registrasi gagal! Username sudah digunakan.';
                        break;
                    case 'password_mismatch':
                        echo 'Password dan konfirmasi password tidak sama!';
                        break;
                    default:
                        echo 'Terjadi kesalahan!';
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form id="loginForm" class="form active" action="auth_process" method="POST">
            <input type="hidden" name="action" value="login">
            <label for="loginUsername">Username</label>
            <input type="text" name="username" id="loginUsername" placeholder="Enter your username" required>

            <label for="loginPassword">Password</label>
            <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>

            <button type="submit" class="main-btn">Login →</button>
        </form>

        <!-- Register Form -->
        <form id="registerForm" class="form" action="auth_process" method="POST">
            <input type="hidden" name="action" value="register">
            <label for="regUsername">Username</label>
            <input type="text" name="username" id="regUsername" placeholder="Create a username" required>

            <label for="regPassword">Password</label>
            <input type="password" name="password" id="regPassword" placeholder="Create a password" required>

            <label for="regConfirm">Confirm Password</label>
            <input type="password" name="confirm_password" id="regConfirm" placeholder="Confirm your password" required>

            <button type="submit" class="main-btn">Register →</button>
        </form>

        <!-- Switch Buttons -->
        <div class="switch">
            <button id="loginSwitch" class="switch-btn active">login</button>
            <button id="registerSwitch" class="switch-btn">register</button>
        </div>
    </div>

    <!-- RIGHT: IMAGE CAROUSEL -->
    <div class="carousel-container">
        <div class="carousel">
            <div class="slide active">
                <img src="images/oldCar.jpeg" alt="car 1">
                <div class="caption">
                    <h3>Mobil-Mobil Mewah Pilihan Anda</h3>
                    <p>Login/register dahulu untuk menyewa mobil impian Anda</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/oldCar2.jpeg" alt="car 2">
                <div class="caption">
                    <h3>Perjalanan Nyaman dan Bergaya</h3>
                    <p>Kemewahan dan performa dalam satu genggaman</p>
                </div>
            </div>
            <div class="slide">
                <img src="images/oldCar3.jpeg" alt="car 3">
                <div class="caption">
                    <h3>Temukan Pengalaman Berkendara Baru</h3>
                    <p>Dapatkan mobil terbaik untuk setiap momen Anda</p>
                </div>
            </div>
        </div>

        <div class="carousel-nav">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>