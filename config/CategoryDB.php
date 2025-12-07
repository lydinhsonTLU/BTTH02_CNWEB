<?php
require_once '../config/ConnectDb.php';

class CategoryManager
{
    private $pdo;
    public function __construct(ConnectDb $db) {
        $this->pdo = $db->getConnection();
    }
    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory($name, $description) {
        $sql = "INSERT INTO categories (name, description, created_at) VALUES (:name, :description, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':name' => $name, ':description' => $description]);
    }


    public function updateCategory($id, $name, $description) {
        $sql = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
