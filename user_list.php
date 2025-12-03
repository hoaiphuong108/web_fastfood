<?php

// --- Lấy limit và trang hiện tại ---
$current_limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
$current_page  = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
if ($current_page < 1) $current_page = 1;

// --- Tính vị trí bắt đầu ---
if ($current_limit === 'all') {
    $start = 0;
} else {
    $start = ($current_page - 1) * $current_limit;
}

// --- Đếm tổng số user ---
$db->query("SELECT COUNT(*) AS total FROM users");
$total_user = $db->single()['total'];

// --- Tính tổng số trang ---
$total_pages = ($current_limit === 'all') ? 1 : ceil($total_user / $current_limit);

// --- Lấy danh sách user + role ---
if ($current_limit === 'all') {
    $sql = "SELECT u.*, r.name AS role_name
            FROM users u
            LEFT JOIN users_roles ur ON u.user_id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.role_id
            ORDER BY u.created_at DESC";
} else {
    $sql = "SELECT u.*, r.name AS role_name
            FROM users u
            LEFT JOIN users_roles ur ON u.user_id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.role_id
            ORDER BY u.created_at DESC
            LIMIT $start, $current_limit";
}

$db->query($sql);
$users = $db->resultSet();