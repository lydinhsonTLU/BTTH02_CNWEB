<?php
require_once __DIR__ . '/../config/configRoute.php';

session_start();

// Xóa toàn bộ session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ
header("Location: " . BASE_URL);
exit();
