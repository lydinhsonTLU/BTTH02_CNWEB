<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/ConnectDb.php';
require_once __DIR__ . '/../config/CourseDB.php';
require_once __DIR__ . '/../config/CategoryDB.php';

define("APP_ROOT", dirname(__DIR__));

session_start();

// Kiểm tra đăng nhập và quyền truy cập (phải là giảng viên - role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: " . BASE_URL . "controllers/LoginController_giangvien.php");
    exit();
}

$db = new ConnectDb();
$pdo = $db->getConnection();
$courseManager = new CourseManager($db);
$categoryManager = new CategoryManager($db);

// Lấy thông tin giảng viên
$teacher_id = $_SESSION['user_id'];
$teacher_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Giảng viên';

// Xử lý các action
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        // Lấy thống kê khóa học của giảng viên
        $sql_stats = "SELECT 
                        COUNT(DISTINCT c.id) as total_courses,
                        COUNT(DISTINCT e.student_id) as total_students,
                        COUNT(DISTINCT l.id) as total_lessons
                      FROM courses c
                      LEFT JOIN enrollments e ON c.id = e.course_id
                      LEFT JOIN lessons l ON c.id = l.course_id
                      WHERE c.instructor_id = :teacher_id";
        
        $stmt_stats = $pdo->prepare($sql_stats);
        $stmt_stats->execute([':teacher_id' => $teacher_id]);
        $stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
        
        // Lấy danh sách khóa học của giảng viên
        $sql_courses = "SELECT c.*, cat.name as category_name,
                        COUNT(DISTINCT e.id) as student_count,
                        COUNT(DISTINCT l.id) as lesson_count
                        FROM courses c
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        LEFT JOIN enrollments e ON c.id = e.course_id
                        LEFT JOIN lessons l ON c.id = l.course_id
                        WHERE c.instructor_id = :teacher_id
                        GROUP BY c.id
                        ORDER BY c.created_at DESC";
        
        $stmt_courses = $pdo->prepare($sql_courses);
        $stmt_courses->execute([':teacher_id' => $teacher_id]);
        $my_courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);
        
        // Lấy khóa học chờ duyệt
        $sql_pending = "SELECT * FROM courses_cho_duyet 
                        WHERE instructor_id = :teacher_id 
                        ORDER BY created_at DESC";
        $stmt_pending = $pdo->prepare($sql_pending);
        $stmt_pending->execute([':teacher_id' => $teacher_id]);
        $pending_courses = $stmt_pending->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/teacher/dashboard.php';
        break;
        
    case 'create_course':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $category_id = intval($_POST['category_id']);
            $price = floatval($_POST['price']);
            $duration_weeks = intval($_POST['duration_weeks']);
            $level = trim($_POST['level']);
            $image = trim($_POST['image']);
            
            // Tạo khóa học vào bảng chờ duyệt
            if ($courseManager->createCourse_cho_duyet($title, $description, $teacher_id, $category_id, $price, $duration_weeks, $level, $image)) {
                $_SESSION['success'] = "Khóa học đã được gửi đi chờ phê duyệt!";
            } else {
                $_SESSION['error'] = "Không thể tạo khóa học!";
            }
            
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        $categories = $categoryManager->getAllCategories();
        include APP_ROOT . '/views/teacher/create_course.php';
        break;
        
    case 'edit_course':
        $course_id = $_GET['id'] ?? 0;
        
        // Kiểm tra quyền sở hữu khóa học
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền chỉnh sửa khóa học này!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $category_id = intval($_POST['category_id']);
            $price = floatval($_POST['price']);
            $duration_weeks = intval($_POST['duration_weeks']);
            $level = trim($_POST['level']);
            $image = trim($_POST['image']);
            
            if ($courseManager->updateCourse($course_id, $title, $description, $category_id, $price, $duration_weeks, $level, $image)) {
                $_SESSION['success'] = "Cập nhật khóa học thành công!";
            } else {
                $_SESSION['error'] = "Không thể cập nhật khóa học!";
            }
            
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        $categories = $categoryManager->getAllCategories();
        include APP_ROOT . '/views/teacher/edit_course.php';
        break;
        
    case 'delete_course':
        $course_id = $_POST['id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền xóa khóa học này!";
        } else {
            if ($courseManager->deleteCourse($course_id)) {
                $_SESSION['success'] = "Xóa khóa học thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa khóa học!";
            }
        }
        
        header("Location: " . BASE_URL . "controllers/TeacherController.php");
        exit();
        break;
        
    case 'manage_lessons':
        $course_id = $_GET['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền truy cập khóa học này!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        // Lấy danh sách bài học
        $sql_lessons = "SELECT l.*, COUNT(m.id) as material_count
                        FROM lessons l
                        LEFT JOIN materials m ON l.id = m.lesson_id
                        WHERE l.course_id = :course_id
                        GROUP BY l.id
                        ORDER BY l.`order`, l.created_at";
        
        $stmt_lessons = $pdo->prepare($sql_lessons);
        $stmt_lessons->execute([':course_id' => $course_id]);
        $lessons = $stmt_lessons->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/teacher/manage_lessons.php';
        break;
        
    case 'create_lesson':
        $course_id = $_POST['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền tạo bài học cho khóa học này!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $video_url = trim($_POST['video_url']);
        $order = intval($_POST['order']);
        
        $sql = "INSERT INTO lessons (course_id, title, content, video_url, `order`, created_at)
                VALUES (:course_id, :title, :content, :video_url, :order, NOW())";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':course_id' => $course_id,
            ':title' => $title,
            ':content' => $content,
            ':video_url' => $video_url,
            ':order' => $order
        ])) {
            $_SESSION['success'] = "Tạo bài học thành công!";
        } else {
            $_SESSION['error'] = "Không thể tạo bài học!";
        }
        
        header("Location: " . BASE_URL . "controllers/TeacherController.php?action=manage_lessons&course_id=" . $course_id);
        exit();
        break;
        
    case 'edit_lesson':
        $lesson_id = $_POST['lesson_id'] ?? 0;
        $course_id = $_POST['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền chỉnh sửa bài học này!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $video_url = trim($_POST['video_url']);
        $order = intval($_POST['order']);
        
        $sql = "UPDATE lessons 
                SET title = :title, content = :content, video_url = :video_url, `order` = :order
                WHERE id = :lesson_id AND course_id = :course_id";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':video_url' => $video_url,
            ':order' => $order,
            ':lesson_id' => $lesson_id,
            ':course_id' => $course_id
        ])) {
            $_SESSION['success'] = "Cập nhật bài học thành công!";
        } else {
            $_SESSION['error'] = "Không thể cập nhật bài học!";
        }
        
        header("Location: " . BASE_URL . "controllers/TeacherController.php?action=manage_lessons&course_id=" . $course_id);
        exit();
        break;
        
    case 'delete_lesson':
        $lesson_id = $_POST['lesson_id'] ?? 0;
        $course_id = $_POST['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền xóa bài học này!";
        } else {
            $sql = "DELETE FROM lessons WHERE id = :lesson_id AND course_id = :course_id";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([':lesson_id' => $lesson_id, ':course_id' => $course_id])) {
                $_SESSION['success'] = "Xóa bài học thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa bài học!";
            }
        }
        
        header("Location: " . BASE_URL . "controllers/TeacherController.php?action=manage_lessons&course_id=" . $course_id);
        exit();
        break;
        
    case 'manage_materials':
        $lesson_id = $_GET['lesson_id'] ?? 0;
        $course_id = $_GET['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền truy cập!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        // Lấy thông tin bài học
        $sql_lesson = "SELECT * FROM lessons WHERE id = :lesson_id AND course_id = :course_id";
        $stmt_lesson = $pdo->prepare($sql_lesson);
        $stmt_lesson->execute([':lesson_id' => $lesson_id, ':course_id' => $course_id]);
        $lesson = $stmt_lesson->fetch(PDO::FETCH_ASSOC);
        
        if (!$lesson) {
            $_SESSION['error'] = "Bài học không tồn tại!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        // Lấy danh sách tài liệu
        $sql_materials = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY uploaded_at DESC";
        $stmt_materials = $pdo->prepare($sql_materials);
        $stmt_materials->execute([':lesson_id' => $lesson_id]);
        $materials = $stmt_materials->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/teacher/manage_materials.php';
        break;
        
    case 'add_material':
        $lesson_id = $_POST['lesson_id'] ?? 0;
        $course_id = $_POST['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền thêm tài liệu!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        $filename = trim($_POST['filename']);
        $file_path = trim($_POST['file_path']);
        $file_type = trim($_POST['file_type']);
        
        $sql = "INSERT INTO materials (lesson_id, filename, file_path, file_type, uploaded_at)
                VALUES (:lesson_id, :filename, :file_path, :file_type, NOW())";
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':lesson_id' => $lesson_id,
            ':filename' => $filename,
            ':file_path' => $file_path,
            ':file_type' => $file_type
        ])) {
            $_SESSION['success'] = "Thêm tài liệu thành công!";
        } else {
            $_SESSION['error'] = "Không thể thêm tài liệu!";
        }
        
        header("Location: " . BASE_URL . "controllers/TeacherController.php?action=manage_materials&lesson_id=" . $lesson_id . "&course_id=" . $course_id);
        exit();
        break;
        
    case 'delete_material':
        $material_id = $_POST['material_id'] ?? 0;
        $lesson_id = $_POST['lesson_id'] ?? 0;
        $course_id = $_POST['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền xóa tài liệu!";
        } else {
            $sql = "DELETE FROM materials WHERE id = :material_id";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([':material_id' => $material_id])) {
                $_SESSION['success'] = "Xóa tài liệu thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa tài liệu!";
            }
        }
        
        header("Location: " . BASE_URL . "controllers/TeacherController.php?action=manage_materials&lesson_id=" . $lesson_id . "&course_id=" . $course_id);
        exit();
        break;
        
    case 'view_students':
        $course_id = $_GET['course_id'] ?? 0;
        
        // Kiểm tra quyền sở hữu
        $course = $courseManager->getCourseById($course_id);
        if (!$course || $course['instructor_id'] != $teacher_id) {
            $_SESSION['error'] = "Bạn không có quyền xem danh sách học viên!";
            header("Location: " . BASE_URL . "controllers/TeacherController.php");
            exit();
        }
        
        // Lấy danh sách học viên đã đăng ký
        $sql_students = "SELECT e.*, u.username, u.fullname, u.email, e.progress
                        FROM enrollments e
                        JOIN users u ON e.student_id = u.id
                        WHERE e.course_id = :course_id
                        ORDER BY e.enrolled_date DESC";
        
        $stmt_students = $pdo->prepare($sql_students);
        $stmt_students->execute([':course_id' => $course_id]);
        $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/teacher/view_students.php';
        break;
        
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL);
        exit();
        break;
        
    default:
        header("Location: " . BASE_URL . "controllers/TeacherController.php");
        exit();
}
