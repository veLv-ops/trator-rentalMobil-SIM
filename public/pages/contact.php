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
        $stmt = $db->prepare("INSERT INTO feedback (name, message) VALUES (?, ?)");
        $stmt->execute([$name, $message]);
        
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

<!-- Contact Section -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="display-4 fw-bold mb-3">Get in <span class="text-primary-custom">Touch</span></h1>
                    <p class="text-main  fs-5">Have questions or need assistance? We're here to help.</p>
                </div>
                
                <div class="card bg-dark-surface border-0 shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-5">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0" role="alert">
                                <i class="ph ph-check-circle me-2"></i><?php echo $success; ?>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0" role="alert">
                                <i class="ph ph-warning-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" class="needs-validation" novalidate>
                                <div class="mb-4">
                                    <label class="form-label text-main ">Your Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-main "><i class="ph ph-user"></i></span>
                                        <input type="text" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label text-main ">Message</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-main "><i class="ph ph-chat-text"></i></span>
                                        <textarea class="form-control bg-dark border-secondary text-white" placeholder="How can we help you today?" rows="6" name="message" required style="resize: none;"></textarea>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter your message.
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary-custom justify-content-center">
                                        Send via WhatsApp <i class="ph ph-whatsapp-logo fs-5"></i>
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <div class="bg-dark p-4 rounded-circle d-inline-block">
                                        <i class="ph ph-lock-key fs-1 text-main "></i>
                                    </div>
                                </div>
                                <h4 class="text-white mb-3">Login Required</h4>
                                <p class="text-main  mb-4" style="color: var(--text-main);">Please login to send messages to our support team.</p>
                                <a href="/trator/auth" class="btn btn-primary-custom">
                                    Login Now
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row mt-5 pt-4 border-top border-secondary">
                            <div class="col-md-4 mb-3 mb-md-0 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ph ph-phone text-primary-custom fs-4 mb-2"></i>
                                    <small class="text-main  " style="color: var(--text-main);">+62 857-0486-6825</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ph ph-whatsapp-logo text-success fs-4 mb-2"></i>
                                    <small class="text-main  " style="color: var(--text-main);">WhatsApp Ready</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ph ph-clock text-info fs-4 mb-2"></i>
                                    <small class="text-main  " style="color: var(--text-main);">24/7 Support</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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