<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/UserDB.php';

define("APP_ROOT", dirname(__DIR__));

$db = new ConnectDb();
$userManager = new UserManager($db);

session_start();

// Xử lý đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = intval($_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate
    $errors = [];

    if (empty($fullname)) {
        $errors[] = "Họ và tên không được để trống";
    }

    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ";
    }

    if (!in_array($role, [0, 1])) {
        $errors[] = "Vai trò không hợp lệ";
    }

    if (strlen($password) < 6) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Mật khẩu xác nhận không khớp";
    }

    // Nếu không có lỗi, tiến hành đăng ký
    if (empty($errors)) {
        // Hash mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Tạo tài khoản chờ duyệt
        try {
            $result = $userManager->createUser_cho_duyet(
                $username,
                $email,
                $hashed_password,
                $fullname,
                $role
            );

            if ($result) {
                $_SESSION['success'] = "Đăng ký thành công! Vui lòng đợi admin phê duyệt tài khoản.";
                header("Location: " . BASE_URL);
                exit();
            } else {
                $_SESSION['error'] = "Đăng ký thất bại! Vui lòng thử lại.";
            }
        } catch (PDOException $e) {
            // Xử lý lỗi trùng username hoặc email
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $_SESSION['error'] = "Tên đăng nhập hoặc email đã tồn tại!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

include APP_ROOT . '/views/auth/register.php';
