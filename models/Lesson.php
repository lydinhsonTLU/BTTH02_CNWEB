<?php
class Lesson extends Model {
    protected $table = 'lessons';

    // Lấy danh sách bài học theo khóa học
    public function getByCourseId($course_id)
    {
        $sql = "SELECT * FROM lessons WHERE course_id = ? ORDER BY `order`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin 1 bài học
    public function getById($id)
    {
        $sql = "SELECT * FROM lessons WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tài liệu của 1 bài học
    public function getMaterials($lesson_id)
    {
        $sql = "SELECT * FROM materials WHERE lesson_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$lesson_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?><?php
