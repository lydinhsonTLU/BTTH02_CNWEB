<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/CategoryDB.php';

$db = new ConnectDb();

$categoryManager = new CategoryManager($db);

session_start();

// Xử lý đăng xuất
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL);
    exit();
}

$categories = $categoryManager->getAllCategories();

if (isset($_POST['delete_category'])) {
    $id = $_POST['id'];
    $categoryManager->deleteCategory($id);
    $_SESSION['success'] = 'Xóa danh mục thành công!';
    header('Location: ' . BASE_URL . 'controllers/CategoryController.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        // XỬ LÝ THÊM MỚI
        $name = $_POST['category_name'];
        $description = $_POST['category_description'];

        $categoryManager->createCategory($name, $description);
        $_SESSION['success'] = 'Thêm danh mục thành công!';

    } elseif ($action === 'edit') {
        // XỬ LÝ CHỈNH SỬA
        $id = $_POST['category_id'];
        $name = $_POST['category_name'];
        $description = $_POST['category_description'];

        $categoryManager->updateCategory($id, $name, $description);
        $_SESSION['success'] = 'Cập nhật danh mục thành công!';
    }

    header('Location: ' . BASE_URL . 'controllers/CategoryController.php');
    exit();
}

include __DIR__ . '/../views/admin/managerCategory.php';
?>
<a href="<?= BASE_URL ?>controllers/CategoryController.php?action=logout">Đăng xuất</a>