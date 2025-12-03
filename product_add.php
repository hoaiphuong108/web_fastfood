<?php
// product_add.php
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/function.php';
$db = new Database();

/**
 * Hàm tạo slug từ tên sản phẩm
 */
function createSlug($string)
{
    $slug = strtolower($string);

    $unicode = [
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ'
    ];

    foreach ($unicode as $nonUnicode => $uni) {
        $slug = preg_replace("/($uni)/i", $nonUnicode, $slug);
    }

    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    $slug = trim($slug, '-');

    return $slug;
}

/**
 * Hàm thêm sản phẩm
 * @param array $data dữ liệu từ form ($_POST)
 * @param array|null $file dữ liệu file ($_FILES['image'])
 * @return array ['success' => bool, 'msg' => string]
 */
function addProduct($data, $file = null)
{
    global $db;

    // Lấy dữ liệu
    $name        = $data['name'] ?? '';
    $slug        = $data['slug'] ?: createSlug($name);
    $category_id = $data['category_id'] ?? null;
    $price       = $data['price'] ?? 0;
    $description = $data['description'] ?? '';
    $is_active   = $data['is_active'] ?? 1;

    // Upload ảnh nếu có
    $image_name = null;
    if ($file && $file['error'] === 0) {
        $image_name = time() . '_' . basename($file['name']);
        $uploadPath = __DIR__ . '/../../../../assets/dist/img/' . $image_name;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => false, 'msg' => 'Lỗi khi upload ảnh!'];
        }
    }

    try {
        // Thêm sản phẩm vào bảng products
        $db->query("INSERT INTO products (category_id, name, slug, price, description, is_active, created_at) 
                    VALUES (:category_id, :name, :slug, :price, :description, :is_active, NOW())");
        $db->bind(':category_id', $category_id);
        $db->bind(':name', $name);
        $db->bind(':slug', $slug);
        $db->bind(':price', $price);
        $db->bind(':description', $description);
        $db->bind(':is_active', $is_active);
        $db->execute();

        $product_id = $db->lastInsertId();

        // Nếu có ảnh, lưu vào product_images làm ảnh chính
        if ($image_name) {
            $db->query("INSERT INTO product_images (product_id, image_url, is_primary) 
                        VALUES (:product_id, :image_url, 1)");
            $db->bind(':product_id', $product_id);
            $db->bind(':image_url', $image_name);
            $db->execute();
        }

        return ['success' => true, 'msg' => 'Thêm sản phẩm thành công!'];
    } catch (Exception $e) {
        return ['success' => false, 'msg' => 'Lỗi: ' . $e->getMessage()];
    }
}
