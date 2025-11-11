<?php 
$page_title = 'Trator - Vehicles';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

// Get all cars from database
$stmt = $db->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<!-- gallery section start -->
<div class="gallery_section layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mb-5">
                    <h1 class="gallery_taital">🚗 Our Vehicle Collection</h1>
                    <p class="lead text-muted">Pilih kendaraan terbaik untuk perjalanan Anda</p>
                    <div class="d-inline-flex align-items-center mt-3">
                        <span class="badge badge-success mr-2">✅ Available</span>
                        <span class="badge badge-warning mr-2">🚗 Rented</span>
                        <span class="badge badge-danger">🔧 Maintenance</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="gallery_section_2">
            <div class="row">
                <?php foreach($cars as $car): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 vehicle-card">
                        <div class="position-relative">
                            <img src="images/<?php echo $car['image'] ?? 'img-1.png'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" style="height: 250px; object-fit: cover;">
                            <div style="position: absolute; top: 10px; right: 10px;">
                                <?php 
                                $status_class = '';
                                $status_icon = '';
                                switch($car['status']) {
                                    case 'available':
                                        $status_class = 'bg-success';
                                        $status_icon = '✅';
                                        break;
                                    case 'rented':
                                        $status_class = 'bg-warning';
                                        $status_icon = '🚗';
                                        break;
                                    case 'maintenance':
                                        $status_class = 'bg-danger';
                                        $status_icon = '🔧';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $status_class; ?> px-3 py-2">
                                    <?php echo $status_icon . ' ' . ucfirst($car['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary font-weight-bold"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">📅 Tahun</span>
                                    <span class="font-weight-bold"><?php echo $car['year']; ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">💰 Harga</span>
                                    <span class="font-weight-bold text-success">Rp <?php echo number_format($car['price_per_day']); ?>/hari</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <?php if ($car['status'] == 'available'): ?>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button class="btn btn-primary btn-lg btn-block" onclick="bookVehicle('<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>', '<?php echo $car['year']; ?>', '<?php echo number_format($car['price_per_day']); ?>', '<?php echo htmlspecialchars($_SESSION['username']); ?>')">
                                            📱 Book Now
                                        </button>
                                    <?php else: ?>
                                        <a href="/trator/auth" class="btn btn-outline-primary btn-lg btn-block">
                                            🔐 Login to Book
                                        </a>
                                    <?php endif; ?>
                                <?php elseif ($car['status'] == 'rented'): ?>
                                    <button class="btn btn-secondary btn-lg btn-block" disabled>
                                        ⏰ Currently Rented
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-danger btn-lg btn-block" disabled>
                                        🔧 Under Maintenance
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if (empty($cars)): ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <div style="font-size: 3rem; color: #6c757d;">🚗</div>
                </div>
                <h3 class="text-muted">Tidak ada kendaraan di sistem</h3>
                <p class="text-muted">Silakan hubungi kami untuk informasi lebih lanjut.</p>
                <a href="/trator/contact" class="btn btn-primary">
                    📞 Hubungi Kami
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- gallery section end -->

<style>
.vehicle-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.vehicle-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.card-img-top {
    border-radius: 15px 15px 0 0;
    transition: transform 0.3s ease;
}

.vehicle-card:hover .card-img-top {
    transform: scale(1.05);
}

.badge {
    font-size: 0.85em;
    border-radius: 20px;
}

.btn {
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.card-title {
    font-size: 1.25rem;
}

@media (max-width: 768px) {
    .vehicle-card {
        margin-bottom: 1.5rem;
    }
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