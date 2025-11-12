<?php
/**
 * API Quản lý Học phí
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

// GET - Xem học phí
if ($method === 'GET') {
    try {
        if ($action === 'hocsinh' && isset($_GET['id'])) {
            // Xem học phí của học sinh
            $id = $_GET['id'];

            // Kiểm tra quyền
            if (hasRole(['HocSinh', 'PhuHuynh']) && $_SESSION['user_id'] != $id) {
                // Nếu là phụ huynh, kiểm tra có phải con của họ không
                if (hasRole(['PhuHuynh'])) {
                    $checkQuery = "SELECT MaHocSinh FROM HocSinh WHERE MaPhuHuynh = :ma_ph AND MaHocSinh = :ma_hs";
                    $checkStmt = $db->prepare($checkQuery);
                    $checkStmt->bindParam(':ma_ph', $_SESSION['user_id']);
                    $checkStmt->bindParam(':ma_hs', $id);
                    $checkStmt->execute();

                    if ($checkStmt->rowCount() == 0) {
                        sendResponse(false, 'Bạn không có quyền xem thông tin này', null, 403);
                    }
                } else {
                    sendResponse(false, 'Bạn không có quyền xem thông tin này', null, 403);
                }
            }

            $query = "SELECT
                        hp.MaHocPhi,
                        kh.TenKhoaHoc,
                        hp.SoTien,
                        hp.NgayTaoBill,
                        hp.HanDong,
                        hp.NgayDong,
                        hp.TrangThai,
                        hp.PhuongThucThanhToan,
                        hp.GhiChu
                      FROM HocPhi hp
                      JOIN KhoaHoc kh ON hp.MaKhoaHoc = kh.MaKhoaHoc
                      WHERE hp.MaHocSinh = :id
                      ORDER BY hp.NgayTaoBill DESC";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        } elseif ($action === 'chuadong') {
            // Danh sách học phí chưa đóng (Admin)
            requireRole(['Admin']);

            $query = "SELECT
                        hp.MaHocPhi,
                        nd.HoTen as TenHocSinh,
                        nd.Email,
                        nd.SoDienThoai,
                        kh.TenKhoaHoc,
                        hp.SoTien,
                        hp.HanDong,
                        hp.TrangThai,
                        ph.HoTen as TenPhuHuynh,
                        ph.SoDienThoai as SDTPhuHuynh
                      FROM HocPhi hp
                      JOIN HocSinh hs ON hp.MaHocSinh = hs.MaHocSinh
                      JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                      LEFT JOIN NguoiDung ph ON hs.MaPhuHuynh = ph.MaNguoiDung
                      JOIN KhoaHoc kh ON hp.MaKhoaHoc = kh.MaKhoaHoc
                      WHERE hp.TrangThai IN ('ChuaDong', 'QuaHan')
                      ORDER BY hp.HanDong";

            $stmt = $db->prepare($query);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        } else {
            // Tất cả học phí (Admin)
            requireRole(['Admin']);

            $query = "SELECT
                        hp.MaHocPhi,
                        nd.HoTen as TenHocSinh,
                        kh.TenKhoaHoc,
                        hp.SoTien,
                        hp.NgayTaoBill,
                        hp.HanDong,
                        hp.NgayDong,
                        hp.TrangThai
                      FROM HocPhi hp
                      JOIN HocSinh hs ON hp.MaHocSinh = hs.MaHocSinh
                      JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                      JOIN KhoaHoc kh ON hp.MaKhoaHoc = kh.MaKhoaHoc
                      ORDER BY hp.NgayTaoBill DESC";

            $stmt = $db->prepare($query);
            $stmt->execute();

            sendResponse(true, 'Thành công', $stmt->fetchAll());
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// POST - Tạo hóa đơn học phí
if ($method === 'POST' && $action === 'create') {
    requireRole(['Admin']);

    $data = json_decode(file_get_contents("php://input"), true);

    $maHocSinh = isset($data['ma_hoc_sinh']) ? $data['ma_hoc_sinh'] : null;
    $maKhoaHoc = isset($data['ma_khoa_hoc']) ? $data['ma_khoa_hoc'] : null;
    $hanDong = isset($data['han_dong']) ? $data['han_dong'] : date('Y-m-d', strtotime('+7 days'));

    if (!$maHocSinh || !$maKhoaHoc) {
        sendResponse(false, 'Thiếu thông tin', null, 400);
    }

    try {
        // Sử dụng stored procedure
        $query = "CALL sp_TaoHoaDonHocPhi(:ma_hoc_sinh, :ma_khoa_hoc, :han_dong)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':ma_hoc_sinh', $maHocSinh);
        $stmt->bindParam(':ma_khoa_hoc', $maKhoaHoc);
        $stmt->bindParam(':han_dong', $hanDong);

        if ($stmt->execute()) {
            sendResponse(true, 'Tạo hóa đơn thành công', ['id' => $db->lastInsertId()]);
        } else {
            sendResponse(false, 'Không thể tạo hóa đơn', null, 500);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// PUT - Cập nhật trạng thái thanh toán
if ($method === 'PUT' && $action === 'thanhtoan') {
    requireRole(['Admin']);

    $data = json_decode(file_get_contents("php://input"), true);

    $maHocPhi = isset($data['ma_hoc_phi']) ? $data['ma_hoc_phi'] : null;
    $phuongThuc = isset($data['phuong_thuc']) ? sanitizeInput($data['phuong_thuc']) : 'Tiền mặt';

    if (!$maHocPhi) {
        sendResponse(false, 'Thiếu mã học phí', null, 400);
    }

    try {
        $query = "UPDATE HocPhi
                 SET TrangThai = 'DaDong',
                     NgayDong = CURDATE(),
                     PhuongThucThanhToan = :phuong_thuc,
                     NhanVienThuPhi = :nhan_vien
                 WHERE MaHocPhi = :ma_hoc_phi";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':ma_hoc_phi', $maHocPhi);
        $stmt->bindParam(':phuong_thuc', $phuongThuc);
        $stmt->bindParam(':nhan_vien', $_SESSION['user_id']);

        if ($stmt->execute()) {
            sendResponse(true, 'Xác nhận thanh toán thành công');
        } else {
            sendResponse(false, 'Không thể cập nhật', null, 500);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// GET - Thống kê học phí
if ($method === 'GET' && $action === 'thongke') {
    requireRole(['Admin']);

    try {
        $query = "SELECT
                    COUNT(*) as TongHoaDon,
                    SUM(CASE WHEN TrangThai = 'DaDong' THEN SoTien ELSE 0 END) as DaThu,
                    SUM(CASE WHEN TrangThai = 'ChuaDong' THEN SoTien ELSE 0 END) as ChuaThu,
                    SUM(CASE WHEN TrangThai = 'QuaHan' THEN SoTien ELSE 0 END) as QuaHan,
                    SUM(SoTien) as TongCong
                  FROM HocPhi";

        $stmt = $db->prepare($query);
        $stmt->execute();

        sendResponse(true, 'Thành công', $stmt->fetch());
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

sendResponse(false, 'Action không hợp lệ', null, 400);
?>
