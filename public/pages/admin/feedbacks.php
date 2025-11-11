<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

$adminAuth = new AdminAuth();
if (!$adminAuth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

require_once dirname(__DIR__, 3) . '/includes/functions.php';

// Handle delete feedback
if ($_POST['action'] ?? '' === 'delete') {
    $id = $_POST['id'];
    
    $stmt = $db->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: feedback.php?success=1');
    exit();
}

// Get all feedback
$stmt = $db->query("SELECT * FROM feedback ORDER BY created_at DESC");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback Management - Admin</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel - Feedback</span>
            <a href="logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-light p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard">📊 Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vehicles">🚗 Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="feedbacks">💬 Feedbacks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users">👥 Users</a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-10 p-4">
                <h2>Customer Feedback</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Feedback berhasil dihapus!</div>
                <?php endif; ?>
                
                <div class="row">
                    <?php foreach($feedbacks as $feedback): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($feedback['name']); ?></strong>
                                </div>
                                <div>
                                    <small class="text-muted me-2"><?php echo date('d M Y', strtotime($feedback['created_at'])); ?></small>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus feedback ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $feedback['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <p><?php echo htmlspecialchars($feedback['message']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>