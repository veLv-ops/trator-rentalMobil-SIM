<?php 
$page_title = 'Trator - Customer Feedbacks';

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
        
        $success = "Feedback berhasil ditambahkan!";
    } else {
        $error = "Pesan harus diisi.";
    }
}

// Get feedback from database
$stmt = $db->query("SELECT * FROM feedback ORDER BY created_at DESC LIMIT 6");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group feedbacks into chunks of 2 for carousel slides
$feedback_chunks = array_chunk($feedbacks, 2);
?>
<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<!-- Feedbacks Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="display-4 fw-bold mb-3">Client <span class="text-primary-custom">Stories</span></h1>
            <p class="text-main fs-5">Hear what our satisfied customers have to say</p>
        </div>
        
        <div class="row mb-5 justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-8 text-center">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success bg-success text-white border-0"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger bg-danger text-white border-0"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addFeedbackModal">
                        <i class="ph ph-plus me-2"></i> Share Your Experience
                    </button>
                <?php else: ?>
                    <a href="/trator/auth" class="btn btn-outline-custom">
                        <i class="ph ph-lock-key me-2"></i> Login to Review
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($feedback_chunks)): ?>
        <!-- Swiper -->
        <div class="swiper feedbackSwiper" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper pb-5">
                <?php foreach($feedbacks as $feedback): ?>
                <div class="swiper-slide">
                    <div class="card bg-dark-surface border-0 shadow-lg h-100 p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary-custom text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fw-bold fs-4" style="color: var(--text-main);"><?php echo strtoupper(substr($feedback['name'], 0, 1)); ?></span>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: var(--text-main);"><?php echo htmlspecialchars($feedback['name']); ?></h5>
                                <small class="text-main" style="color: #777;"><?php echo date('d M Y', strtotime($feedback['created_at'])); ?></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <i class="ph ph-quotes text-primary-custom fs-1 opacity-25"></i>
                        </div>
                        <p class="text-main mb-0 fst-italic" style="color: var(--text-main);">"<?php echo htmlspecialchars($feedback['message']); ?>"</p>
                        <div class="mt-4 text-warning">
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="ph ph-chat-teardrop-text fs-1 text-main"></i>
            </div>
            <h3 class="text-main">No feedbacks yet</h3>
            <p class="text-main">Be the first to share your experience!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper(".feedbackSwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
});
</script>

<!-- Add Feedback Modal -->
<?php if (isset($_SESSION['user_id'])): ?>
<div class="modal fade" id="addFeedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">💬 Tambah Feedback</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>👤 Nama Anda</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="background-color: #f8f9fa;">
                    </div>
                    
                    <div class="form-group">
                        <label>✉️ Pesan Feedback</label>
                        <textarea class="form-control" placeholder="Tulis feedback Anda tentang layanan kami..." rows="4" name="message" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>