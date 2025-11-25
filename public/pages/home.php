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
                    Drive the <br>
                    <span>Extraordinary.</span>
                </h1>
                <p class="hero-text">
                    Experience the thrill of premium automotive engineering. 
                    From luxury sedans to high-performance sports cars, we have the perfect ride for your journey.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= url('vehicles') ?>" class="btn btn-primary-custom">
                        Explore Fleet <i class="ph ph-arrow-right"></i>
                    </a>
                    <a href="<?= url('contact') ?>" class="btn btn-outline-custom">
                        Contact Us
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-image-wrapper">
                    <!-- Placeholder for a high-quality car image. Ensure 'images/hero-car.png' exists or use a placeholder -->
                    <img src="images/hero-car.png" alt="Premium Car" class="image-banner" style="width: 500px; height: auto;">
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
                    <h3 class="feature-title">Instant Booking</h3>
                    <p class="feature-text">
                        Seamless digital booking experience. Get verified and on the road in minutes, not hours.
                    </p>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <i class="ph ph-shield-check feature-icon"></i>
                    <h3 class="feature-title">Premium Insurance</h3>
                    <p class="feature-text">
                        Drive with peace of mind. Comprehensive insurance coverage included with every rental.
                    </p>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <i class="ph ph-headset feature-icon"></i>
                    <h3 class="feature-title">24/7 Support</h3>
                    <p class="feature-text">
                        Our dedicated concierge team is available around the clock to assist you with any needs.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?>