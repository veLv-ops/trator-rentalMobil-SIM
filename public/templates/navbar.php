<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/functions.php';
?>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= url('home') ?>">
            TRATOR<span>.</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('home') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('about') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('vehicles') ?>">Fleet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('feedbacks') ?>">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('contact') ?>">Contact</a>
                </li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary-custom btn-sm" href="<?= url('auth') ?>">Login / Register</a>
                    </li>
                <?php else: ?>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary-custom" href="<?= url('admin') ?>">Admin Panel</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-custom btn-sm" href="<?= url('logout') ?>">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>