<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/UserDB.php';

$db = new ConnectDb();

$userManager = new UserManager($db);

session_start();

$users = $userManager->getAllUsers();

$users_cho_duyet = $userManager->getAllUsers_cho_duyet();

//kich hoat
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && isset($_GET['kich_hoat'])) {
        $id = $_GET['id'];
        $user_cho_kich_hoat = $userManager->getUser_cho_duyet_ById($id);
        
        if ($user_cho_kich_hoat) {
            $userManager->createUser(
                $user_cho_kich_hoat['username'], 
                $user_cho_kich_hoat['email'], 
                $user_cho_kich_hoat['password'], 
                $user_cho_kich_hoat['fullname'], 
                $user_cho_kich_hoat['role']
            );
            
            $userManager->deleteUser_cho_duyet($id);
            
            $_SESSION['success'] = "Đã kích hoạt tài khoản thành công!";
        }
        
        header("Location: " . BASE_URL . "controllers/AdminController.php");
        exit();
    }
}

//vô hiệu hóa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['vo_hieu_hoa'])) {
        $id = $_POST['id'];
        $user_vo_hieu_hoa = $userManager->getUserById($id);

        if ($user_vo_hieu_hoa) {
            $userManager->createUser_cho_duyet(
                $user_vo_hieu_hoa['username'],
                $user_vo_hieu_hoa['email'],
                $user_vo_hieu_hoa['password'],
                $user_vo_hieu_hoa['fullname'],
                $user_vo_hieu_hoa['role']
            );

            $userManager->deleteUser($id);

            $_SESSION['success'] = "Đã vô hiệu hóa tài khoản thành công!";
        }

        header("Location: " . BASE_URL . "controllers/AdminController.php");
        exit();
    }
}

include __DIR__ . '/../views/admin/managerUser.php';