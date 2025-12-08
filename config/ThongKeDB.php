<?php

require_once __DIR__ . '/ConnectDb.php';
require_once __DIR__ . '/../models/ThongKe.php';

class ThongKeManager
{
    private $db;

    public function __construct(ConnectDb $db)
    {
        $this->db = $db;
    }

    /**
     * Lấy tổng số khóa học
     * @return int
     */
    public function getTongKhoahoc()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT COUNT(*) as total FROM courses";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy tổng số học viên (role = 0)
     * @return int
     */
    public function getTongHocvien()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy tổng số giảng viên (role = 1)
     * @return int
     */
    public function getTongGiangvien()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy tổng doanh thu từ các khóa học đã có học viên đăng ký
     * @return float
     */
    public function getDoanhthu()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT SUM(c.price) as total 
                FROM courses c
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.status = 'active' OR e.status = 'completed'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy 4 khóa học phổ biến nhất (nhiều học viên đăng ký nhất)
     * @return array
     */
    public function getKhoahocPhobien()
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT 
                    c.id,
                    c.title,
                    c.description,
                    c.price,
                    c.image,
                    c.level,
                    COUNT(e.id) as so_hocvien,
                    u.fullname as giang_vien
                FROM courses c
                LEFT JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN users u ON c.instructor_id = u.id
                GROUP BY c.id, c.title, c.description, c.price, c.image, c.level, u.fullname
                ORDER BY so_hocvien DESC
                LIMIT 4";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả thống kê trong một object ThongKe
     * @return ThongKe
     */
    public function getAllThongKe()
    {
        $tongKhoahoc = $this->getTongKhoahoc();
        $tongHocvien = $this->getTongHocvien();
        $tongGiangvien = $this->getTongGiangvien();
        $doanhthu = $this->getDoanhthu();
        $khoahocPhobien = $this->getKhoahocPhobien();

        return new ThongKe(
            $tongKhoahoc,
            $tongHocvien,
            $tongGiangvien,
            $doanhthu,
            $khoahocPhobien
        );
    }
}
