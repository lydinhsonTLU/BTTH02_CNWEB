<?php
// controllers/Controller.php - FIX VĨNH VIỄN ĐƯỜNG DẪN VIEW
class Controller
{
    protected function view($viewPath, $data = [])
    {
        extract($data);

        // ĐƯỜNG DẪN TUYỆT ĐỐI – CHẠY ĐÚNG 100% MỌI LÚC
        $file = __DIR__ . "/../views/{$viewPath}.php";

        if (file_exists($file)) {
            require $file;
        } else {
            die("View không tồn tại: $file");
        }
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
    public function myCourses() {
    session_start();
    $student_id = $_SESSION['user_id'];

    $courses = EnrollmentModel::getMyCourses($student_id);

    include "./views/student/my_courses.php";
}
//Xem khóa học đã đăng ký
public function myCourses()
{
    session_start();
    $student_id = $_SESSION['user_id'];

    $courses = Enrollment::getMyCourses($student_id);

    include "./views/student/my_courses.php";
}
//Xem chi tiết khóa học + tiến độ + danh sách bài học
public function viewCourse()
{
    global $pdo;

    $course_id = $_GET['id'];

    // Lấy thông tin khóa học
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();

    // Lấy bài học
    $stmt = $pdo->prepare("SELECT * FROM lessons WHERE course_id=? ORDER BY `order`");
    $stmt->execute([$course_id]);
    $lessons = $stmt->fetchAll();

    include "./views/student/course_detail.php";
}
//xem bài học và tài liệu
public function viewLesson()
{
    global $pdo;

    $lesson_id = $_GET['id'];

    // Lấy thông tin bài học
    $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id=?");
    $stmt->execute([$lesson_id]);
    $lesson = $stmt->fetch();

    // Lấy tài liệu bài học
    $stmt = $pdo->prepare("SELECT * FROM materials WHERE lesson_id=?");
    $stmt->execute([$lesson_id]);
    $materials = $stmt->fetchAll();

    include "./views/student/lesson_detail.php";
}

}

?>