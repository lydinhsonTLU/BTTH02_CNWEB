<?php
require_once "../config/ConnectDb.php";

class CourseManager {
    private $pdo;

    public function __construct(ConnectDb $db) {
        $this->pdo = $db->getConnection();
    }
    
    public function createCourse($title, $description, $instructor_id, $category_id, $price, $duration_weeks, $level, $image)
    {
        $sql = "INSERT INTO courses 
                (title, description, instructor_id, category_id, price, duration_weeks, level, image, created_at)
                VALUES 
                (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image, NOW())";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':instructor_id' => $instructor_id,
            ':category_id' => $category_id,
            ':price' => $price,
            ':duration_weeks' => $duration_weeks,
            ':level' => $level,
            ':image' => $image
        ]);
    }
    
    public function createCourse_cho_duyet($title, $description, $instructor_id, $category_id, $price, $duration_weeks, $level, $image)
    {
        $sql = "INSERT INTO courses_cho_duyet 
                (title, description, instructor_id, category_id, price, duration_weeks, level, image, created_at)
                VALUES 
                (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image, NOW())";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':instructor_id' => $instructor_id,
            ':category_id' => $category_id,
            ':price' => $price,
            ':duration_weeks' => $duration_weeks,
            ':level' => $level,
            ':image' => $image
        ]);
    }
    
    public function getAllCourses() {
        $sql = "SELECT * FROM courses ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCourses_cho_duyet() {
        $sql = "SELECT * FROM courses_cho_duyet ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getCourseById($id) {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCourse_cho_duyet_ById($id) {
        $sql = "SELECT * FROM courses_cho_duyet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function updateCourse($id, $title, $description, $category_id, $price, $duration_weeks, $level, $image) {
        $sql = "UPDATE courses 
                SET title = :title,
                    description = :description,
                    category_id = :category_id,
                    price = :price,
                    duration_weeks = :duration_weeks,
                    level = :level,
                    image = :image
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $category_id,
            ':price' => $price,
            ':duration_weeks' => $duration_weeks,
            ':level' => $level,
            ':image' => $image,
            ':id' => $id
        ]);
    }

    
    public function deleteCourse($id) {
        $sql = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deleteCourse_cho_duyet($id) {
        $sql = "DELETE FROM courses_cho_duyet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
