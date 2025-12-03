<?php
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
    return trim($slug, '-');
}

/**
 * Hàm cập nhật sản phẩm
 * @param int $product_id
 * @param array $data ($_POST)
 * @param array|null $file ($_FILES['image'])
 * @return array ['success'=>bool, 'msg'=>string]
 */
function editProduct($product_id, $data, $file = null)
{
    global $db;

    // Lấy dữ liệu
    $name        = $data['name'] ?? '';
    $slug        = $data['slug'] ?: createSlug($name);
    $category_id = $data['category_id'] ?? null;
    $price       = $data['price'] ?? 0;
    $description = $data['description'] ?? '';
    $is_active   = $data['is_active'] ?? 1;

    // Upload ảnh mới nếu có
    $image_name = null;
    if ($file && $file['error'] === 0) {
        $image_name = time() . '_' . basename($file['name']);
        $uploadPath = __DIR__ . '/../../../../assets/dist/img/' . $image_name;
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => false, 'msg' => 'Lỗi upload ảnh!'];
        }
    }

    try {
        // Cập nhật sản phẩm
        $db->query("UPDATE products 
                   SET name=:name, slug=:slug, category_id=:category_id,
                       price=:price, description=:description, is_active=:is_active
                   WHERE product_id=:id");
        $db->bind(':name', $name);
        $db->bind(':slug', $slug);
        $db->bind(':category_id', $category_id);
        $db->bind(':price', $price);
        $db->bind(':description', $description);
        $db->bind(':is_active', $is_active);
        $db->bind(':id', $product_id);
        $db->execute();

        // Nếu upload ảnh mới, cập nhật product_images
        if ($image_name) {
            $db->query("UPDATE product_images 
                       SET image_url=:image_url
                       WHERE product_id=:id AND is_primary=1");
            $db->bind(':image_url', $image_name);
            $db->bind(':id', $product_id);
            $db->execute();
        }

        return ['success' => true, 'msg' => 'Cập nhật sản phẩm thành công!'];
    } catch (Exception $e) {
        return ['success' => false, 'msg' => 'Lỗi: ' . $e->getMessage()];
    }
}
