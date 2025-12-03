<?php
session_start();

// Hiển thị lỗi debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/connect.php';

$error = "";

// Nếu đã login admin → chuyển hướng
if (isset($_SESSION['user_id']) && strtolower($_SESSION['role'] ?? '') === 'admin') {
    header("Location: index.php");
    exit();
}

// Xử lý form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password_input = trim($_POST['password'] ?? '');

    if ($email === '' || $password_input === '') {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu!";
    } else {
        // Lấy user theo email
        $stmt = $conn->prepare("SELECT user_id, email, password_hash, full_name, is_active 
                                FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Kiểm tra tài khoản active
            if ($user['is_active'] == 0) {
                $error = "Tài khoản của bạn đã bị khóa!";
            } elseif (password_verify($password_input, $user['password_hash'])) {

                // Lấy role user
                $stmt_role = $conn->prepare("SELECT r.name AS role_name 
                                            FROM users_roles ur
                                            INNER JOIN roles r ON ur.role_id = r.role_id
                                            WHERE ur.user_id = ?");
                $stmt_role->bind_param("i", $user['user_id']);
                $stmt_role->execute();
                $res_role = $stmt_role->get_result();
                $role = $res_role->fetch_assoc();

                // Lưu session
                $_SESSION['user_id']   = $user['user_id'];
                $_SESSION['email']     = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $role['role_name'] ?? 'user';

                header("Location: index.php");
                exit();
            } else {
                $error = "Sai mật khẩu!";
            }
        } else {
            $error = "Email không tồn tại trong hệ thống!";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Tài Khoản</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/login.css">
</head>

<body>
    <div class="log-container">
        <div class="log-box">
            <div class="log-header">
                <h2>Đăng Nhập Hệ Thống</h2>

                <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

            </div>
            <form method="POST" action="" class="login-form" id="loginForm">
                <!-- Email -->
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" required autocomplete="email">
                        <label for="email" class="input-label">Email</label>
                    </div>
                </div>
                <!-- Mật Khẩu -->
                <div class="form-group">
                    <div class="input-wrapper password-wrapper">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <label for="password">Mật Khẩu</label>
                        <button type="button" class="password-toggle" id="passwordToggle"
                            aria-label="Toggle password visibility">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                </div>

                <div class="form-forgot">
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="log-btn">
                    <span class="btn-text">Đăng Nhập</span>
                    <span class="btn-loader"></span>
                </button>
            </form>
        </div>
    </div>
    <script src="<?= BASE_URL ?>assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/dist/js/control-login.js"></script>
</body>

</html>