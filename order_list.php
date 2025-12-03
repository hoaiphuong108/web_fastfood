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

// --- Đếm tổng số order ---
$db->query("SELECT COUNT(*) AS total FROM orders");
$total_order = $db->single()['total'] ?? 0;

// --- Tính tổng số trang ---
$total_pages = ($current_limit === 'all' || $current_limit == 0) ? 1 : ceil($total_order / $current_limit);

// --- Lấy danh sách order + user + branch + trạng thái giao hàng ---
$sql = "SELECT 
            o.*, 
            u.full_name AS user_name, 
            b.name AS branch_name, 
            ds.delivery_status_name AS delivery_statuses
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        LEFT JOIN branches b ON o.branch_id = b.branch_id
        LEFT JOIN delivery_statuses ds ON o.delivery_status_id = ds.delivery_status_id
        ORDER BY o.created_at DESC";

if ($current_limit !== 'all') {
    $sql .= " LIMIT :start, :limit";
}

$db->query($sql);

if ($current_limit !== 'all') {
    $db->bind(':start', $start, PDO::PARAM_INT);
    $db->bind(':limit', $current_limit, PDO::PARAM_INT);
}

$orders = $db->resultSet();
