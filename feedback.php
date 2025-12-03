<?php
// --- Lấy limit và trang hiện tại ---
$current_limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
$current_page  = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
if ($current_page < 1) $current_page = 1;

$start = ($current_limit === 'all') ? 0 : ($current_page - 1) * $current_limit;

// --- Lấy filter từ form ---
$keyword   = trim($_GET['keyword'] ?? '');
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';
$rating    = $_GET['rating'] ?? '';
$status    = $_GET['status'] ?? '';

$where = [];
$params = [];

// --- Filter keyword (bind riêng cho từng placeholder) ---
if ($keyword !== '') {
    $where[] = "(u.full_name LIKE :keyword1 OR f.order_id LIKE :keyword2 OR b.name LIKE :keyword3)";
    $params[':keyword1'] = "%$keyword%";
    $params[':keyword2'] = "%$keyword%";
    $params[':keyword3'] = "%$keyword%";
}

// --- Filter date ---
if ($date_from !== '') {
    $where[] = "f.created_at >= :date_from";
    $params[':date_from'] = $date_from . " 00:00:00";
}
if ($date_to !== '') {
    $where[] = "f.created_at <= :date_to";
    $params[':date_to'] = $date_to . " 23:59:59";
}

// --- Filter rating ---
if ($rating !== '') {
    $where[] = "f.rating = :rating";
    $params[':rating'] = $rating;
}

// --- Filter status ---
if ($status !== '') {
    if ($status === 'pending') $where[] = "f.is_active = 0";
    if ($status === 'approved') $where[] = "f.is_active = 1";
    if ($status === 'hidden') $where[] = "f.is_active = -1"; // giả sử hidden = -1
}

// --- Build WHERE SQL ---
$where_sql = '';
if (!empty($where)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
}

// --- Count total feedbacks ---
$count_sql = "SELECT COUNT(*) AS total
              FROM feedbacks f
              LEFT JOIN users u ON f.user_id = u.user_id
              LEFT JOIN branches b ON f.branch_id = b.branch_id
              $where_sql";

$db->query($count_sql);
foreach ($params as $key => $val) $db->bind($key, $val);
$total_feedback = $db->single()['total'];

// --- Tính tổng số trang ---
$total_pages = ($current_limit === 'all') ? 1 : ceil($total_feedback / $current_limit);

// --- Lấy danh sách feedbacks + orders + user + branches
$sql = "SELECT f.feedback_id,
               f.order_id,
               f.branch_id,
               f.created_at,
               f.rating,
               f.is_active,
               u.full_name AS user_name,
               COALESCE(b.name, ob.name) AS branch_name
        FROM feedbacks f
        LEFT JOIN users u ON f.user_id = u.user_id
        LEFT JOIN orders o ON f.order_id = o.order_id
        LEFT JOIN branches b ON f.branch_id = b.branch_id
        LEFT JOIN branches ob ON o.branch_id = ob.branch_id
        $where_sql
        ORDER BY f.created_at DESC";

if ($current_limit !== 'all') {
    $sql .= " LIMIT $start, " . (int)$current_limit;
}

$db->query($sql);
foreach ($params as $key => $val) $db->bind($key, $val);
$feedbacks = $db->resultSet();
?>

<main>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/feedback.css">
    <h3>Quản lý Đánh Giá </h3>

    <section class="nav-search">
        <form action="index.php" method="get">
            <input type="hidden" name="page" value="use/feedback">
            <!-- Tìm kiếm theo tên danh mục sản phẩm -->
            <input type="text" name="keyword" placeholder="Search"
                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" class="input-search">
            <!-- Giới hạn tìm kiếm từ ngày nhiu -> ngày nhiu -->
            <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"
                class="input-date">
            <span style="margin: 0 5px;">-</span>
            <input type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"
                class="input-date">
            <!-- Lọc theo đánh giá 1 -> 5 sao -->
            <select name="rating" class="input-select">
                <option value="">Đánh giá</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>" <?= (($_GET['rating'] ?? '') == $i) ? 'selected' : '' ?>>
                        <?= str_repeat('★', $i) ?>
                    </option>
                <?php endfor; ?>
            </select>
            <!-- Lọc theo trạng thái -->
            <select name="status" class="input-select">
                <option value="">Trạng thái</option>
                <option value="pending" <?= (($_GET['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Chờ duyệt
                </option>
                <option value="approved" <?= (($_GET['status'] ?? '') == 'approved') ? 'selected' : '' ?>>Đã duyệt
                </option>
                <option value="hidden" <?= (($_GET['status'] ?? '') == 'hidden') ? 'selected' : '' ?>>Ẩn</option>
            </select>
            <button type="submit" class="btn-search">Tìm Kiếm</button>
        </form>
    </section>

    <!-- Bảng hiện thị đánh giá gần đây (giới hạn)
             Tên Khách Hàng
             ID đơn hàng
             Ngày đăng
             Mức đánh giá
             Trạng Thái
             Thao tác: Duyệt | Ẩn | Xóa | Xem Chi Tiết -->
    <section class="table-section">
        <!-- Header có phần chọn hiển thị -->
        <div class="table-header d-flex">
            <h3>Danh sách đánh giá gần đây</h3>

            <form method="get" action="" class="btn-show">
                <input type="hidden" name="page" value="use/feedback">
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
        <!-- Bảng đánh giá gần đây -->
        <div class="table-container">
            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Họ và Tên</th>
                        <th>ID Đơn hàng</th>
                        <th>Cửa hàng</th>
                        <th>Ngày tạo</th>
                        <th>Đánh giá</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $fb): ?>
                            <tr>
                                <td><?= htmlspecialchars($fb['user_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($fb['order_id'] ?? '') ?></td>
                                <td><?= htmlspecialchars($fb['branch_name'] ?? '') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($fb['created_at'])) ?></td>
                                <td>
                                    <?= str_repeat('★', $fb['rating']) ?>
                                    <?= str_repeat('☆', 5 - $fb['rating']) ?>
                                </td>
                                <td class="status">
                                    <?php if ($fb['is_active'] == 1): ?>
                                        <span class="status-active">Đã duyệt</span>
                                    <?php else: ?>
                                        <span class="status-locked">Chờ duyệt</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="approve.php?id=<?= $fb['feedback_id'] ?>" class="btn-approve">Duyệt</a>
                                    <a href="hide.php?id=<?= $fb['feedback_id'] ?>" class="btn-hide">Ẩn</a>
                                    <a href="delete.php?id=<?= $fb['feedback_id'] ?>" class="btn-delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa đánh giá này không?');">Xóa</a>
                                    <a href="index.php?page=use/feedback_view&id=<?= $fb['feedback_id'] ?>" class="btn-view">Xem
                                        chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">Chưa có phản hồi nào.</td>
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
                            href="index.php?page=use/feedback&page_num=<?= max(1, $current_page - 1) ?>?&&<?= $current_limit ?>">
                            &laquo;
                        </a>
                    </li>


                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?page=use/feedback&page_num=<?= $i ?>?&&<?= $current_limit ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>


                    <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link"
                            href="index.php?page=use/feedback&page_num=<?= min($total_pages, $current_page + 1) ?>?&&<?= $current_limit ?>">
                            &raquo;
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </section>
</main>