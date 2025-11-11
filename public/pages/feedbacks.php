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

<!-- client section start -->
<div class="client_section layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mb-5">
                    <h1 class="client_taital">💬 Customer Feedbacks</h1>
                    <p class="lead text-muted">Testimoni dari pelanggan yang puas dengan layanan kami</p>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-primary btn-lg mb-4" data-toggle="modal" data-target="#addFeedbackModal">
                        ➕ Tambah Feedback
                    </button>
                <?php else: ?>
                    <a href="/auth" class="btn btn-primary btn-lg mb-4">
                        🔒 Login untuk Memberikan Feedback
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($feedback_chunks)): ?>
        <div id="custom_slider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach($feedback_chunks as $index => $chunk): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="client_section_2">
                        <div class="row">
                            <?php foreach($chunk as $feedback): ?>
                            <div class="col-md-6">
                                <div class="client_taital_box card shadow-sm border-0 mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <h5 class="moark_text mb-1"><?php echo htmlspecialchars($feedback['name']); ?></h5>
                                                <small class="text-muted"><?php echo date('d M Y', strtotime($feedback['created_at'])); ?></small>
                                            </div>
                                        </div>
                                        <p class="client_text">"<?php echo htmlspecialchars($feedback['message']); ?>"</p>
                                        <div class="text-warning">
                                            ⭐⭐⭐⭐⭐
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($feedback_chunks) > 1): ?>
            <a class="carousel-control-prev" href="#custom_slider" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#custom_slider" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="text-center">
            <div class="py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h3>Belum ada feedback dari pelanggan</h3>
                <p>Jadilah yang pertama memberikan feedback!</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- client section end -->

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