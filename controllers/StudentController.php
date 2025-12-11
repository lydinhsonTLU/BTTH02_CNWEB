<?php
require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/ConnectDb.php';
require_once __DIR__ . '/../config/CourseDB.php';
require_once __DIR__ . '/../config/CategoryDB.php';

define("APP_ROOT", dirname(__DIR__));

session_start();

// Kiểm tra đăng nhập và quyền truy cập (phải là student - role = 0)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 0) {
    header("Location: " . BASE_URL . "controllers/LoginController_student.php");
    exit();
}

$db = new ConnectDb();
$pdo = $db->getConnection();
$courseManager = new CourseManager($db);
$categoryManager = new CategoryManager($db);

// Lấy thông tin student
$student_id = $_SESSION['user_id'];
$student_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Sinh viên';

// Khởi tạo các biến mặc định cho dashboard
$all_courses = [];
$enrolled_courses = [];
$categories = [];
$enrolled_course_ids = [];

// Xử lý các action
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        try {
            // Lấy tất cả courses với filter
            $search = $_GET['search'] ?? '';
            $category_filter = $_GET['category'] ?? '';
            
            $sql = "SELECT c.*, cat.name as category_name, u.fullname as instructor_name,
                    COUNT(DISTINCT e.id) as total_students
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    LEFT JOIN users u ON c.instructor_id = u.id
                    LEFT JOIN enrollments e ON c.id = e.course_id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (c.title LIKE :search OR c.description LIKE :search)";
                $params[':search'] = "%$search%";
            }
            
            if (!empty($category_filter)) {
                $sql .= " AND c.category_id = :category_id";
                $params[':category_id'] = $category_filter;
            }
            
            $sql .= " GROUP BY c.id ORDER BY c.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $all_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Lấy danh sách khóa học đã đăng ký
            $sql_enrolled = "SELECT e.*, c.title, c.description, c.image, c.duration_weeks, c.level,
                            cat.name as category_name, u.fullname as instructor_name
                            FROM enrollments e
                            JOIN courses c ON e.course_id = c.id
                            LEFT JOIN categories cat ON c.category_id = cat.id
                            LEFT JOIN users u ON c.instructor_id = u.id
                            WHERE e.student_id = :student_id
                            ORDER BY e.enrolled_date DESC";
            
            $stmt_enrolled = $pdo->prepare($sql_enrolled);
            $stmt_enrolled->execute([':student_id' => $student_id]);
            $enrolled_courses = $stmt_enrolled->fetchAll(PDO::FETCH_ASSOC);
            
            // Lấy danh sách categories
            $categories = $categoryManager->getAllCategories();
            
            // Kiểm tra khóa học nào đã đăng ký
            $enrolled_course_ids = array_column($enrolled_courses, 'course_id');
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Lỗi khi tải dữ liệu: " . $e->getMessage();
        }
        
        include APP_ROOT . '/views/student/dashboard.php';
        break;
        
    case 'course_detail':
        $course_id = $_GET['id'] ?? 0;
        
        // Lấy thông tin khóa học
        $sql = "SELECT c.*, cat.name as category_name, u.fullname as instructor_name, u.email as instructor_email,
                COUNT(DISTINCT e.id) as total_students
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                WHERE c.id = :course_id
                GROUP BY c.id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':course_id' => $course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$course) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: " . BASE_URL . "controllers/StudentController.php");
            exit();
        }
        
        // Kiểm tra đã đăng ký chưa
        $sql_check = "SELECT * FROM enrollments WHERE course_id = :course_id AND student_id = :student_id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':course_id' => $course_id, ':student_id' => $student_id]);
        $is_enrolled = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        // Lấy danh sách bài học
        $sql_lessons = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY `order`, created_at";
        $stmt_lessons = $pdo->prepare($sql_lessons);
        $stmt_lessons->execute([':course_id' => $course_id]);
        $lessons = $stmt_lessons->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/student/course_detail.php';
        break;
        
    case 'enroll':
        $course_id = $_POST['course_id'] ?? 0;
        
        // Kiểm tra khóa học tồn tại
        $course = $courseManager->getCourseById($course_id);
        if (!$course) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: " . BASE_URL . "controllers/StudentController.php");
            exit();
        }
        
        // Kiểm tra đã đăng ký chưa
        $sql_check = "SELECT * FROM enrollments WHERE course_id = :course_id AND student_id = :student_id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':course_id' => $course_id, ':student_id' => $student_id]);
        
        if ($stmt_check->fetch()) {
            $_SESSION['error'] = "Bạn đã đăng ký khóa học này rồi!";
        } else {
            // Đăng ký khóa học
            $sql_enroll = "INSERT INTO enrollments (course_id, student_id, enrolled_date, status, progress) 
                          VALUES (:course_id, :student_id, NOW(), 'active', 0)";
            $stmt_enroll = $pdo->prepare($sql_enroll);
            
            if ($stmt_enroll->execute([':course_id' => $course_id, ':student_id' => $student_id])) {
                $_SESSION['success'] = "Đăng ký khóa học thành công!";
            } else {
                $_SESSION['error'] = "Đăng ký khóa học thất bại!";
            }
        }
        
        header("Location: " . BASE_URL . "controllers/StudentController.php?action=course_detail&id=" . $course_id);
        exit();
        break;
        
    case 'my_courses':
        // Lấy danh sách khóa học đã đăng ký
        $sql = "SELECT e.*, c.title, c.description, c.image, c.duration_weeks, c.level,
                cat.name as category_name, u.fullname as instructor_name
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN users u ON c.instructor_id = u.id
                WHERE e.student_id = :student_id
                ORDER BY e.enrolled_date DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        $enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/student/my_courses.php';
        break;
        
    case 'view_lesson':
        $lesson_id = $_GET['lesson_id'] ?? 0;
        $course_id = $_GET['course_id'] ?? 0;
        
        // Kiểm tra đã đăng ký khóa học chưa
        $sql_check = "SELECT * FROM enrollments WHERE course_id = :course_id AND student_id = :student_id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':course_id' => $course_id, ':student_id' => $student_id]);
        $enrollment = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if (!$enrollment) {
            $_SESSION['error'] = "Bạn chưa đăng ký khóa học này!";
            header("Location: " . BASE_URL . "controllers/StudentController.php");
            exit();
        }
        
        // Lấy thông tin bài học
        $sql_lesson = "SELECT l.*, c.title as course_title 
                      FROM lessons l
                      JOIN courses c ON l.course_id = c.id
                      WHERE l.id = :lesson_id AND l.course_id = :course_id";
        $stmt_lesson = $pdo->prepare($sql_lesson);
        $stmt_lesson->execute([':lesson_id' => $lesson_id, ':course_id' => $course_id]);
        $lesson = $stmt_lesson->fetch(PDO::FETCH_ASSOC);
        
        if (!$lesson) {
            $_SESSION['error'] = "Bài học không tồn tại!";
            header("Location: " . BASE_URL . "controllers/StudentController.php?action=course_detail&id=" . $course_id);
            exit();
        }
        
        // Lấy tài liệu của bài học
        $sql_materials = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY uploaded_at";
        $stmt_materials = $pdo->prepare($sql_materials);
        $stmt_materials->execute([':lesson_id' => $lesson_id]);
        $materials = $stmt_materials->fetchAll(PDO::FETCH_ASSOC);
        
        // Lấy danh sách tất cả bài học của khóa học
        $sql_all_lessons = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY `order`, created_at";
        $stmt_all_lessons = $pdo->prepare($sql_all_lessons);
        $stmt_all_lessons->execute([':course_id' => $course_id]);
        $all_lessons = $stmt_all_lessons->fetchAll(PDO::FETCH_ASSOC);
        
        include APP_ROOT . '/views/student/view_lesson.php';
        break;
        
    case 'update_progress':
        header('Content-Type: application/json');
        $course_id = $_POST['course_id'] ?? 0;
        $progress = intval($_POST['progress'] ?? 0);
        
        // Cập nhật tiến độ
        $sql = "UPDATE enrollments SET progress = :progress WHERE course_id = :course_id AND student_id = :student_id";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([':progress' => $progress, ':course_id' => $course_id, ':student_id' => $student_id])) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật tiến độ thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật tiến độ thất bại']);
        }
        exit();
        break;
        
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL);
        exit();
        break;
        
    default:
        header("Location: " . BASE_URL . "controllers/StudentController.php");
        exit();
}
