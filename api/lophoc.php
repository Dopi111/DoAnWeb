<?php
/**
 * API Quản lý Lớp học
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/helpers.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// GET - Lấy danh sách lớp học
if ($method === 'GET') {
    try {
        if ($action === 'detail' && isset($_GET['id'])) {
            // Chi tiết lớp học
            $id = $_GET['id'];

            $query = "SELECT
                        lh.MaLop,
                        lh.TenLop,
                        kh.TenKhoaHoc,
                        kh.MoTa,
                        kh.HocPhi,
                        lh.PhongHoc,
                        lh.SiSoToiDa,
                        lh.SiSoHienTai,
                        lh.NgayBatDau,
                        lh.NgayKetThuc,
                        lh.TrangThai,
                        gv.HoTen as GiaoVien,
                        gv.MaNguoiDung as MaGiaoVien,
                        GROUP_CONCAT(CONCAT(lc.ThuTrongTuan, ' ', lc.GioBatDau, '-', lc.GioKetThuc) SEPARATOR ', ') as LichHoc
                      FROM LopHoc lh
                      JOIN KhoaHoc kh ON lh.MaKhoaHoc = kh.MaKhoaHoc
                      LEFT JOIN PhanCongGiaoVien pc ON lh.MaLop = pc.MaLop
                      LEFT JOIN NguoiDung gv ON pc.MaGiaoVien = gv.MaNguoiDung
                      LEFT JOIN LichHoc lc ON lh.MaLop = lc.MaLop
                      WHERE lh.MaLop = :id
                      GROUP BY lh.MaLop";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                sendResponse(true, 'Thành công', $stmt->fetch());
            } else {
                sendResponse(false, 'Không tìm thấy lớp học', null, 404);
            }
        } elseif ($action === 'hocsinh' && isset($_GET['id'])) {
            // Danh sách học sinh trong lớp
            $id = $_GET['id'];

            $query = "SELECT
                        hs.MaHocSinh,
                        nd.HoTen,
                        nd.Email,
                        nd.SoDienThoai,
                        hs.LopHienTai,
                        dkl.NgayDangKy,
                        dkl.TrangThai
                      FROM DangKyLop dkl
                      JOIN HocSinh hs ON dkl.MaHocSinh = hs.MaHocSinh
                      JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                      WHERE dkl.MaLop = :id AND dkl.TrangThai = 'DaXacNhan'
                      ORDER BY nd.HoTen";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        } else {
            // Danh sách tất cả lớp học
            $query = "SELECT
                        lh.MaLop,
                        lh.TenLop,
                        kh.TenKhoaHoc,
                        kh.HocPhi,
                        lh.PhongHoc,
                        lh.SiSoHienTai,
                        lh.SiSoToiDa,
                        lh.NgayBatDau,
                        lh.NgayKetThuc,
                        lh.TrangThai,
                        gv.HoTen as GiaoVien,
                        GROUP_CONCAT(CONCAT(lc.ThuTrongTuan, ' ', lc.GioBatDau, '-', lc.GioKetThuc) SEPARATOR ', ') as LichHoc
                      FROM LopHoc lh
                      JOIN KhoaHoc kh ON lh.MaKhoaHoc = kh.MaKhoaHoc
                      LEFT JOIN PhanCongGiaoVien pc ON lh.MaLop = pc.MaLop
                      LEFT JOIN NguoiDung gv ON pc.MaGiaoVien = gv.MaNguoiDung
                      LEFT JOIN LichHoc lc ON lh.MaLop = lc.MaLop
                      GROUP BY lh.MaLop
                      ORDER BY lh.NgayBatDau DESC";

            $stmt = $db->prepare($query);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// POST - Tạo lớp học mới
if ($method === 'POST' && $action === 'create') {
    requireRole(['Admin']);

    $data = json_decode(file_get_contents("php://input"), true);

    $tenLop = isset($data['ten_lop']) ? sanitizeInput($data['ten_lop']) : '';
    $maKhoaHoc = isset($data['ma_khoa_hoc']) ? $data['ma_khoa_hoc'] : null;
    $phongHoc = isset($data['phong_hoc']) ? sanitizeInput($data['phong_hoc']) : '';
    $siSoToiDa = isset($data['si_so_toi_da']) ? $data['si_so_toi_da'] : 12;
    $ngayBatDau = isset($data['ngay_bat_dau']) ? $data['ngay_bat_dau'] : null;
    $ngayKetThuc = isset($data['ngay_ket_thuc']) ? $data['ngay_ket_thuc'] : null;
    $maGiaoVien = isset($data['ma_giao_vien']) ? $data['ma_giao_vien'] : null;
    $lichHoc = isset($data['lich_hoc']) ? $data['lich_hoc'] : []; // Array of schedules

    if (empty($tenLop) || empty($maKhoaHoc)) {
        sendResponse(false, 'Vui lòng nhập đầy đủ thông tin', null, 400);
    }

    try {
        $db->beginTransaction();

        // Insert lớp học
        $insertQuery = "INSERT INTO LopHoc (TenLop, MaKhoaHoc, PhongHoc, SiSoToiDa, NgayBatDau, NgayKetThuc)
                       VALUES (:ten_lop, :ma_khoa_hoc, :phong_hoc, :si_so, :ngay_bat_dau, :ngay_ket_thuc)";

        $stmt = $db->prepare($insertQuery);
        $stmt->bindParam(':ten_lop', $tenLop);
        $stmt->bindParam(':ma_khoa_hoc', $maKhoaHoc);
        $stmt->bindParam(':phong_hoc', $phongHoc);
        $stmt->bindParam(':si_so', $siSoToiDa);
        $stmt->bindParam(':ngay_bat_dau', $ngayBatDau);
        $stmt->bindParam(':ngay_ket_thuc', $ngayKetThuc);
        $stmt->execute();

        $lopId = $db->lastInsertId();

        // Phân công giáo viên (nếu có)
        if ($maGiaoVien) {
            $pcQuery = "INSERT INTO PhanCongGiaoVien (MaLop, MaGiaoVien, NgayPhanCong)
                       VALUES (:ma_lop, :ma_giao_vien, CURDATE())";
            $pcStmt = $db->prepare($pcQuery);
            $pcStmt->bindParam(':ma_lop', $lopId);
            $pcStmt->bindParam(':ma_giao_vien', $maGiaoVien);
            $pcStmt->execute();
        }

        // Thêm lịch học
        if (!empty($lichHoc)) {
            $lichQuery = "INSERT INTO LichHoc (MaLop, ThuTrongTuan, GioBatDau, GioKetThuc)
                         VALUES (:ma_lop, :thu, :gio_bat_dau, :gio_ket_thuc)";
            $lichStmt = $db->prepare($lichQuery);

            foreach ($lichHoc as $lich) {
                $lichStmt->bindParam(':ma_lop', $lopId);
                $lichStmt->bindParam(':thu', $lich['thu']);
                $lichStmt->bindParam(':gio_bat_dau', $lich['gio_bat_dau']);
                $lichStmt->bindParam(':gio_ket_thuc', $lich['gio_ket_thuc']);
                $lichStmt->execute();
            }
        }

        $db->commit();
        sendResponse(true, 'Tạo lớp học thành công', ['id' => $lopId]);
    } catch (PDOException $e) {
        $db->rollBack();
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// POST - Đăng ký học sinh vào lớp
if ($method === 'POST' && $action === 'dangky') {
    $data = json_decode(file_get_contents("php://input"), true);

    $maHocSinh = isset($data['ma_hoc_sinh']) ? $data['ma_hoc_sinh'] : null;
    $maLop = isset($data['ma_lop']) ? $data['ma_lop'] : null;

    if (!$maHocSinh || !$maLop) {
        sendResponse(false, 'Thiếu thông tin', null, 400);
    }

    try {
        // Sử dụng stored procedure
        $query = "CALL sp_DangKyLop(:ma_hoc_sinh, :ma_lop, @ket_qua)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':ma_hoc_sinh', $maHocSinh);
        $stmt->bindParam(':ma_lop', $maLop);
        $stmt->execute();

        // Lấy kết quả
        $result = $db->query("SELECT @ket_qua as ket_qua")->fetch();

        if (strpos($result['ket_qua'], 'thành công') !== false) {
            sendResponse(true, $result['ket_qua']);
        } else {
            sendResponse(false, $result['ket_qua'], null, 400);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

sendResponse(false, 'Action không hợp lệ', null, 400);
?>
