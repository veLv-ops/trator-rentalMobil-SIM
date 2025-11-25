<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

$adminAuth = new AdminAuth();
if (!$adminAuth->isLoggedIn()) {
    header('Location: /trator/admin/login');
    exit();
}

require_once dirname(__DIR__, 3) . '/includes/functions.php';

// Handle user actions
if ($_POST['action'] ?? '' === 'update_role') {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    
    $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$new_role, $user_id]);
    
    header('Location: /trator/admin/users?success=1');
    exit();
}

if ($_POST['action'] ?? '' === 'edit') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    $stmt = $db->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $role, $id]);
    
    header('Location: /trator/admin/users?success=2');
    exit();
}

if ($_POST['action'] ?? '' === 'delete') {
    $id = $_POST['id'];
    
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: /trator/admin/users?success=3');
    exit();
}

// Get all users
$stmt = $db->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users Management - Admin</title>
    
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
        .table-card {
            background-color: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
            color: var(--text-light);
        }
        .table th {
            background-color: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 500;
            padding: 16px;
        }
        .table td {
            border-bottom: 1px solid var(--border-color);
            padding: 16px;
            vertical-align: middle;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .modal-content {
            background-color: var(--dark-surface);
            border: 1px solid var(--border-color);
        }
        .modal-header, .modal-footer {
            border-color: var(--border-color);
        }
        .form-control, .form-select {
            background-color: var(--dark);
            border-color: var(--border-color);
            color: var(--text-light);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--dark);
            border-color: var(--primary);
            color: var(--text-light);
            box-shadow: 0 0 0 0.25rem rgba(212, 248, 0, 0.25);
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
                        <a class="nav-link" href="/trator/admin/feedbacks">
                            <i class="ph ph-chat-text fs-5"></i> Feedbacks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/trator/admin/users">
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
                    <h1 class="h2 text-white">Users Management</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 text-success border-success border-opacity-25" role="alert">
                        <?php 
                        if ($_GET['success'] == 1) echo 'Role successfully updated!';
                        elseif ($_GET['success'] == 2) echo 'User successfully updated!';
                        elseif ($_GET['success'] == 3) echo 'User successfully deleted!';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-dark border border-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="ph ph-user text-muted"></i>
                                            </div>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($user['role'] == 'admin'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-custom bg-opacity-10 text-primary-custom px-3 py-2 rounded-pill">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                                    data-id="<?php echo $user['id']; ?>"
                                                    data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                    data-role="<?php echo $user['role']; ?>">
                                                <i class="ph ph-pencil"></i>
                                            </button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Username</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Role</label>
                            <select class="form-select" name="role" id="edit_role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editUserModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                
                const id = button.getAttribute('data-id');
                const username = button.getAttribute('data-username');
                const role = button.getAttribute('data-role');
                
                editModal.querySelector('#edit_id').value = id;
                editModal.querySelector('#edit_username').value = username;
                editModal.querySelector('#edit_role').value = role;
            });
        }
    });
    </script>
</body>
</html>