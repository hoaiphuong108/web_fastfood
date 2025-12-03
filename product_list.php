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

// --- Đếm tổng số sản phẩm ---
$db->query("SELECT COUNT(*) AS total FROM products");
$total_product = $db->single()['total'] ?? 0;

// --- Tính tổng số trang ---
$total_pages = ($current_limit === 'all' || $current_limit == 0) ? 1 : ceil($total_product / $current_limit);

// --- Lấy danh sách sản phẩm + danh mục + ảnh chính ---
$sql = "SELECT p.product_id, p.name AS product_name, p.price, p.is_active, p.created_at,
               c.name AS category_name,
               pi.image_url
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi 
          ON pi.product_id = p.product_id AND pi.is_primary = 1
        ORDER BY p.created_at DESC";

if ($current_limit !== 'all') {
    $sql .= " LIMIT :start, :limit";
}

$db->query($sql);

if ($current_limit !== 'all') {
    $db->bind(':start', $start, PDO::PARAM_INT);
    $db->bind(':limit', $current_limit, PDO::PARAM_INT);
}

$products = $db->resultSet();

// --- Đặt giá trị mặc định ---
foreach ($products as &$p) {
    $p['product_name']   = $p['product_name'] ?? 'Chưa có tên';
    $p['category_name']  = $p['category_name'] ?? 'Chưa phân loại';
    $p['price']          = $p['price'] ?? 0;
    $p['quantity']       = 0;
    $p['is_active']      = $p['is_active'] ?? 1;
    $p['image_url']      = $p['image_url'] ?? null;
    $p['created_at']     = $p['created_at'] ?? date('Y-m-d H:i:s');
}
