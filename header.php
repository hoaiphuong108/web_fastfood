<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/connect.php';

// Khởi tạo biến mặc định
$notification_count = 0;

// Nếu admin đã đăng nhập, lấy số thông báo chưa đọc
$admin_id = $_SESSION['admin_id'] ?? null;
if ($admin_id) {
    $db->query("SELECT COUNT(*) AS total_unread FROM notifications WHERE admin_id = '$admin_id' AND status = 'unread'");
    $notification_count = $db->single()['total_unread'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>../assets/dist/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>../assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/header.css">
</head>

<body>
    <header class=" header-main d-flex justify-content-between align-items-center px-3 py-2 header-main">
        <h4></h4>

        <div class="d-flex align-items-center gap-3">
            <!-- Thông báo -->
            <div class="notification position-relative">
                <i class="fas fa-bell fa-lg" id="notificationBell"></i>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                    <?= $notification_count ?>
                </span>

                <!-- Dropdown thông báo -->
                <div class="dropdown-notification collapse" id="notificationDropdown">
                    <ul class="list-group list-group-flush">
                        <?php
                        if ($admin_id) {
                            $db->query("SELECT * FROM notifications WHERE admin_id = '$admin_id' ORDER BY created_at DESC LIMIT 5");
                            $notifications = $db->resultset(); // giả sử trả về mảng
                            if ($notifications) {
                                foreach ($notifications as $note) {
                                    echo '<li class="list-group-item">' . htmlspecialchars($note['title']) . '</li>';
                                }
                            } else {
                                echo '<li class="list-group-item text-center">Không có thông báo mới</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Dropdown hồ sơ -->
            <div class="dropdown-header">
                <button class="btn btn-dark d-flex align-items-center" id="toggleDropdownHeader">
                    <i class="fas fa-user-circle me-2"></i>
                    <span>Admin</span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </button>
                <div class="dropdown-menu-header collapse">
                    <ul class="list-unstyled mb-0">
                        <li><a href="profile.php" class="dropdown-item">Hồ sơ cá nhân</a></li>
                        <li><a href="logout.php" class="dropdown-item text-danger">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </header>


    <script src="<?= BASE_URL ?>assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/dist/js/control-header.js"></script>
</body>

</html>