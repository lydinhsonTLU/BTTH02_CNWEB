<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/UserDB.php';

define("APP_ROOT", dirname(__DIR__));

$db = new ConnectDb();
$userManager = new UserManager($db);

session_start();

// Nếu đã đăng nhập, chuyển đến dashboard
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 0) {
    header("Location: " . BASE_URL . "controllers/StudentController.php");
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
        // Lấy thông tin user theo username
        $user = $userManager->getUserByUsername($username);

        if ($user) {
            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Kiểm tra role = 0 (sinh viên)
                if ($user['role'] == 0) {
                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // Debug
                    error_log("Login successful for user: " . $user['username']);
                    error_log("Session data: " . print_r($_SESSION, true));

                    // Chuyển đến dashboard
                    header("Location: " . BASE_URL . "controllers/StudentController.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Tài khoản không có quyền truy cập dành cho sinh viên!";
                }
            } else {
                $_SESSION['error'] = "Mật khẩu không chính xác!";
            }
        } else {
            $_SESSION['error'] = "Tên đăng nhập không tồn tại!";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

include APP_ROOT . '/views/student/login_student.php';
