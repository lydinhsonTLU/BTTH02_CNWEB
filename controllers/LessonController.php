<?php
require_once 'models/Lesson.php';

class LessonController {
    private $db;
    private $lessonModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        $database = new ConnectDb();
        $this->db = $database->getConnection();
        $this->lessonModel = new Lesson($this->db);
    }

    // Quản lý bài học của 1 khóa
    public function manage() {
        if (isset($_GET['course_id'])) {
            $lessons = $this->lessonModel->getByCourseId($_GET['course_id']);
            $course_id = $_GET['course_id'];
            require 'views/instructor/lessons/manage.php';
        }
    }

    // Tạo bài học mới & Upload tài liệu
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $course_id = $_POST['course_id'];
            
            // 1. Tạo bài học
            $lesson_id = $this->lessonModel->create(
                $course_id,
                $_POST['title'],
                $_POST['content'],
                $_POST['video_url'],
                $_POST['order']
            );

            // 2. Upload tài liệu (nếu có)
            if ($lesson_id && !empty($_FILES['material']['name'])) {
                $target_dir = "assets/uploads/materials/";
                $filename = basename($_FILES["material"]["name"]);
                $target_file = $target_dir . time() . "_" . $filename;
                $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                if (move_uploaded_file($_FILES["material"]["tmp_name"], $target_file)) {
                    $this->lessonModel->addMaterial($lesson_id, $filename, $target_file, $filetype);
                }
            }

            header("Location: index.php?controller=Lesson&action=manage&course_id=" . $course_id);
        } else {
            $course_id = $_GET['course_id'];
            require 'views/instructor/lessons/create.php';
        }
    }
}
?>