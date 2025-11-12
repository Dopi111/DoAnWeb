<?php
/**
 * API Quản lý Điểm
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/helpers.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// GET - Xem điểm
if ($method === 'GET') {
    try {
        if ($action === 'hocsinh' && isset($_GET['id'])) {
            // Xem điểm của 1 học sinh
            $id = $_GET['id'];

            // Kiểm tra quyền: học sinh chỉ xem điểm của mình
            if (hasRole(['HocSinh']) && $_SESSION['user_id'] != $id) {
                sendResponse(false, 'Bạn không có quyền xem điểm của học sinh khác', null, 403);
            }

            $query = "SELECT
                        d.MaDiem,
                        kh.TenKhoaHoc,
                        d.DiemChuyenCan,
                        d.DiemGiuaKy,
                        d.DiemCuoiKy,
                        d.DiemTongKet,
                        d.XepLoai,
                        d.NhanXet,
                        d.NgayCapNhat
                      FROM Diem d
                      JOIN KhoaHoc kh ON d.MaKhoaHoc = kh.MaKhoaHoc
                      WHERE d.MaHocSinh = :id
                      ORDER BY d.NgayCapNhat DESC";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        } elseif ($action === 'lop' && isset($_GET['id'])) {
            // Xem điểm theo lớp (cho giáo viên)
            requireRole(['GiaoVien', 'Admin']);

            $maLop = $_GET['id'];

            $query = "SELECT
                        hs.MaHocSinh,
                        nd.HoTen,
                        nd.Email,
                        d.DiemChuyenCan,
                        d.DiemGiuaKy,
                        d.DiemCuoiKy,
                        d.DiemTongKet,
                        d.XepLoai
                      FROM DangKyLop dkl
                      JOIN HocSinh hs ON dkl.MaHocSinh = hs.MaHocSinh
                      JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                      JOIN LopHoc lh ON dkl.MaLop = lh.MaLop
                      LEFT JOIN Diem d ON hs.MaHocSinh = d.MaHocSinh AND lh.MaKhoaHoc = d.MaKhoaHoc
                      WHERE dkl.MaLop = :ma_lop AND dkl.TrangThai = 'DaXacNhan'
                      ORDER BY nd.HoTen";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':ma_lop', $maLop);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        } else {
            sendResponse(false, 'Thiếu tham số', null, 400);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// POST - Nhập điểm mới
if ($method === 'POST' && $action === 'create') {
    requireRole(['GiaoVien', 'Admin']);

    $data = json_decode(file_get_contents("php://input"), true);

    $maHocSinh = isset($data['ma_hoc_sinh']) ? $data['ma_hoc_sinh'] : null;
    $maKhoaHoc = isset($data['ma_khoa_hoc']) ? $data['ma_khoa_hoc'] : null;
    $diemChuyenCan = isset($data['diem_chuyen_can']) ? $data['diem_chuyen_can'] : null;
    $diemGiuaKy = isset($data['diem_giua_ky']) ? $data['diem_giua_ky'] : null;
    $diemCuoiKy = isset($data['diem_cuoi_ky']) ? $data['diem_cuoi_ky'] : null;
    $nhanXet = isset($data['nhan_xet']) ? sanitizeInput($data['nhan_xet']) : '';

    if (!$maHocSinh || !$maKhoaHoc) {
        sendResponse(false, 'Thiếu thông tin', null, 400);
    }

    try {
        $query = "INSERT INTO Diem (MaHocSinh, MaKhoaHoc, DiemChuyenCan, DiemGiuaKy, DiemCuoiKy, NhanXet)
                 VALUES (:ma_hoc_sinh, :ma_khoa_hoc, :diem_chuyen_can, :diem_giua_ky, :diem_cuoi_ky, :nhan_xet)
                 ON DUPLICATE KEY UPDATE
                 DiemChuyenCan = :diem_chuyen_can,
                 DiemGiuaKy = :diem_giua_ky,
                 DiemCuoiKy = :diem_cuoi_ky,
                 NhanXet = :nhan_xet";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':ma_hoc_sinh', $maHocSinh);
        $stmt->bindParam(':ma_khoa_hoc', $maKhoaHoc);
        $stmt->bindParam(':diem_chuyen_can', $diemChuyenCan);
        $stmt->bindParam(':diem_giua_ky', $diemGiuaKy);
        $stmt->bindParam(':diem_cuoi_ky', $diemCuoiKy);
        $stmt->bindParam(':nhan_xet', $nhanXet);

        if ($stmt->execute()) {
            sendResponse(true, 'Nhập điểm thành công');
        } else {
            sendResponse(false, 'Không thể nhập điểm', null, 500);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// PUT - Cập nhật điểm
if ($method === 'PUT' && $action === 'update') {
    requireRole(['GiaoVien', 'Admin']);

    $data = json_decode(file_get_contents("php://input"), true);
    $maDiem = isset($data['ma_diem']) ? $data['ma_diem'] : null;

    if (!$maDiem) {
        sendResponse(false, 'Thiếu mã điểm', null, 400);
    }

    try {
        $updates = [];
        $params = [':ma_diem' => $maDiem];

        if (isset($data['diem_chuyen_can'])) {
            $updates[] = "DiemChuyenCan = :diem_chuyen_can";
            $params[':diem_chuyen_can'] = $data['diem_chuyen_can'];
        }
        if (isset($data['diem_giua_ky'])) {
            $updates[] = "DiemGiuaKy = :diem_giua_ky";
            $params[':diem_giua_ky'] = $data['diem_giua_ky'];
        }
        if (isset($data['diem_cuoi_ky'])) {
            $updates[] = "DiemCuoiKy = :diem_cuoi_ky";
            $params[':diem_cuoi_ky'] = $data['diem_cuoi_ky'];
        }
        if (isset($data['nhan_xet'])) {
            $updates[] = "NhanXet = :nhan_xet";
            $params[':nhan_xet'] = sanitizeInput($data['nhan_xet']);
        }

        if (empty($updates)) {
            sendResponse(false, 'Không có dữ liệu cập nhật', null, 400);
        }

        $query = "UPDATE Diem SET " . implode(', ', $updates) . " WHERE MaDiem = :ma_diem";
        $stmt = $db->prepare($query);

        if ($stmt->execute($params)) {
            sendResponse(true, 'Cập nhật điểm thành công');
        } else {
            sendResponse(false, 'Không thể cập nhật', null, 500);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

sendResponse(false, 'Action không hợp lệ', null, 400);
?>
