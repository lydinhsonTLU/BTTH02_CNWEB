<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/UserDB.php';

define("APP_ROOT", dirname(__DIR__));

$db = new ConnectDb();
$userManager = new UserManager($db);

session_start();

// Nếu đã đăng nhập, chuyển đến dashboard qua controller
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 1) {
    header("Location: " . BASE_URL . "controllers/TeacherController.php");
    exit();
}

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate
    $errors = [];

    if (empty($username)) {
        $errors[] = "Tên đăng nhập không được để trống";
    }

    if (empty($password)) {
        $errors[] = "Mật khẩu không được để trống";
    }

    if (empty($errors)) {
        // Lấy thông tin user theo username và role = 1 (giảng viên)
        $user = $userManager->getUserByRole($username, 1);

        if ($user) {
            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Chuyển đến dashboard qua controller
                header("Location: " . BASE_URL . "controllers/TeacherController.php");
                exit();
            } else {
                $_SESSION['error'] = "Mật khẩu không chính xác!";
            }
        } else {
            $_SESSION['error'] = "Tên đăng nhập không tồn tại hoặc không có quyền truy cập dành cho giảng viên!";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

include APP_ROOT . '/views/teacher/login_teacher.php';
