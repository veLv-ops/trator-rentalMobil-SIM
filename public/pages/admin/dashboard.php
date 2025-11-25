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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Trator</title>
    
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
            color: var(--text-main);
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
        .stat-card {
            background-color: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }
        .chart-card {
            background-color: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
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
                        <a class="nav-link active" href="/trator/admin/dashboard">
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
                    <h1 class="h2 text-white">Dashboard Overview</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="text-main">
                            Welcome back, <span class="text-primary-custom fw-bold"><?php echo $_SESSION['admin_username']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="stat-card p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-main mb-1">Total Fleet</p>
                                    <h2 class="text-white fw-bold mb-0"><?php echo $total_cars; ?></h2>
                                </div>
                                <div class="bg-primary-custom p-3 rounded-circle text-dark">
                                    <i class="ph ph-car fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-main mb-1">Active Users</p>
                                    <h2 class="text-white fw-bold mb-0"><?php echo $total_users; ?></h2>
                                </div>
                                <div class="bg-dark border border-secondary p-3 rounded-circle text-white">
                                    <i class="ph ph-users fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-main mb-1">Total Feedbacks</p>
                                    <h2 class="text-white fw-bold mb-0"><?php echo $total_feedback; ?></h2>
                                </div>
                                <div class="bg-dark border border-secondary p-3 rounded-circle text-white">
                                    <i class="ph ph-chat-text fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="chart-card p-4 h-100">
                            <h5 class="text-white mb-4">Vehicle Status</h5>
                            <canvas id="vehicleStatusChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-card p-4 h-100">
                            <h5 class="text-white mb-4">Feedback Trend</h5>
                            <canvas id="feedbackTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="chart-card p-4 h-100">
                            <h5 class="text-white mb-4">Popular Brands</h5>
                            <canvas id="vehicleBrandsChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-card p-4 h-100">
                            <h5 class="text-white mb-4">Quick Stats</h5>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-main">Fleet Availability</small>
                                    <small class="text-primary-custom"><?php echo $total_cars > 0 ? round((array_sum(array_column(array_filter($vehicle_status, function($v) { return $v['status'] == 'available'; }), 'count')) / $total_cars * 100)) : 0; ?>%</small>
                                </div>
                                <div class="progress bg-dark" style="height: 6px;">
                                    <div class="progress-bar bg-primary-custom" style="width: <?php echo $total_cars > 0 ? (array_sum(array_column(array_filter($vehicle_status, function($v) { return $v['status'] == 'available'; }), 'count')) / $total_cars * 100) : 0; ?>%"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-main">User Engagement</small>
                                    <small class="text-white"><?php echo $total_users > 0 ? round(min(($total_feedback / $total_users * 100), 100)) : 0; ?>%</small>
                                </div>
                                <div class="progress bg-dark" style="height: 6px;">
                                    <div class="progress-bar bg-white" style="width: <?php echo $total_users > 0 ? min(($total_feedback / $total_users * 100), 100) : 0; ?>%"></div>
                                </div>
                            </div>
                            <div class="mt-auto text-center pt-3 border-top border-secondary">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="ph ph-pulse me-1"></i> System Healthy
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Chart Defaults
        Chart.defaults.color = '#A0A0A0';
        Chart.defaults.borderColor = '#333333';
        
        // Vehicle Status Pie Chart
        const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
        new Chart(vehicleStatusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($vehicle_status, 'status')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($vehicle_status, 'count')); ?>,
                    backgroundColor: ['#D4F800', '#FFFFFF', '#dc3545', '#6f42c1'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                },
                cutout: '70%'
            }
        });
        
        // Monthly Feedback Line Chart
        const feedbackTrendCtx = document.getElementById('feedbackTrendChart').getContext('2d');
        new Chart(feedbackTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthly_feedback, 'month')); ?>,
                datasets: [{
                    label: 'Feedbacks',
                    data: <?php echo json_encode(array_column($monthly_feedback, 'count')); ?>,
                    borderColor: '#D4F800',
                    backgroundColor: 'rgba(212, 248, 0, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#D4F800'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, grid: { color: '#333' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
        
        // Vehicle Brands Bar Chart
        const vehicleBrandsCtx = document.getElementById('vehicleBrandsChart').getContext('2d');
        new Chart(vehicleBrandsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($vehicle_brands, 'brand')); ?>,
                datasets: [{
                    label: 'Vehicles',
                    data: <?php echo json_encode(array_column($vehicle_brands, 'count')); ?>,
                    backgroundColor: '#FFFFFF',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, grid: { color: '#333' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>