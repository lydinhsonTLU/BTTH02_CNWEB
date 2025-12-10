<?php
class Course {
    private $conn;
    private $table = "courses";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy khóa học của giảng viên cụ thể
    public function getByInstructor($instructor_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE instructor_id = :id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();
        return $stmt;
    }

    // Tạo khóa học mới
    public function create($title, $desc, $instructor_id, $cat_id, $price, $duration, $level, $image) {
        $query = "INSERT INTO " . $this->table . " 
                  (title, description, instructor_id, category_id, price, duration_weeks, level, image, created_at)
                  VALUES (:title, :desc, :uid, :cat_id, :price, :dur, :lvl, :img, NOW())";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':uid', $instructor_id);
        $stmt->bindParam(':cat_id', $cat_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':dur', $duration);
        $stmt->bindParam(':lvl', $level);
        $stmt->bindParam(':img', $image);
        
        return $stmt->execute();
    }

    // Lấy chi tiết khóa học
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Xóa khóa học
    public function delete($id, $instructor_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND instructor_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':uid', $instructor_id);
        return $stmt->execute();
    }

    // Lấy danh sách học viên của khóa học (Kèm tiến độ)
    public function getStudents($course_id, $instructor_id) {
        // Kiểm tra xem khóa học có thuộc giảng viên này không trước
        $checkParams = [':cid' => $course_id, ':uid' => $instructor_id];
        $checkSql = "SELECT id FROM courses WHERE id = :cid AND instructor_id = :uid";
        $stmtCheck = $this->conn->prepare($checkSql);
        $stmtCheck->execute($checkParams);
        
        if($stmtCheck->rowCount() == 0) return false;

        $query = "SELECT u.fullname, u.email, e.enrolled_date, e.progress, e.status 
                  FROM enrollments e
                  JOIN users u ON e.student_id = u.id
                  WHERE e.course_id = :cid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cid', $course_id);
        $stmt->execute();
        return $stmt;
    }
}
?>