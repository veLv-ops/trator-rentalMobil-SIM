<?php $page_title = 'Trator - About'; ?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<!-- About Section -->
<section class="section-padding mt-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="position-relative">
                    <div class="hero-bg-glow" style="width: 300px; height: 300px; left: -50px; top: -50px;"></div>
                    <img src="images/about-img.png" class="img-fluid rounded-4 shadow-lg position-relative z-1" style="width: 100%;" alt="About Trator">
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="display-4 fw-bold mb-4">Standar Baru<span class="text-primary-custom">Mobilitas</span></h2>
                <p class="text-main mb-3">
                    Di Trator, kami percaya bahwa setiap perjalanan harus menjadi sebuah pengalaman. Kami bukan sekadar perusahaan rental mobil; kami adalah partner Anda dalam menjelajahi dunia dengan gaya, kenyamanan, dan performa.
                </p>
                <p class="text-main mb-3">
                    Didirikan dengan semangat terhadap keunggulan otomotif, armada kami terdiri dari kendaraan-kendaraan terbaik yang dirawat secara teliti demi memastikan keamanan dan kepuasan Anda. Baik Anda membutuhkan sedan elegan untuk perjalanan bisnis ataupun SUV tangguh untuk petualangan, kami memiliki kunci yang tepat menunggu untuk Anda.
                </p>
                <div class="row g-4 mb-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-dark-surface p-3 rounded-circle text-primary-custom">
                                <i class="ph ph-trophy fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Award Winning</h5>
                                <small class="text-main">Best Service 2023</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-dark-surface p-3 rounded-circle text-primary-custom">
                                <i class="ph ph-users fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Happy Clients</h5>
                                <small class="text-main">10k+ Trusted Users</small>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="<?= url('vehicles') ?>" class="btn btn-primary-custom">
                    Temukan Armada Kami
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?>