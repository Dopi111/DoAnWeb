<?php
/**
 * Cấu hình kết nối Database
 * Hệ thống Quản lý Trung tâm Dạy thêm
 */

class Database {
    // Thông tin kết nối
    private $host = "localhost";
    private $db_name = "QuanLyHocThem";
    private $username = "root"; // Thay đổi nếu cần
    private $password = "";     // Thay đổi nếu cần
    private $charset = "utf8mb4";

    public $conn;

    /**
     * Kết nối database
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi kết nối database: ' . $exception->getMessage()
            ]);
            die();
        }

        return $this->conn;
    }
}
?>
