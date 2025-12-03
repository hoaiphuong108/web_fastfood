<?php

$errors = [];          // Khởi tạo mảng lỗi
$success = false;      // Khởi tạo trạng thái thành công
$db->query("SELECT role_id, name FROM roles");
$roles = $db->resultSet();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name  = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $address    = trim($_POST['address']);
    $role_id    = (int)($_POST['role'] ?? 0);
    $is_active  = (int)($_POST['is_active'] ?? 1);

    // Validate dữ liệu
    if (empty($full_name)) $errors[] = "Vui lòng nhập họ tên";
    if (empty($email)) $errors[] = "Vui lòng nhập email";
    if (empty($password)) $errors[] = "Vui lòng nhập mật khẩu";
    if ($role_id <= 0) $errors[] = "Vui lòng chọn vai trò";

    // Kiểm tra email đã tồn tại chưa
    if (empty($errors)) {
        $db->query("SELECT COUNT(*) AS count FROM users WHERE email = :email");
        $db->bind(':email', $email);
        $result = $db->single();
        if ($result['count'] > 0) {
            $errors[] = "Email này đã tồn tại, vui lòng chọn email khác.";
        }
    }

    // Thêm user nếu không có lỗi
    if (empty($errors)) {

        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (full_name, email, password_hash, address, is_active) 
                VALUES (:full_name, :email, :password_hash, :address, :is_active)";
        $db->query($sql);
        $db->bind(':full_name', $full_name);
        $db->bind(':email', $email);
        $db->bind(':password_hash', $hashed_pass);
        $db->bind(':address', $address);
        $db->bind(':is_active', $is_active);

        if ($db->execute()) {
            $user_id = $db->lastInsertId();

            // Thêm role
            $sql_role = "INSERT INTO users_roles (user_id, role_id) VALUES (:user_id, :role_id)";
            $db->query($sql_role);
            $db->bind(':user_id', $user_id);
            $db->bind(':role_id', $role_id);
            $db->execute();

            $success = true;
            $_POST = [];
        } else {
            $errors[] = "Lỗi thêm người dùng";
        }
    }
}