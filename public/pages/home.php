<?php $page_title = 'Trator - Home'; ?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-bg-glow"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title">
                    Mengendarai sesuatu yang<br>
                    <span>Spesial.</span>
                </h1>
                <p class="hero-text">
                    Rasakan sensasi rekayasa otomotif premium. Dari sedan mewah hingga mobil sport berperforma tinggi, kami menyediakan kendaraan yang tepat untuk perjalanan Anda.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= url('vehicles') ?>" class="btn btn-primary-custom">
                        Jelajahi armada kami <i class="ph ph-arrow-right"></i>
                    </a>
                    <a href="<?= url('contact') ?>" class="btn btn-outline-custom">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-image-wrapper">
                    <!-- Placeholder for a high-quality car image. Ensure 'images/hero-car.png' exists or use a placeholder -->
                    <img src="images/hero-car.png" alt="Premium Car" class="image-banner" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding bg-dark-surface">
    <div class="container">
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <i class="ph ph-lightning feature-icon"></i>
                    <h3 class="feature-title">Booking Instan</h3>
                    <p class="feature-text">
                        Pengalaman pemesanan digital yang mulus. Verifikasi cepat dan Anda siap berkendara dalam hitungan menit, bukan jam.
                    </p>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <i class="ph ph-shield-check feature-icon"></i>
                    <h3 class="feature-title">Asuransi Premium</h3>
                    <p class="feature-text">
                        Berkendara dengan tenang. Setiap penyewaan sudah termasuk perlindungan asuransi lengkap.
                    </p>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <i class="ph ph-headset feature-icon"></i>
                    <h3 class="feature-title">24/7 Support</h3>
                    <p class="feature-text">
                        Tim concierge kami siap membantu Anda kapan pun, selama 24 jam penuh.                    
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?>