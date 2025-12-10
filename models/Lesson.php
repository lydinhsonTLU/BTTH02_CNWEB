<?php
class Lesson {
    private $conn;
    private $table = "lessons";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByCourseId($course_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE course_id = :cid ORDER BY `order` ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cid', $course_id);
        $stmt->execute();
        return $stmt;
    }

    public function create($course_id, $title, $content, $video, $order) {
        $query = "INSERT INTO " . $this->table . " (course_id, title, content, video_url, `order`, created_at)
                  VALUES (:cid, :title, :content, :vid, :ord, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cid', $course_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':vid', $video);
        $stmt->bindParam(':ord', $order);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Upload tài liệu (vào bảng materials)
    public function addMaterial($lesson_id, $filename, $filepath, $filetype) {
        $query = "INSERT INTO materials (lesson_id, filename, file_path, file_type, uploaded_at)
                  VALUES (:lid, :fname, :fpath, :ftype, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lid', $lesson_id);
        $stmt->bindParam(':fname', $filename);
        $stmt->bindParam(':fpath', $filepath);
        $stmt->bindParam(':ftype', $filetype);
        return $stmt->execute();
    }
}
?>