<?php
require_once 'function/product_add.php';
$msg = "";
$result = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = addProduct($_POST, $_FILES['image'] ?? null);
    $msg = $result['msg'];
}
?>

<main>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/fastfood.css">

    <h3>Thêm Sản Phẩm Mới</h3>

    <section class="table-section">
        <div class="table-header d-flex">
            <h3>Thông tin sản phẩm</h3>
            <a href="?page=product/list" class="btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="table-container form-container">
            <!-- Hiển thị thông báo -->
            <?php if (!empty($msg)): ?>
                <p style="color: <?= $result['success'] ? 'green' : 'red' ?>;"><?= htmlspecialchars($msg) ?></p>
            <?php endif; ?>
            <form class="user-form" method="post" enctype="multipart/form-data" action="">

                <!-- Ảnh sản phẩm -->
                <div class="form-group avatar-upload">
                    <label>Ảnh sản phẩm</label><br>
                    <input type="file" name="image" accept="image/*">
                </div>

                <!-- Tên sản phẩm -->
                <div class="form-group">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="name" placeholder="Nhập tên sản phẩm" required>
                </div>

                <!-- Slug (tự động hoặc nhập tay) -->
                <div class="form-group">
                    <label>Slug (URL thân thiện)</label>
                    <input type="text" name="slug" placeholder="Nhập slug hoặc để trống tự tạo">
                </div>

                <!-- Danh mục -->
                <div class="form-group">
                    <label>Danh mục</label>
                    <select name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Giá -->
                <div class="form-group">
                    <label>Giá</label>
                    <input type="number" name="price" placeholder="0,000" min="0" required>
                </div>

                <!-- Mô tả -->
                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="description" rows="4" placeholder="Nhập mô tả sản phẩm"></textarea>
                </div>

                <!-- Trạng thái -->
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="is_active">
                        <option value="1" selected>Còn bán</option>
                        <option value="0">Ngừng bán</option>
                    </select>
                </div>

                <!-- Nút Lưu -->
                <div class="form-group">
                    <button type="submit" class="btn-add-product"><i class="fas fa-plus"></i> Thêm sản phẩm</button>
                </div>
</main>