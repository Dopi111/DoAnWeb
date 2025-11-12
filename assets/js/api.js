/**
 * API Helper Functions
 * Kết nối Frontend với Backend API
 */

const API_BASE_URL = 'http://localhost/DoAnWeb/api';

/**
 * Hàm gọi API chung
 */
async function callAPI(endpoint, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include' // Để gửi cookie session
        };

        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
        const result = await response.json();

        return result;
    } catch (error) {
        console.error('API Error:', error);
        return {
            success: false,
            message: 'Lỗi kết nối đến server'
        };
    }
}

/**
 * Authentication APIs
 */
const AuthAPI = {
    // Đăng nhập
    async login(email, password) {
        return await callAPI('/auth.php?action=login', 'POST', { email, password });
    },

    // Đăng xuất
    async logout() {
        return await callAPI('/auth.php?action=logout', 'POST');
    },

    // Kiểm tra session
    async checkSession() {
        return await callAPI('/auth.php?action=check', 'GET');
    }
};

/**
 * Học sinh APIs
 */
const HocSinhAPI = {
    // Lấy danh sách
    async getAll(search = '', page = 1) {
        return await callAPI(`/hocsinh.php?search=${search}&page=${page}`, 'GET');
    },

    // Lấy chi tiết
    async getDetail(id) {
        return await callAPI(`/hocsinh.php?action=detail&id=${id}`, 'GET');
    },

    // Lấy danh sách lớp của học sinh
    async getLop(id) {
        return await callAPI(`/hocsinh.php?action=lop&id=${id}`, 'GET');
    },

    // Thêm mới
    async create(data) {
        return await callAPI('/hocsinh.php?action=create', 'POST', data);
    },

    // Cập nhật
    async update(data) {
        return await callAPI('/hocsinh.php?action=update', 'PUT', data);
    },

    // Xóa
    async delete(id) {
        return await callAPI(`/hocsinh.php?action=delete&id=${id}`, 'DELETE');
    }
};

/**
 * Lớp học APIs
 */
const LopHocAPI = {
    // Lấy danh sách
    async getAll() {
        return await callAPI('/lophoc.php', 'GET');
    },

    // Lấy chi tiết
    async getDetail(id) {
        return await callAPI(`/lophoc.php?action=detail&id=${id}`, 'GET');
    },

    // Lấy danh sách học sinh trong lớp
    async getHocSinh(id) {
        return await callAPI(`/lophoc.php?action=hocsinh&id=${id}`, 'GET');
    },

    // Tạo lớp mới
    async create(data) {
        return await callAPI('/lophoc.php?action=create', 'POST', data);
    },

    // Đăng ký học sinh vào lớp
    async dangKy(maHocSinh, maLop) {
        return await callAPI('/lophoc.php?action=dangky', 'POST', {
            ma_hoc_sinh: maHocSinh,
            ma_lop: maLop
        });
    }
};

/**
 * Điểm APIs
 */
const DiemAPI = {
    // Xem điểm của học sinh
    async getByHocSinh(id) {
        return await callAPI(`/diem.php?action=hocsinh&id=${id}`, 'GET');
    },

    // Xem điểm theo lớp
    async getByLop(id) {
        return await callAPI(`/diem.php?action=lop&id=${id}`, 'GET');
    },

    // Nhập điểm
    async create(data) {
        return await callAPI('/diem.php?action=create', 'POST', data);
    },

    // Cập nhật điểm
    async update(data) {
        return await callAPI('/diem.php?action=update', 'PUT', data);
    }
};

/**
 * Học phí APIs
 */
const HocPhiAPI = {
    // Xem học phí của học sinh
    async getByHocSinh(id) {
        return await callAPI(`/hocphi.php?action=hocsinh&id=${id}`, 'GET');
    },

    // Xem học phí chưa đóng
    async getChuaDong() {
        return await callAPI('/hocphi.php?action=chuadong', 'GET');
    },

    // Lấy tất cả
    async getAll() {
        return await callAPI('/hocphi.php', 'GET');
    },

    // Tạo hóa đơn
    async create(data) {
        return await callAPI('/hocphi.php?action=create', 'POST', data);
    },

    // Xác nhận thanh toán
    async thanhToan(maHocPhi, phuongThuc) {
        return await callAPI('/hocphi.php?action=thanhtoan', 'PUT', {
            ma_hoc_phi: maHocPhi,
            phuong_thuc: phuongThuc
        });
    },

    // Thống kê
    async thongKe() {
        return await callAPI('/hocphi.php?action=thongke', 'GET');
    }
};

/**
 * Helper Functions
 */

// Hiển thị thông báo
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Thêm vào đầu body hoặc container
    const container = document.querySelector('.main-content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Hiển thị loading
function showLoading() {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-overlay';
    loadingDiv.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    document.body.appendChild(loadingDiv);
}

function hideLoading() {
    const loadingDiv = document.getElementById('loading-overlay');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// Format tiền VNĐ
function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Format ngày
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

// Format datetime
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return '';
    const date = new Date(dateTimeString);
    return date.toLocaleString('vi-VN');
}

// Kiểm tra đăng nhập
async function checkAuth() {
    const result = await AuthAPI.checkSession();
    if (!result.success) {
        window.location.href = 'DangNhap(chung).html';
    }
    return result.data;
}

// Xử lý đăng xuất
async function handleLogout() {
    if (confirm('Bạn có chắc muốn đăng xuất?')) {
        const result = await AuthAPI.logout();
        if (result.success) {
            window.location.href = 'DangNhap(chung).html';
        }
    }
}

// Export để sử dụng ở các file khác
window.API = {
    Auth: AuthAPI,
    HocSinh: HocSinhAPI,
    LopHoc: LopHocAPI,
    Diem: DiemAPI,
    HocPhi: HocPhiAPI
};

window.Utils = {
    showAlert,
    showLoading,
    hideLoading,
    formatMoney,
    formatDate,
    formatDateTime,
    checkAuth,
    handleLogout
};
