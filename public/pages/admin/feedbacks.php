<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

$adminAuth = new AdminAuth();
if (!$adminAuth->isLoggedIn()) {
    header('Location: /trator/admin/login');
    exit();
}

require_once dirname(__DIR__, 3) . '/includes/functions.php';

// Handle delete feedback
if ($_POST['action'] ?? '' === 'delete') {
    $id = $_POST['id'];
    
    $stmt = $db->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: /trator/admin/feedbacks?success=1');
    exit();
}

// Get all feedback
$stmt = $db->query("SELECT * FROM feedback ORDER BY created_at DESC");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback Management - Admin</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/trator/css/style.css">
    
    <style>
        body {
            background-color: var(--dark);
        }
        .sidebar {
            background-color: var(--dark-surface);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
        }
        .nav-link {
            color: var(--text-muted);
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(212, 248, 0, 0.1);
            color: var(--primary);
        }
        .feedback-card {
            background-color: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: transform 0.3s ease;
        }
        .feedback-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-4 collapse d-md-block" id="sidebarMenu">
                <a href="/trator/home" class="d-flex align-items-center mb-5 text-decoration-none">
                    <span class="fs-4 fw-bold text-white">TRATOR<span class="text-primary-custom">.</span></span>
                </a>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/dashboard">
                            <i class="ph ph-squares-four fs-5"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/vehicles">
                            <i class="ph ph-car fs-5"></i> Vehicles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/trator/admin/feedbacks">
                            <i class="ph ph-chat-text fs-5"></i> Feedbacks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/users">
                            <i class="ph ph-users fs-5"></i> Users
                        </a>
                    </li>
                </ul>
                
                <div class="mt-auto pt-5">
                    <a href="/trator/admin/logout" class="nav-link text-danger">
                        <i class="ph ph-sign-out fs-5"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary">
                    <h1 class="h2 text-white">Customer Feedback</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 text-success border-success border-opacity-25" role="alert">
                        Feedback successfully deleted!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row g-4">
                    <?php foreach($feedbacks as $feedback): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="feedback-card h-100 p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary-custom rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--dark);">
                                        <span class="fw-bold"><?php echo strtoupper(substr($feedback['name'], 0, 1)); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-white mb-0"><?php echo htmlspecialchars($feedback['name']); ?></h6>
                                        <small class="text-muted"><?php echo date('d M Y', strtotime($feedback['created_at'])); ?></small>
                                    </div>
                                </div>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $feedback['id']; ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0">
                                        <i class="ph ph-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="mb-3 flex-grow-1">
                                <p class="text-light opacity-75 mb-0">"<?php echo htmlspecialchars($feedback['message']); ?>"</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>