<?php 
$page_title = 'Trator - Contact Us';

// Start session first
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 2) . '/includes/functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in when submitting
    if (!isset($_SESSION['user_id'])) {
        redirect('/auth');
    }
    
    $name = $_SESSION['username'];
    $message = sanitize($_POST['message']);
    
    if (!empty($message)) {
        // Save to database
        $stmt = $db->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, '', $message]);
        
        // Create WhatsApp message
        $whatsapp_message = "Halo trator, saya *{$name}*%0A%0A{$message}";
        $whatsapp_number = "6285704866825"; // Ganti dengan nomor WhatsApp admin
        $whatsapp_url = "https://wa.me/{$whatsapp_number}?text={$whatsapp_message}";
        
        // Set flag for JavaScript to open WhatsApp
        $whatsapp_redirect = $whatsapp_url;
        $success = "Pesan berhasil dikirim! WhatsApp akan terbuka di tab baru.";
    } else {
        $error = "Pesan harus diisi.";
    }
}
?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<!-- feedback section start -->
<div class="feedback_section layout_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h1 class="feedback_taital">📞 Hubungi Kami</h1>
                    <p class="lead text-muted">Sampaikan pertanyaan, saran, atau keluhan Anda kepada kami</p>
                </div>
                
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" class="needs-validation" novalidate>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">👤 Nama Anda</label>
                                    <input type="text" class="form-control form-control-lg" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="background-color: #f8f9fa;">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">✉️ Pesan Anda</label>
                                    <textarea class="form-control form-control-lg" placeholder="Tulis pesan, pertanyaan, atau saran Anda di sini..." rows="6" name="message" required style="resize: vertical;"></textarea>
                                    <div class="invalid-feedback">
                                        Pesan harus diisi.
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fab fa-whatsapp me-2"></i>Kirim ke WhatsApp
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-lock fa-3x text-muted"></i>
                                </div>
                                <h4 class="text-muted mb-3">Login Diperlukan</h4>
                                <p class="text-muted mb-4">Anda harus login terlebih dahulu untuk mengirim pesan ke WhatsApp admin kami.</p>
                                <a href="/auth" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-center mt-4 pt-4 border-top">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        <small class="text-muted">+62 857-0486-6825</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fab fa-whatsapp text-success me-2"></i>
                                        <small class="text-muted">WhatsApp Ready</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-clock text-info me-2"></i>
                                        <small class="text-muted">24/7 Support</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- feedback section end -->

<?php if (isset($whatsapp_redirect)): ?>
<script>
// Open WhatsApp in new tab
window.open('<?php echo $whatsapp_redirect; ?>', '_blank');
</script>
<?php endif; ?>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

<?php include 'templates/footer.php'; ?>