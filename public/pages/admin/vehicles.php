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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vehicles Management - Admin</title>
    
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
                        <a class="nav-link active" href="/trator/admin/vehicles">
                            <i class="ph ph-car fs-5"></i> Vehicles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/feedbacks">
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
                    <h1 class="h2 text-white">Vehicles Management</h1>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCarModal">
                        <i class="ph ph-plus me-2"></i> Add Vehicle
                    </button>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 text-success border-success border-opacity-25" role="alert">
                        <?php 
                        switch($_GET['success']) {
                            case 'added': echo 'Vehicle successfully added!'; break;
                            case 'updated': echo 'Vehicle successfully updated!'; break;
                            case 'deleted': echo 'Vehicle successfully deleted!'; break;
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                        <img src="/trator/images/<?php echo $car['image']; ?>" alt="Car" style="width: 60px; height: 40px; object-fit: cover; border-radius: 8px;">
                                    </td>
                                    <td><?php echo $car['brand']; ?></td>
                                    <td><?php echo $car['model']; ?></td>
                                    <td><?php echo $car['year']; ?></td>
                                    <td>Rp <?php echo number_format($car['price_per_day']); ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = match($car['status']) {
                                            'available' => 'bg-success',
                                            'rented' => 'bg-warning',
                                            'maintenance' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?> bg-opacity-10 text-<?php echo str_replace('bg-', '', $statusClass); ?> px-3 py-2 rounded-pill">
                                            <?php echo ucfirst($car['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editCarModal" 
                                                    data-id="<?php echo $car['id']; ?>"
                                                    data-brand="<?php echo htmlspecialchars($car['brand']); ?>"
                                                    data-model="<?php echo htmlspecialchars($car['model']); ?>"
                                                    data-year="<?php echo $car['year']; ?>"
                                                    data-price="<?php echo $car['price_per_day']; ?>"
                                                    data-status="<?php echo $car['status']; ?>"
                                                    data-image="<?php echo $car['image']; ?>">
                                                <i class="ph ph-pencil"></i>
                                            </button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
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

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addCarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Add New Vehicle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label text-main" >Brand</label>
                            <input type="text" class="form-control" name="brand" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-main">Model</label>
                            <input type="text" class="form-control" name="model" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-main">Year</label>
                            <input type="number" class="form-control" name="year" min="1990" max="2030" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-main">Price per Day (Rp)</label>
                            <input type="number" class="form-control" name="price_per_day" min="0" step="1000" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-main">Vehicle Photo</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-main">Optional. Supported: JPG, PNG, GIF</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Add Vehicle</button>
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
                    <h5 class="modal-title text-white">Edit Vehicle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="mb-3 text-center">
                            <label class="form-label text-muted d-block">Current Photo</label>
                            <img id="current_image" src="" alt="Current" style="width: 150px; height: 100px; object-fit: cover; border-radius: 8px;">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Brand</label>
                            <input type="text" class="form-control" name="brand" id="edit_brand" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Model</label>
                            <input type="text" class="form-control" name="model" id="edit_model" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Year</label>
                            <input type="number" class="form-control" name="year" id="edit_year" min="1990" max="2030" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Price per Day (Rp)</label>
                            <input type="number" class="form-control" name="price_per_day" id="edit_price" min="0" step="1000" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">New Photo (Optional)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current photo</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Update Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editCarModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                
                const id = button.getAttribute('data-id');
                const brand = button.getAttribute('data-brand');
                const model = button.getAttribute('data-model');
                const year = button.getAttribute('data-year');
                const price = button.getAttribute('data-price');
                const status = button.getAttribute('data-status');
                const image = button.getAttribute('data-image');
                
                editModal.querySelector('#edit_id').value = id;
                editModal.querySelector('#edit_brand').value = brand;
                editModal.querySelector('#edit_model').value = model;
                editModal.querySelector('#edit_year').value = year;
                editModal.querySelector('#edit_price').value = price;
                editModal.querySelector('#edit_status').value = status;
                editModal.querySelector('#current_image').src = '/trator/images/' + image;
            });
        }
    });
    </script>
</body>
</html>