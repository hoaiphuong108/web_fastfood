<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/sidebar.css">
    <link rel="icon" href="<?= BASE_URL ?>assets/dist/favicon.io-01.png" type="image/x-icon">
</head>

<body>
    <button class="btn-toggle-sidebar" id="toggleSidebar">
        <i class="fas fa-bars"></i>
    </button>
    <aside>
        <div class="sidebar-main">
            <div class="sidebar-header">

                <div class="logo-sidebar">
                    <img src="<?= BASE_URL ?>assets/dist/favicon.io-01.png" alt="logo">
                </div>
                <h2>Admin_Webfastfood</h2>
            </div>
            <!-- xog header -->
            <div class="sidebar-menu">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="?page=dashboard" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- QUẢN LÝ NGƯỜI DÙNG -->
                    <li class="nav-dropdown">
                        <a href="#menuUsers" class="dropdown-toggler" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="menuUsers">
                            <p>QUẢN LÝ NGƯỜI DÙNG</p>
                            <span class="nav-icon fas fa-chevron-down"></span>
                        </a>
                        <div class="collapse ps-3" id="menuUsers">
                            <ul class="dropdown-menu-sidebar">
                                <li class="nav-item">
                                    <a href="?page=use/list">
                                        <p>Danh sách người dùng</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="?page=use/feedback">
                                        <p>Quản lý đánh giá</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Hết!! -->

                    <!-- QUẢN LÝ SẢN PHẨM -->
                    <li class="nav-dropdown">
                        <a href="#menuProducts" class="dropdown-toggler" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="menuProducts">
                            <p>QUẢN LÝ SẢN PHẨM</p>
                            <span class="nav-icon fas fa-chevron-down"></span>
                        </a>
                        <div class="collapse ps-3" id="menuProducts">
                            <ul class="dropdown-menu-sidebar">
                                <!-- danh mục sản phẩm | danh sách sản phẩm -->
                                <li class="nav-item">
                                    <a href="?page=product/list">
                                        <p>Quản lý sản phẩm</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Hết!! Quản Lý Sản Phẩm -->

                    <!-- Quản Lý Đơn Hàng -->
                    <li class="nav-dropdown">
                        <a href="#menuOrders" class="dropdown-toggler" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="menuOrders">
                            <p>QUẢN LÝ ĐƠN HÀNG</p>
                            <span class="nav-icon fas fa-chevron-down"></span>
                        </a>
                        <div class=" collapse ps-3" id="menuOrders">
                            <ul class="dropdown-menu-sidebar">
                                <li class="nav-item"><a href="?page=order/list">
                                        <p>Quản lý đơn hàng</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Hết!! Quản Lý Đơn Hàng -->

                    <!-- Khuyến Mãi -->
                    <li class="nav-dropdown">
                        <a href="?page=promotion" class="dropdown-toggler">
                            <p>KHUYẾN MÃI</p>

                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
    <script src="<?= BASE_URL ?>assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/dist/js/control-sidebar.js"></script>
</body>

</html>