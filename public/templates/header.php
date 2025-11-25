<?php 
// Determine correct path based on current directory
if (file_exists('../includes/functions.php')) {
    require_once '../includes/functions.php';
} elseif (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
} else {
    require_once dirname(__DIR__) . '/includes/functions.php';
}
startSession();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title : 'Trator - Premium Car Rental'; ?></title>
    <meta name="description" content="Premium Car Rental Services">
    
    <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo isset($page_style) ? $page_style : 'css/style.css'; ?>">
</head>
<body>