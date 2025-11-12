<?php
/**
 * API Quản lý Học sinh
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

// GET - Lấy danh sách hoặc chi tiết học sinh
if ($method === 'GET') {
    try {
        if ($action === 'detail' && isset($_GET['id'])) {
            // Chi tiết 1 học sinh
            $id = $_GET['id'];

            $query = "SELECT
                        hs.MaHocSinh,
                        nd.HoTen,
                        nd.Email,
                        nd.SoDienThoai,
                        nd.DiaChi,
                        hs.NgaySinh,
                        hs.GioiTinh,
                        hs.LopHienTai,
                        hs.TruongHoc,
                        ph.HoTen as TenPhuHuynh,
                        ph.Email as EmailPhuHuynh,
                        ph.SoDienThoai as SDTPhuHuynh,
                        nd.TrangThai
                      FROM HocSinh hs
                      JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                      LEFT JOIN NguoiDung ph ON hs.MaPhuHuynh = ph.MaNguoiDung
                      WHERE hs.MaHocSinh = :id";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $hocSinh = $stmt->fetch();
                sendResponse(true, 'Thành công', $hocSinh);
            } else {
                sendResponse(false, 'Không tìm thấy học sinh', null, 404);
            }
        } elseif ($action === 'lop' && isset($_GET['id'])) {
            // Lấy danh sách lớp của học sinh
            $id = $_GET['id'];

            $query = "SELECT
                        lh.MaLop,
                        lh.TenLop,
                        kh.TenKhoaHoc,
                        lh.PhongHoc,
                        lh.NgayBatDau,
                        lh.NgayKetThuc,
                        lh.TrangThai,
                        dkl.NgayDangKy,
                        dkl.TrangThai as TrangThaiDangKy,
                        gv.HoTen as GiaoVien
                      FROM DangKyLop dkl
                      JOIN LopHoc lh ON dkl.MaLop = lh.MaLop
                      JOIN KhoaHoc kh ON lh.MaKhoaHoc = kh.MaKhoaHoc
                      LEFT JOIN PhanCongGiaoVien pc ON lh.MaLop = pc.MaLop
                      LEFT JOIN NguoiDung gv ON pc.MaGiaoVien = gv.MaNguoiDung
                      WHERE dkl.MaHocSinh = :id AND dkl.TrangThai != 'DaHuy'
                      ORDER BY lh.NgayBatDau DESC";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $danhSach = $stmt->fetchAll();
            sendResponse(true, 'Thành công', $danhSach);
        } else {
            // Danh sách tất cả học sinh
            requireRole(['Admin', 'GiaoVien']);

            $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%%';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
            $offset = ($page - 1) * $perPage;

            $query = "SELECT
                        hs.MaHocSinh,
                        nd.HoTen,
                        nd.Email,
                        nd.SoDienThoai,
                        hs.NgaySinh,
                        hs.GioiTinh,
                        hs.LopHienTai,
                        hs.TruongHoc,
                        ph.HoTen as TenPhuHuynh,
                        nd.TrangThai
                      FROM HocSinh hs
                      JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                      LEFT JOIN NguoiDung ph ON hs.MaPhuHuynh = ph.MaNguoiDung
                      WHERE (nd.HoTen LIKE :search OR nd.Email LIKE :search)
                      ORDER BY nd.NgayTao DESC
                      LIMIT :limit OFFSET :offset";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':search', $search);
            $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $danhSach = $stmt->fetchAll();

            // Đếm tổng số
            $countQuery = "SELECT COUNT(*) as total
                          FROM HocSinh hs
                          JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
                          WHERE (nd.HoTen LIKE :search OR nd.Email LIKE :search)";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':search', $search);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];

            sendResponse(true, 'Thành công', [
                'data' => $danhSach,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// POST - Thêm học sinh mới
if ($method === 'POST' && $action === 'create') {
    requireRole(['Admin']);

    $data = json_decode(file_get_contents("php://input"), true);

    $hoTen = isset($data['ho_ten']) ? sanitizeInput($data['ho_ten']) : '';
    $email = isset($data['email']) ? sanitizeInput($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '123456'; // Default password
    $soDienThoai = isset($data['so_dien_thoai']) ? sanitizeInput($data['so_dien_thoai']) : '';
    $diaChi = isset($data['dia_chi']) ? sanitizeInput($data['dia_chi']) : '';
    $ngaySinh = isset($data['ngay_sinh']) ? $data['ngay_sinh'] : null;
    $gioiTinh = isset($data['gioi_tinh']) ? $data['gioi_tinh'] : null;
    $lopHienTai = isset($data['lop_hien_tai']) ? sanitizeInput($data['lop_hien_tai']) : '';
    $truongHoc = isset($data['truong_hoc']) ? sanitizeInput($data['truong_hoc']) : '';
    $maPhuHuynh = isset($data['ma_phu_huynh']) ? $data['ma_phu_huynh'] : null;

    if (empty($hoTen) || empty($email)) {
        sendResponse(false, 'Vui lòng nhập đầy đủ họ tên và email', null, 400);
    }

    try {
        // Kiểm tra email
        $checkQuery = "SELECT MaNguoiDung FROM NguoiDung WHERE Email = :email";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            sendResponse(false, 'Email đã được sử dụng', null, 409);
        }

        $db->beginTransaction();

        // Insert NguoiDung
        $hashedPassword = hashPassword($password);
        $insertUserQuery = "INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai, DiaChi, VaiTro)
                           VALUES (:ho_ten, :email, :mat_khau, :so_dien_thoai, :dia_chi, 'HocSinh')";

        $stmt = $db->prepare($insertUserQuery);
        $stmt->bindParam(':ho_ten', $hoTen);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mat_khau', $hashedPassword);
        $stmt->bindParam(':so_dien_thoai', $soDienThoai);
        $stmt->bindParam(':dia_chi', $diaChi);
        $stmt->execute();

        $userId = $db->lastInsertId();

        // Insert HocSinh
        $insertHSQuery = "INSERT INTO HocSinh (MaHocSinh, MaPhuHuynh, NgaySinh, GioiTinh, LopHienTai, TruongHoc)
                         VALUES (:id, :ma_phu_huynh, :ngay_sinh, :gioi_tinh, :lop_hien_tai, :truong_hoc)";

        $stmt2 = $db->prepare($insertHSQuery);
        $stmt2->bindParam(':id', $userId);
        $stmt2->bindParam(':ma_phu_huynh', $maPhuHuynh);
        $stmt2->bindParam(':ngay_sinh', $ngaySinh);
        $stmt2->bindParam(':gioi_tinh', $gioiTinh);
        $stmt2->bindParam(':lop_hien_tai', $lopHienTai);
        $stmt2->bindParam(':truong_hoc', $truongHoc);
        $stmt2->execute();

        $db->commit();

        sendResponse(true, 'Thêm học sinh thành công', ['id' => $userId]);
    } catch (PDOException $e) {
        $db->rollBack();
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// PUT - Cập nhật thông tin học sinh
if ($method === 'PUT' && $action === 'update') {
    requireRole(['Admin']);

    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? $data['id'] : null;

    if (!$id) {
        sendResponse(false, 'Thiếu ID học sinh', null, 400);
    }

    try {
        $db->beginTransaction();

        // Update NguoiDung
        if (isset($data['ho_ten']) || isset($data['so_dien_thoai']) || isset($data['dia_chi'])) {
            $updates = [];
            $params = [':id' => $id];

            if (isset($data['ho_ten'])) {
                $updates[] = "HoTen = :ho_ten";
                $params[':ho_ten'] = sanitizeInput($data['ho_ten']);
            }
            if (isset($data['so_dien_thoai'])) {
                $updates[] = "SoDienThoai = :so_dien_thoai";
                $params[':so_dien_thoai'] = sanitizeInput($data['so_dien_thoai']);
            }
            if (isset($data['dia_chi'])) {
                $updates[] = "DiaChi = :dia_chi";
                $params[':dia_chi'] = sanitizeInput($data['dia_chi']);
            }

            $updateUserQuery = "UPDATE NguoiDung SET " . implode(', ', $updates) . " WHERE MaNguoiDung = :id";
            $stmt = $db->prepare($updateUserQuery);
            $stmt->execute($params);
        }

        // Update HocSinh
        if (isset($data['ngay_sinh']) || isset($data['gioi_tinh']) || isset($data['lop_hien_tai']) || isset($data['truong_hoc'])) {
            $updates = [];
            $params = [':id' => $id];

            if (isset($data['ngay_sinh'])) {
                $updates[] = "NgaySinh = :ngay_sinh";
                $params[':ngay_sinh'] = $data['ngay_sinh'];
            }
            if (isset($data['gioi_tinh'])) {
                $updates[] = "GioiTinh = :gioi_tinh";
                $params[':gioi_tinh'] = $data['gioi_tinh'];
            }
            if (isset($data['lop_hien_tai'])) {
                $updates[] = "LopHienTai = :lop_hien_tai";
                $params[':lop_hien_tai'] = sanitizeInput($data['lop_hien_tai']);
            }
            if (isset($data['truong_hoc'])) {
                $updates[] = "TruongHoc = :truong_hoc";
                $params[':truong_hoc'] = sanitizeInput($data['truong_hoc']);
            }

            $updateHSQuery = "UPDATE HocSinh SET " . implode(', ', $updates) . " WHERE MaHocSinh = :id";
            $stmt2 = $db->prepare($updateHSQuery);
            $stmt2->execute($params);
        }

        $db->commit();
        sendResponse(true, 'Cập nhật thành công');
    } catch (PDOException $e) {
        $db->rollBack();
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

// DELETE - Xóa học sinh
if ($method === 'DELETE' && $action === 'delete') {
    requireRole(['Admin']);

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        sendResponse(false, 'Thiếu ID học sinh', null, 400);
    }

    try {
        // Xóa NguoiDung (cascade sẽ xóa HocSinh)
        $query = "DELETE FROM NguoiDung WHERE MaNguoiDung = :id AND VaiTro = 'HocSinh'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            sendResponse(true, 'Xóa học sinh thành công');
        } else {
            sendResponse(false, 'Không thể xóa học sinh', null, 500);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi: ' . $e->getMessage(), null, 500);
    }
}

sendResponse(false, 'Action không hợp lệ', null, 400);
?>
