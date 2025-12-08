<?php

require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/CourseDB.php';

$db = new ConnectDb();
$khoahoc = new CourseManager($db);

$pheduyetkhoahoc = $khoahoc->getAllCourses_cho_duyet();

//kich hoat
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && isset($_GET['duyet'])) {
        $id = $_GET['id'];
        $khoahoc_duyet = $khoahoc->getCourse_cho_duyet_ById($id);

        if ($khoahoc_duyet) {
            $khoahoc->createCourse(
                $khoahoc_duyet['title'],
                $khoahoc_duyet['description'],
                $khoahoc_duyet['instructor_id'],
                $khoahoc_duyet['category_id'],
                $khoahoc_duyet['price'],
                $khoahoc_duyet['duration_weeks'],
                $khoahoc_duyet['level'],
                $khoahoc_duyet['image']
            );

            $khoahoc->deleteCourse_cho_duyet($id);

            $_SESSION['success'] = "Đã duyệt khóa học!";
        }

        header("Location: " . BASE_URL . "controllers/PheduyetCourseController.php");
        exit();
    }
}

//vô hiệu hóa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['tu_choi'])) {
        $id = $_POST['id'];
        $khoahoc_tuchoi = $khoahoc->getCourse_cho_duyet_ById($id);

        if ($khoahoc_tuchoi) {
            $khoahoc->deleteCourse_cho_duyet($id);

            $_SESSION['success'] = "Đã từ chối khóa học!";
        }

        header("Location: " . BASE_URL . "controllers/PheduyetCourseController.php");
        exit();
    }
}


include __DIR__ . '/../views/admin/pheduyetCourse.php';