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
<html>
<head>
    <title>Users Management - Admin</title>
    <link rel="stylesheet" href="/trator/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel - Users</span>
            <a href="/trator/admin/logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-light p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/dashboard">📊 Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/vehicles">🚗 Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/feedbacks">💬 Feedbacks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/trator/admin/users">👥 Users</a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-10 p-4">
                <h2>Users Management</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <?php if ($_GET['success'] == 1): ?>
                        <div class="alert alert-success">Role berhasil diubah!</div>
                    <?php elseif ($_GET['success'] == 2): ?>
                        <div class="alert alert-success">User berhasil diupdate!</div>
                    <?php elseif ($_GET['success'] == 3): ?>
                        <div class="alert alert-success">User berhasil dihapus!</div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#editUserModal" 
                                            data-id="<?php echo $user['id']; ?>"
                                            data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                            data-role="<?php echo $user['role']; ?>">
                                        ✏️
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role" id="edit_role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/trator/js/jquery.min.js"></script>
    <script src="/trator/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#editUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var username = button.data('username');
            var role = button.data('role');
            
            var modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_username').val(username);
            modal.find('#edit_role').val(role);
        });
    });
    </script>
</body>
</html>