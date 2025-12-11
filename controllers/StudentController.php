<?php
class Controller
{
    protected function view($viewPath, $data = [])
    {
        extract($data);

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
}

class StudentController extends Controller {

    private $enrollmentModel;
    private $courseModel;
    private $lessonModel;

    public function __construct() {
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
    }

    // 4. Xem khóa học đã đăng ký
    public function myCourses()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để xem khóa học của mình";
            $this->redirect('?url=auth/login');
        }

        $student_id = $_SESSION['user_id'];
        $courses = $this->enrollmentModel->getMyCourses($student_id);

        $this->view('student/my_courses', [
            'courses' => $courses
        ]);
    }

    // 5. Theo dõi tiến độ học tập (chi tiết khóa học cho học viên)
    public function viewCourse()
    {
        session_start();
        $course_id = $_GET['id'] ?? null;
        if (!$course_id || !is_numeric($course_id)) {
            $_SESSION['error'] = "Khóa học không hợp lệ";
            $this->redirect('?url=student');
        }

        $course = $this->courseModel->getById($course_id);
        if (!$course) {
            $_SESSION['error'] = "Không tìm thấy khóa học";
            $this->redirect('?url=student');
        }

        $lessons = $this->lessonModel->getByCourseId($course_id);
        $student_id = $_SESSION['user_id'] ?? null;
        $enrollment = $this->enrollmentModel->getEnrollment($course_id, $student_id);
        $progress = $enrollment['progress'] ?? 0;

        $this->view('student/course_detail', [
            'course' => $course,
            'lessons' => $lessons,
            'progress' => $progress
        ]);
    }

    // 6. Xem bài học và tài liệu
    public function viewLesson()
    {
        session_start();
        $lesson_id = $_GET['id'] ?? null;
        if (!$lesson_id || !is_numeric($lesson_id)) {
            $_SESSION['error'] = "Bài học không hợp lệ";
            $this->redirect('?url=student');
        }

        $lesson = $this->lessonModel->getById($lesson_id);
        if (!$lesson) {
            $_SESSION['error'] = "Không tìm thấy bài học";
            $this->redirect('?url=student');
        }

        $materials = $this->lessonModel->getMaterials($lesson_id);

        $this->view('student/lesson_detail', [
            'lesson' => $lesson,
            'materials' => $materials
        ]);
    }

}

?>