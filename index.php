<?php
// khởi động session luôn phải có nhaaa ':)))
session_start();
// // kiểm tra đăng nhập
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header('Location: login.php');
//     exit;
// }
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/admin/includes/db.php';
// khởi tạo database
$db = new Database();

// === Logic Phân Trang SPA === //
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; // trang mặc định khi đăng nhập vào
$path = str_replace('-', '/', $page);
$page_path = __DIR__ . "/admin/pages/{$path}.php";
// === Phân Quyền === //
ob_start(); // bắt bộ đệm và ghi nhớ nội dung
if (file_exists($page_path)) {
    include $page_path; // gọi các trang con khác
} else {
    require_once __DIR__ . '/errors/404.php';
}
$pages_content = ob_get_clean(); // lấy nội dung đã được ghi nhớ lưu vào biến
?>
<!-- Giao Diện HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- Header-Sidebar -->
    <?php
    include __DIR__ . '/layouts/header.php';
    include __DIR__ . '/layouts/sidebar.php';
    ?>
    <main id="main-content">
        <?= $pages_content ?>
    </main>
    <!-- Footer -->
    <?php
    include __DIR__ . '/layouts/footer.php';
    ?>
</body>
<script src="<?= BASE_URL ?>/assets/dist/js/bootstrap.bundle.min.js"></script>

</html>