<?php
// Tổng số người dùng
$db->query('SELECT COUNT(user_id) as total_user FROM users');
$total_user = $db->single()['total_user'] ?? 0;

// Tổng số sản phẩm
$db->query('SELECT COUNT(product_id) as total_product FROM products');
$total_product = $db->single()['total_product'] ?? 0;

// Tổng số đơn hàng
$db->query('SELECT COUNT(order_id) as total_order FROM orders');
$total_order = $db->single()['total_order'] ?? 0;

// Tổng tiền của đơn hàng hoàn thành
// Giả sử trạng thái "hoàn thành" có status_id = 1
$db->query("SELECT SUM(final_amount) AS total_revenue FROM orders WHERE status_id = 1");
$total_revenue = $db->single()['total_revenue'] ?? 0;
$total_revenue_format = number_format($total_revenue, 0, ',', '.') . '₫';


// --- Lấy 5 đơn hàng gần đây ---
$sql_order = "SELECT 
    o.order_id,
    u.full_name AS khach_hang,
    b.name AS branch_name,
    o.created_at AS ngay_dat,
    os.status_name AS trang_thai,
    ds.delivery_status_name AS trang_thai_giao,
    o.final_amount AS tong_tien
FROM orders o
LEFT JOIN users u ON o.user_id = u.user_id
LEFT JOIN branches b ON o.branch_id = b.branch_id
LEFT JOIN order_statuses os ON o.status_id = os.status_id
LEFT JOIN delivery_statuses ds ON o.delivery_status_id = ds.delivery_status_id
ORDER BY o.created_at DESC
LIMIT 5";

$db->query($sql_order);
$orders = $db->resultSet();  // Lấy mảng kết quả

function getStatusClass($status_name)
{
    return match ($status_name) {
        'Chờ xác nhận' => 'status-choxacnhan',
        'Đang chuẩn bị' => 'status-dangchuabih',
        'Đang giao' => 'status-danggiao',
        'Hoàn tất' => 'status-hoantat',
        'Đã hủy' => 'status-dahuy',
        default => 'status-choxacnhan'
    };
}

function getDeliveryClass($delivery_name)
{
    return match ($delivery_name) {
        'Chưa giao' => 'delivery-chuagiao',
        'Đang giao' => 'delivery-danggiao',
        'Đã giao' => 'delivery-dagiao',
        'Đã hủy' => 'delivery-dahuy',
        default => 'delivery-chuagiao'
    };
}


?>

<main>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/status.css">
    <div class="typing-wrapper">
        <div class="typing-text"></div>
    </div>
    <!-- Cards thống kê -->
    <section class="stats-cards">
        <div class="card-user">
            <h3>Người dùng</h3>
            <p><i class="fas fa-users"></i><?php echo $total_user ?></p>
        </div>
        <div class="card-product">
            <h3>Sản phẩm</h3>
            <p><i class="fas fa-cubes"></i><?php echo $total_product ?></p>
        </div>
        <div class="card-order">
            <h3>Đơn hàng</h3>
            <p><i class="fas fa-clipboard-list"></i><?php echo $total_order ?></p>
        </div>
        <div class="card-doanhthu">
            <h3>Doanh thu</h3>
            <p><i class="fas fa-donate"></i><?php echo  $total_revenue ?>
            <p>
        </div>
    </section>
    <!-- Bảng đơn hàng gần đây -->
    <section class="table-section">
        <h3>Đơn hàng gần đây</h3>
        <div class="table-container">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID Đơn</th>
                        <th>Khách hàng</th>
                        <th>Chi Nhánh</th>
                        <th>Trạng thái</th>
                        <th>Trạng thái giao</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)) : ?>
                    <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['khach_hang']) ?></td>
                        <td><?= htmlspecialchars($order['branch_name'] ?? 'Chưa có') ?></td>
                        <!-- Trạng thái đơn hàng -->
                        <td>
                            <span class="status <?= getStatusClass($order['trang_thai'] ?? '') ?>">
                                <?= htmlspecialchars($order['trang_thai'] ?? 'Chưa có') ?>
                            </span>
                        </td>

                        <!-- Trạng thái giao hàng -->
                        <td>
                            <span class="delivery <?= getDeliveryClass($order['trang_thai_giao'] ?? '') ?>">
                                <?= htmlspecialchars($order['trang_thai_giao'] ?? 'Chưa có') ?>
                            </span>
                        </td>

                        <td><?= number_format($order['tong_tien'], 0, ',', '.') ?> đ</td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Chưa có đơn hàng nào</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <script src="<?= BASE_URL ?>/assets/dist/js/control-dashboard.js"></script>
</main>