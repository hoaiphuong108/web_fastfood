<?php
// Tổng số mã khuyến mãi
$db->query('SELECT COUNT(promotion_id) as total_promo FROM promotions');
$total_promo = $db->single()['total_promo'] ?? 0;

// Tổng số mã khuyến mãi đang hoạt động
$db->query("SELECT COUNT(promotion_id) as active_promo FROM promotions 
            WHERE (start_date IS NULL OR start_date <= CURDATE()) 
              AND (end_date IS NULL OR end_date >= CURDATE())");
$active_promo = $db->single()['active_promo'] ?? 0;

// Danh sách 5 khuyến mãi gần đây
$sql_promo = "SELECT promotion_id, title, discount_type, discount_value, start_date, end_date, created_at
              FROM promotions
              ORDER BY created_at DESC
              LIMIT 5";

$db->query($sql_promo);
$promotions = $db->resultSet();
?>
<main>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/dashboard.css">

    <!-- Cards thống kê -->
    <section class="stats-cards">
        <div class="card-promo">
            <h3>Tổng mã khuyến mãi</h3>
            <p><i class="fas fa-ticket-alt"></i><?= $total_promo ?></p>
        </div>
        <div class="card-promo-active">
            <h3>Đang hoạt động</h3>
            <p><i class="fas fa-check-circle"></i><?= $active_promo ?></p>
        </div>
    </section>

    <!-- Bảng khuyến mãi -->
    <section class="table-section">
        <h3>Khuyến mãi gần đây</h3>
        <div class="table-container">
            <table class="promo-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khuyến mãi</th>
                        <th>Loại giảm</th>
                        <th>Giá trị</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($promotions)) : ?>
                    <?php foreach ($promotions as $promo) : ?>
                    <tr>
                        <td><?= htmlspecialchars($promo['promotion_id']) ?></td>
                        <td><?= htmlspecialchars($promo['title']) ?></td>
                        <td><?= htmlspecialchars($promo['discount_type']) ?></td>
                        <td>
                            <?= $promo['discount_type'] === 'percent'
                                        ? htmlspecialchars($promo['discount_value']) . '%'
                                        : number_format($promo['discount_value'], 0, ',', '.') . '₫' ?>
                        </td>
                        <td><?= htmlspecialchars($promo['start_date'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($promo['end_date'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">Chưa có khuyến mãi nào</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>