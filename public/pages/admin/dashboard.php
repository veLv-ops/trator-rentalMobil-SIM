<?php
session_start();
require_once dirname(__DIR__, 3) . '/includes/admin_auth.php';

$adminAuth = new AdminAuth();
if (!$adminAuth->isLoggedIn()) {
    header('Location: /trator/admin/login');
    exit();
}

require_once dirname(__DIR__, 3) . '/includes/functions.php';

// Get statistics
$stmt = $db->query("SELECT COUNT(*) as total FROM cars");
$total_cars = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM feedback");
$total_feedback = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM users");
$total_users = $stmt->fetch()['total'];

// Get vehicle status data for pie chart
$stmt = $db->query("SELECT status, COUNT(*) as count FROM cars GROUP BY status");
$vehicle_status = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get monthly feedback data for line chart
$stmt = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM feedback GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY month DESC LIMIT 6");
$monthly_feedback = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

// Get vehicle brands for bar chart
$stmt = $db->query("SELECT brand, COUNT(*) as count FROM cars GROUP BY brand ORDER BY count DESC LIMIT 5");
$vehicle_brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Trator</title>
    <link rel="stylesheet" href="/trator/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Panel - Trator</span>
            <div>
                <span class="text-light me-3">Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="/trator/admin/logout" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-light p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/trator/admin/dashboard">📊 Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trator/admin/vehicles">🚗 Vehicles</a>
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
                <h2>Dashboard</h2>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>🚗 Total Vehicles</h5>
                                        <h2><?php echo $total_cars; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-car fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>👥 Total Users</h5>
                                        <h2><?php echo $total_users; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>💬 Total Feedback</h5>
                                        <h2><?php echo $total_feedback; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-comments fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">📊 Vehicle Status Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="vehicleStatusChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">📈 Monthly Feedback Trend</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="feedbackTrendChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">🏆 Top Vehicle Brands</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="vehicleBrandsChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">📋 Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted">Available Vehicles</small>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: <?php echo $total_cars > 0 ? (array_sum(array_column(array_filter($vehicle_status, function($v) { return $v['status'] == 'available'; }), 'count')) / $total_cars * 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">User Engagement</small>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: <?php echo $total_users > 0 ? min(($total_feedback / $total_users * 100), 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <small class="text-success">📈 System Health: Excellent</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Vehicle Status Pie Chart
        const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
        new Chart(vehicleStatusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($vehicle_status, 'status')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($vehicle_status, 'count')); ?>,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6f42c1'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Monthly Feedback Line Chart
        const feedbackTrendCtx = document.getElementById('feedbackTrendChart').getContext('2d');
        new Chart(feedbackTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthly_feedback, 'month')); ?>,
                datasets: [{
                    label: 'Feedback Count',
                    data: <?php echo json_encode(array_column($monthly_feedback, 'count')); ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Vehicle Brands Bar Chart
        const vehicleBrandsCtx = document.getElementById('vehicleBrandsChart').getContext('2d');
        new Chart(vehicleBrandsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($vehicle_brands, 'brand')); ?>,
                datasets: [{
                    label: 'Vehicle Count',
                    data: <?php echo json_encode(array_column($vehicle_brands, 'count')); ?>,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
                    borderColor: ['#0056b3', '#1e7e34', '#e0a800', '#c82333', '#59359a'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>