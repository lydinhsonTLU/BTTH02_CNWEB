<?php
require_once 'models/Course.php';

class CourseController {
    private $db;
    private $courseModel;

    public function __construct() {
        // Kiểm tra quyền: Phải đăng nhập và là Giảng viên (role = 1)
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        $database = new ConnectDb();
        $this->db = $database->getConnection();
        $this->courseModel = new Course($this->db);
    }

    // 1. Xem danh sách khóa học của mình
    public function index() {
        $courses = $this->courseModel->getByInstructor($_SESSION['user_id']);
        require 'views/instructor/my_courses.php';
    }

    // 2. Tạo khóa học
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý upload ảnh
            $target_dir = "assets/uploads/courses/";
            $image_name = time() . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

            $this->courseModel->create(
                $_POST['title'],
                $_POST['description'],
                $_SESSION['user_id'],
                $_POST['category_id'],
                $_POST['price'],
                $_POST['duration'],
                $_POST['level'],
                $image_name
            );
            header("Location: index.php?controller=Course&action=index");
        } else {
            // Cần lấy danh mục để hiển thị ở view (tạm bỏ qua code lấy danh mục ở đây để ngắn gọn)
            require 'views/instructor/course/create.php';
        }
    }

    // 3. Xóa khóa học
    public function delete() {
        if (isset($_GET['id'])) {
            $this->courseModel->delete($_GET['id'], $_SESSION['user_id']);
        }
        header("Location: index.php?controller=Course&action=index");
    }
    
    // 4. Xem danh sách học viên đăng ký khóa học
    public function students() {
        if(isset($_GET['id'])) {
            $course_id = $_GET['id'];
            $students = $this->courseModel->getStudents($course_id, $_SESSION['user_id']);
            require 'views/instructor/students/list.php';
        }
    }
}
?>