<?php
class Enrollment extends Model {
    
    // Đăng ký học
    public function enroll($course_id, $student_id) {
        try {
            $sql = "INSERT INTO enrollments (course_id, student_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$course_id, $student_id]);
        } catch (Exception $e) {
            return false; // đã đăng ký rồi (do UNIQUE constraint)
        }
    }

    // Kiểm tra đã đăng ký chưa
    public function isEnrolled($course_id, $student_id) {
        $sql = "SELECT id FROM enrollments WHERE course_id = ? AND student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$course_id, $student_id]);
        return $stmt->rowCount() > 0;
    }

    // Lấy danh sách khóa học đã đăng ký của sinh viên
    public function getMyCourses($student_id)
    {
        $sql = "SELECT 
                    c.id, c.title, c.image, c.description,
                    e.progress, e.enrolled_date
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.student_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$student_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin enrollment (dùng để hiển thị tiến độ)
    public function getEnrollment($course_id, $student_id)
    {
        $sql = "SELECT * FROM enrollments WHERE course_id = ? AND student_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$course_id, $student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}

?>