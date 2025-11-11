<?php 
$page_title = 'Trator - Dashboard';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('/login');
}
?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<div class="services_section layout_padding">
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Selamat datang di dashboard Trator Rental Mobil.</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sewa Mobil</h5>
                        <p class="card-text">Lihat dan sewa mobil yang tersedia</p>
                        <a href="/services" class="btn btn-primary">Lihat Mobil</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Riwayat Sewa</h5>
                        <p class="card-text">Lihat riwayat penyewaan Anda</p>
                        <a href="#" class="btn btn-secondary">Lihat Riwayat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>