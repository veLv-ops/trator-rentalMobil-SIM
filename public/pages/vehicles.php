<?php 
$page_title = 'Trator - Vehicles';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

// Get all cars from database
$stmt = $db->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<!-- Vehicles Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="display-4 fw-bold mb-3">Our <span class="text-primary-custom">Premium Fleet</span></h1>
            <p class="text-main fs-5">Choose the perfect companion for your journey</p>
            
            <div class="d-flex justify-content-center gap-3 mt-4">
                <span class="badge bg-dark-surface text-success border border-success p-2 px-3 rounded-pill">
                    <i class="ph ph-check-circle me-1"></i> Available
                </span>
                <span class="badge bg-dark-surface text-warning border border-warning p-2 px-3 rounded-pill">
                    <i class="ph ph-car me-1"></i> Rented
                </span>
                <span class="badge bg-dark-surface text-danger border border-danger p-2 px-3 rounded-pill">
                    <i class="ph ph-wrench me-1"></i> Maintenance
                </span>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach($cars as $index => $car): 
                $delay = ($index % 3) * 100;
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                <div class="card h-100 bg-dark-surface border-0 shadow-lg overflow-hidden vehicle-card">
                    <div class="position-relative">
                        <img src="images/<?php echo $car['image'] ?? 'img-1.png'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" style="height: 250px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 p-3">
                            <?php 
                            $status_class = '';
                            $status_icon = '';
                            switch($car['status']) {
                                case 'available':
                                    $status_class = 'bg-success';
                                    $status_icon = 'check-circle';
                                    break;
                                case 'rented':
                                    $status_class = 'bg-warning text-dark';
                                    $status_icon = 'car';
                                    break;
                                case 'maintenance':
                                    $status_class = 'bg-danger';
                                    $status_icon = 'wrench';
                                    break;
                            }
                            ?>
                            <span class="badge <?php echo $status_class; ?> p-2 px-3 rounded-pill shadow-sm">
                                <i class="ph ph-<?php echo $status_icon; ?> me-1"></i> <?php echo ucfirst($car['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <h4 class="card-title fw-bold mb-1 text-main" style="color: var(--text-main);"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h4>
                        <p class="text-main mb-4" style="color: var(--text-main);"><?php echo $car['year']; ?> Series</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-3" style="background: rgba(255,255,255,0.05);">
                            <div>
                                <small class="text-main d-block">Daily Rate</small>
                                <span class="text-primary-custom fw-bold fs-5">Rp <?php echo number_format($car['price_per_day']); ?></span>
                            </div>
                            <div class="text-end">
                                <small class="text-main d-block">Transmission</small>
                                <span class="text-white fw-bold">Automatic</span>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <?php if ($car['status'] == 'available'): ?>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button class="btn btn-primary-custom w-100 justify-content-center" onclick="bookVehicle('<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>', '<?php echo $car['year']; ?>', '<?php echo number_format($car['price_per_day']); ?>', '<?php echo htmlspecialchars($_SESSION['username']); ?>')">
                                        Book Now <i class="ph ph-arrow-right"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="/trator/auth" class="btn btn-outline-custom w-100 justify-content-center">
                                        Login to Book
                                    </a>
                                <?php endif; ?>
                            <?php elseif ($car['status'] == 'rented'): ?>
                                <button class="btn btn-secondary w-100" disabled style="opacity: 0.5;">
                                    Currently Rented
                                </button>
                            <?php else: ?>
                                <button class="btn btn-danger w-100" disabled style="opacity: 0.5;">
                                    Under Maintenance
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($cars)): ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="ph ph-car fs-1 text-main"></i>
                </div>
                <h3 class="text-main">No vehicles available at the moment</h3>
                <p class="text-main">Please check back later or contact us for assistance.</p>
                <a href="/trator/contact" class="btn btn-primary-custom mt-3">
                    Contact Support
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.vehicle-card {
    transition: transform 0.3s ease, border-color 0.3s ease;
    border: 1px solid rgba(255,255,255,0.05) !important;
}

.vehicle-card:hover {
    transform: translateY(-10px);
    border-color: var(--primary) !important;
}

.vehicle-card .card-img-top {
    transition: transform 0.5s ease;
}

.vehicle-card:hover .card-img-top {
    transform: scale(1.05);
}
</style>

<script>
function bookVehicle(vehicle, year, price, username) {
    const message = `Halo trator, saya *${username}*%0A%0ASaya ingin booking kendaraan:%0A🚗 *${vehicle}*%0A📅 Tahun: ${year}%0A💰 Harga: Rp ${price}/hari%0A%0AMohon informasi ketersediaan dan prosedur booking. Terima kasih!`;
    const whatsappNumber = "6285704866825";
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;
    
    window.open(whatsappUrl, '_blank');
}
</script>

<?php include 'templates/footer.php'; ?>