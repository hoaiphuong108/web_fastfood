<?php
require_once 'function/order_list.php';
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
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/fastfood.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/status.css">

    <h3>Danh sách Đơn Hàng</h3>

    <section class="table-section">
        <!-- Header có phần chọn hiển thị -->
        <div class="table-header d-flex">
            <h3>Danh sách đơn hàng</h3>

            <form method="get" action="" class="btn-show">
                <input type="hidden" name="page" value="order/list">
                <span>Show</span>
                <select name="limit" onchange="this.form.submit()">
                    <?php
                    $options = [5, 10, 20, 50, 'all'];
                    foreach ($options as $opt) {
                        $selected = ($current_limit == $opt) ? 'selected' : '';
                        $display_text = ($opt === 'all') ? 'ALL' : $opt;
                        echo "<option value=\"$opt\" $selected>$display_text</option>";
                    }
                    ?>
                </select>
            </form>
        </div>

        <!-- Bảng danh sách order -->
        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Chi nhánh</th>
                        <th>Tổng tiền</th>
                        <th>Giảm giá</th>
                        <th>Phí ship</th>
                        <th>Tổng thanh toán</th>
                        <th>Trạng thái giao hàng</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?= htmlspecialchars($o['order_id']) ?></td>
                        <td><?= htmlspecialchars($o['user_name'] ?? 'Chưa có') ?></td>
                        <td><?= htmlspecialchars($o['branch_name'] ?? 'Chưa có') ?></td>
                        <td><?= number_format($o['total_amount'], 0, ',', '.') ?> VND</td>
                        <td><?= number_format($o['discount_amount'] ?? 0, 0, ',', '.') ?> VND</td>
                        <td><?= number_format($o['shipping_fee'] ?? 0, 0, ',', '.') ?> VND</td>
                        <td><?= number_format($o['final_amount'] ?? 0, 0, ',', '.') ?> VND</td>
                        <td>
                            <span class="delivery <?= getDeliveryClass($o['trang_thai_giao'] ?? '') ?>">
                                <?= htmlspecialchars($o['trang_thai_giao'] ?? '') ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                        <td class="status">
                            <a href="edit.php?id=<?= $o['order_id'] ?>" class="btn-edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="delete.php?id=<?= $o['order_id'] ?>" class="btn-delete"
                                onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này không?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align:center;">Đang cập nhật.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <?php
        $max_pages_to_show = 3;
        if ($current_limit !== 'all' && $total_pages > 1):
            $start_page = max(1, $current_page - floor($max_pages_to_show / 2));
            $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);
            if ($end_page - $start_page + 1 < $max_pages_to_show) {
                $start_page = max(1, $end_page - $max_pages_to_show + 1);
            }
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="index.php?page=order/list&page_num=<?= max(1, $current_page - 1) ?>&limit=<?= $current_limit ?>">
                        &laquo;
                    </a>
                </li>
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                    <a class="page-link"
                        href="index.php?page=order/list&page_num=<?= $i ?>&limit=<?= $current_limit ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="index.php?page=order/list&page_num=<?= min($total_pages, $current_page + 1) ?>&limit=<?= $current_limit ?>">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </section>
</main>