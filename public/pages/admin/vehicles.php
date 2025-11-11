<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

$adminAuth = new AdminAuth();
if (!$adminAuth->isLoggedIn()) {
    header('Location: /trator/admin/login');
    exit();
}

require_once dirname(__DIR__, 3) . '/includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $brand = sanitize($_POST['brand']);
        $model = sanitize($_POST['model']);
        $year = (int)$_POST['year'];
        $price = (float)$_POST['price_per_day'];
        
        // Handle file upload
        $image = 'default-car.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = dirname(__DIR__, 2) . '/images/';
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = uniqid() . '.' . $file_extension;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image)) {
                // File uploaded successfully
            } else {
                $image = 'default-car.jpg';
            }
        }
        
        $stmt = $db->prepare("INSERT INTO cars (brand, model, year, price_per_day, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$brand, $model, $year, $price, $image]);
        
        header('Location: /trator/admin/vehicles?success=added');
        exit();
    }
    
    if ($action == 'edit') {
        $id = (int)$_POST['id'];
        $brand = sanitize($_POST['brand']);
        $model = sanitize($_POST['model']);
        $year = (int)$_POST['year'];
        $price = (float)$_POST['price_per_day'];
        $status = sanitize($_POST['status']);
        
        // Get current car data
        $stmt = $db->prepare("SELECT image FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        $current_car = $stmt->fetch();
        $image = $current_car['image'];
        
        // Handle file upload if new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = dirname(__DIR__, 2) . '/images/';
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_image = uniqid() . '.' . $file_extension;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_image)) {
                // Delete old image if it's not default
                if ($image != 'default-car.jpg' && file_exists($upload_dir . $image)) {
                    unlink($upload_dir . $image);
                }
                $image = $new_image;
            }
        }
        
        $stmt = $db->prepare("UPDATE cars SET brand = ?, model = ?, year = ?, price_per_day = ?, status = ?, image = ? WHERE id = ?");
        $stmt->execute([$brand, $model, $year, $price, $status, $image, $id]);
        
        header('Location: /trator/admin/vehicles?success=updated');
        exit();
    }
    
    if ($action == 'delete') {
        $id = (int)$_POST['id'];
        
        // Get image name to delete file
        $stmt = $db->prepare("SELECT image FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        $car = $stmt->fetch();
        
        if ($car && $car['image'] != 'default-car.jpg') {
            $image_path = dirname(__DIR__, 2) . '/images/' . $car['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: /trator/admin/vehicles?success=deleted');
        exit();
    }
}

// Get all cars
$stmt = $db->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vehicles Management - Admin</title>
    <link rel="stylesheet" href="/trator/css/bootstrap.min.css">
    <style>
        .fa { font-family: FontAwesome; }
        .fa-plus:before { content: "+"; }
        .fa-edit:before { content: "✎"; }
        .fa-trash:before { content: "🗑"; }
        .fa-tachometer-alt:before { content: "📊"; }
        .fa-car:before { content: "🚗"; }
        .fa-comments:before { content: "💬"; }
        .fa-users:before { content: "👥"; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel - Vehicles</span>
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
                        <a class="nav-link active" href="/trator/admin/vehicles">🚗 Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/feedbacks">💬 Feedbacks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/users">👥 Users</a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-10 p-4">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        switch($_GET['success']) {
                            case 'added': echo 'Vehicle berhasil ditambahkan!'; break;
                            case 'updated': echo 'Vehicle berhasil diupdate!'; break;
                            case 'deleted': echo 'Vehicle berhasil dihapus!'; break;
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Vehicles Management</h2>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addCarModal">
                        ➕ Add Vehicle
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Price/Day</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cars as $car): ?>
                            <tr>
                                <td>
                                    <img src="/trator/images/<?php echo $car['image']; ?>" alt="Car" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td><?php echo $car['brand']; ?></td>
                                <td><?php echo $car['model']; ?></td>
                                <td><?php echo $car['year']; ?></td>
                                <td>Rp <?php echo number_format($car['price_per_day']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $car['status'] == 'available' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($car['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#editCarModal" 
                                            data-id="<?php echo $car['id']; ?>"
                                            data-brand="<?php echo htmlspecialchars($car['brand']); ?>"
                                            data-model="<?php echo htmlspecialchars($car['model']); ?>"
                                            data-year="<?php echo $car['year']; ?>"
                                            data-price="<?php echo $car['price_per_day']; ?>"
                                            data-status="<?php echo $car['status']; ?>"
                                            data-image="<?php echo $car['image']; ?>">
                                        ✏️
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus vehicle ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
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

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addCarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" class="form-control" name="brand" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" class="form-control" name="model" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" class="form-control" name="year" min="1990" max="2030" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Price per Day (Rp)</label>
                            <input type="number" class="form-control" name="price_per_day" min="0" step="1000" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Vehicle Photo</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Optional. Supported: JPG, PNG, GIF</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editCarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="form-group">
                            <label>Current Photo</label><br>
                            <img id="current_image" src="" alt="Current" style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                        </div>
                        
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" class="form-control" name="brand" id="edit_brand" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" class="form-control" name="model" id="edit_model" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" class="form-control" name="year" id="edit_year" min="1990" max="2030" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Price per Day (Rp)</label>
                            <input type="number" class="form-control" name="price_per_day" id="edit_price" min="0" step="1000" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>New Photo (Optional)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current photo</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/trator/js/jquery.min.js"></script>
    <script src="/trator/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#editCarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var brand = button.data('brand');
            var model = button.data('model');
            var year = button.data('year');
            var price = button.data('price');
            var status = button.data('status');
            var image = button.data('image');
            
            var modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_brand').val(brand);
            modal.find('#edit_model').val(model);
            modal.find('#edit_year').val(year);
            modal.find('#edit_price').val(price);
            modal.find('#edit_status').val(status);
            modal.find('#current_image').attr('src', '/trator/images/' + image);
        });
    });
    </script>
</body>
</html>