<?php
/**
 * File chứa các hàm tiện ích
 */

/**
 * Trả về JSON response
 */
function sendResponse($success, $message, $data = null, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');

    $response = [
        'success' => $success,
        'message' => $message
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate số điện thoại Việt Nam
 */
function isValidPhone($phone) {
    return preg_match('/^(0|\+84)[0-9]{9,10}$/', $phone);
}

/**
 * Sanitize input
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Hash mật khẩu
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify mật khẩu
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Kiểm tra đã đăng nhập chưa
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Yêu cầu đăng nhập
 */
function requireLogin() {
    if (!isLoggedIn()) {
        sendResponse(false, 'Vui lòng đăng nhập', null, 401);
    }
}

/**
 * Kiểm tra vai trò
 */
function hasRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }

    if (!is_array($roles)) {
        $roles = [$roles];
    }

    return in_array($_SESSION['vai_tro'], $roles);
}

/**
 * Yêu cầu vai trò cụ thể
 */
function requireRole($roles) {
    requireLogin();

    if (!hasRole($roles)) {
        sendResponse(false, 'Bạn không có quyền truy cập chức năng này', null, 403);
    }
}

/**
 * Format tiền tệ VNĐ
 */
function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

/**
 * Format ngày tháng
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    if (empty($datetime)) return '';
    return date($format, strtotime($datetime));
}

/**
 * Upload file
 */
function uploadFile($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'], $maxSize = 5242880) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Lỗi upload file'];
    }

    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'Định dạng file không được phép'];
    }

    if ($fileSize > $maxSize) {
        return ['success' => false, 'message' => 'File quá lớn (tối đa ' . ($maxSize / 1024 / 1024) . 'MB)'];
    }

    $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
    $uploadPath = '../uploads/' . $newFileName;

    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        return ['success' => true, 'filename' => $newFileName];
    } else {
        return ['success' => false, 'message' => 'Không thể lưu file'];
    }
}

/**
 * Paginate data
 */
function paginate($query, $page = 1, $perPage = 10) {
    $offset = ($page - 1) * $perPage;
    return [
        'query' => $query . " LIMIT $perPage OFFSET $offset",
        'page' => $page,
        'perPage' => $perPage
    ];
}

/**
 * Get current user info
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'ho_ten' => $_SESSION['ho_ten'],
        'email' => $_SESSION['email'],
        'vai_tro' => $_SESSION['vai_tro']
    ];
}
?>
