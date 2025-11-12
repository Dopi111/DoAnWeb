<?php
/**
 * API Xác thực (Login, Logout, Check Session)
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/helpers.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// LOGIN
if ($method === 'POST' && $action === 'login') {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = isset($data['email']) ? sanitizeInput($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($email) || empty($password)) {
        sendResponse(false, 'Vui lòng nhập đầy đủ email và mật khẩu', null, 400);
    }

    try {
        $query = "SELECT MaNguoiDung, HoTen, Email, MatKhau, VaiTro, TrangThai
                  FROM NguoiDung
                  WHERE Email = :email";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            if ($user['TrangThai'] === 'NgungHoatDong') {
                sendResponse(false, 'Tài khoản đã bị khóa', null, 403);
            }

            // Verify password
            if (password_verify($password, $user['MatKhau'])) {
                // Lưu session
                $_SESSION['user_id'] = $user['MaNguoiDung'];
                $_SESSION['ho_ten'] = $user['HoTen'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['vai_tro'] = $user['VaiTro'];

                sendResponse(true, 'Đăng nhập thành công', [
                    'user' => [
                        'id' => $user['MaNguoiDung'],
                        'ho_ten' => $user['HoTen'],
                        'email' => $user['Email'],
                        'vai_tro' => $user['VaiTro']
                    ]
                ]);
            } else {
                sendResponse(false, 'Mật khẩu không đúng', null, 401);
            }
        } else {
            sendResponse(false, 'Email không tồn tại', null, 404);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi hệ thống: ' . $e->getMessage(), null, 500);
    }
}

// LOGOUT
if ($method === 'POST' && $action === 'logout') {
    session_unset();
    session_destroy();
    sendResponse(true, 'Đăng xuất thành công');
}

// CHECK SESSION
if ($method === 'GET' && $action === 'check') {
    if (isLoggedIn()) {
        sendResponse(true, 'Đã đăng nhập', getCurrentUser());
    } else {
        sendResponse(false, 'Chưa đăng nhập', null, 401);
    }
}

// REGISTER (Chỉ admin mới được tạo tài khoản)
if ($method === 'POST' && $action === 'register') {
    requireRole(['Admin']);

    $data = json_decode(file_get_contents("php://input"), true);

    $hoTen = isset($data['ho_ten']) ? sanitizeInput($data['ho_ten']) : '';
    $email = isset($data['email']) ? sanitizeInput($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $soDienThoai = isset($data['so_dien_thoai']) ? sanitizeInput($data['so_dien_thoai']) : '';
    $diaChi = isset($data['dia_chi']) ? sanitizeInput($data['dia_chi']) : '';
    $vaiTro = isset($data['vai_tro']) ? $data['vai_tro'] : '';

    // Validate
    if (empty($hoTen) || empty($email) || empty($password) || empty($vaiTro)) {
        sendResponse(false, 'Vui lòng nhập đầy đủ thông tin bắt buộc', null, 400);
    }

    if (!isValidEmail($email)) {
        sendResponse(false, 'Email không hợp lệ', null, 400);
    }

    if (!in_array($vaiTro, ['HocSinh', 'PhuHuynh', 'GiaoVien', 'Admin'])) {
        sendResponse(false, 'Vai trò không hợp lệ', null, 400);
    }

    try {
        // Kiểm tra email đã tồn tại
        $checkQuery = "SELECT MaNguoiDung FROM NguoiDung WHERE Email = :email";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            sendResponse(false, 'Email đã được sử dụng', null, 409);
        }

        // Hash password
        $hashedPassword = hashPassword($password);

        // Insert người dùng
        $insertQuery = "INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai, DiaChi, VaiTro)
                        VALUES (:ho_ten, :email, :mat_khau, :so_dien_thoai, :dia_chi, :vai_tro)";

        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bindParam(':ho_ten', $hoTen);
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':mat_khau', $hashedPassword);
        $insertStmt->bindParam(':so_dien_thoai', $soDienThoai);
        $insertStmt->bindParam(':dia_chi', $diaChi);
        $insertStmt->bindParam(':vai_tro', $vaiTro);

        if ($insertStmt->execute()) {
            $userId = $db->lastInsertId();

            // Tạo record cho vai trò cụ thể
            if ($vaiTro === 'HocSinh') {
                $roleQuery = "INSERT INTO HocSinh (MaHocSinh) VALUES (:id)";
            } elseif ($vaiTro === 'PhuHuynh') {
                $roleQuery = "INSERT INTO PhuHuynh (MaPhuHuynh) VALUES (:id)";
            } elseif ($vaiTro === 'GiaoVien') {
                $roleQuery = "INSERT INTO GiaoVien (MaGiaoVien) VALUES (:id)";
            } elseif ($vaiTro === 'Admin') {
                $roleQuery = "INSERT INTO Admin (MaAdmin) VALUES (:id)";
            }

            $roleStmt = $db->prepare($roleQuery);
            $roleStmt->bindParam(':id', $userId);
            $roleStmt->execute();

            sendResponse(true, 'Tạo tài khoản thành công', ['user_id' => $userId]);
        } else {
            sendResponse(false, 'Không thể tạo tài khoản', null, 500);
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Lỗi hệ thống: ' . $e->getMessage(), null, 500);
    }
}

sendResponse(false, 'Action không hợp lệ', null, 400);
?>
