<?php
// Include DB và function
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/function.php';
$db = new Database();
$msg = "";

// Xử lý upload
if (isset($_POST['upload'])) {

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        // Tạo tên file mới để tránh trùng
        $filename = time() . '_' . $_FILES['image']['name'];
        $uploadPath = __DIR__ . '/../../../assets/dist/img/' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {

            // Lưu vào DB product_images, product_id = NULL → ảnh độc lập
            $db->query("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (NULL, :url, 1)");
            $db->bind(":url", $filename);
            $db->execute();

            $msg = "Upload ảnh thành công!";
        } else {
            $msg = "Lỗi khi di chuyển file!";
        }
    } else {
        $msg = "Chưa chọn ảnh!";
    }
}

// Lấy danh sách ảnh chưa gán sản phẩm
$db->query("SELECT * FROM product_images WHERE product_id IS NULL ORDER BY image_id DESC");
$images = $db->resultSet();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Upload ảnh</title>
</head>

<body>

    <h2>Upload ảnh</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit" name="upload">Upload</button>
    </form>

    <p><?= $msg ?></p>

    <hr>
    <h3>Danh sách ảnh đã upload</h3>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <?php foreach ($images as $img): ?>
            <div>
                <img src="<?= BASE_URL ?>assets/dist/img/<?= $img['image_url'] ?>" width="140"><br>
                ID: <?= $img['image_id'] ?><br>
                <?= $img['image_url'] ?>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>