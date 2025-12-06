<?php
require_once "../config/ConnectDb.php";
class UserManager {
    private $pdo;
    public function __construct(ConnectDb $db) {
        $this->pdo = $db->getConnection();
    }

// CREATE
    public function createUser($username, $email, $password, $fullname, $role) {
        $sql = "INSERT INTO users (username, email, password, fullname, role, created_at) VALUES (:username, :email, :password, :fullname, :role, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password, ':fullname' => $fullname, ':role' => $role]);
    }

    public function createUser_cho_duyet($username, $email, $password, $fullname, $role) {
        $sql = "INSERT INTO users_cho_duyet (username, email, password, fullname, role, created_at) VALUES (:username, :email, :password, :fullname, :role, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password, ':fullname' => $fullname, ':role' => $role]);
    }

    // READ ALL
    public function getAllUsers() {
        $sql = "SELECT * FROM users where role in (0,1)";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers_cho_duyet() {
        $sql = "SELECT * FROM users_cho_duyet where role in (0,1)";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ BY ID
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUser_cho_duyet_ById($id) {
        $sql = "SELECT * FROM users_cho_duyet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // UPDATE
    public function updateUser($id, $name, $email) {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':name' => $name, ':email' => $email, ':id' => $id]);
    }

    // DELETE
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deleteUser_cho_duyet($id) {
        $sql = "DELETE FROM users_cho_duyet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}