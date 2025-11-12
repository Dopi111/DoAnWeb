/**
 * Dashboard Router & Controller
 * Quản lý routing và load modules động
 */

class DashboardApp {
    constructor() {
        this.currentUser = null;
        this.currentModule = 'home';
        this.modules = {};
        this.init();
    }

    async init() {
        // Check authentication
        await this.checkAuth();

        // Load user info
        this.loadUserInfo();

        // Setup sidebar
        this.setupSidebar();

        // Load home module
        this.loadModule('home');

        // Setup event listeners
        this.setupEventListeners();
    }

    async checkAuth() {
        const result = await API.Auth.checkSession();
        if (!result.success) {
            window.location.href = 'DangNhap(chung).html';
            return;
        }

        this.currentUser = result.data;
        localStorage.setItem('user', JSON.stringify(result.data));
    }

    loadUserInfo() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        if (!user.ho_ten) {
            window.location.href = 'DangNhap(chung).html';
            return;
        }

        this.currentUser = user;

        // Update user info in sidebar
        document.getElementById('userName').textContent = user.ho_ten;
        document.getElementById('userRole').textContent = this.getRoleText(user.vai_tro);
        document.getElementById('userAvatar').textContent = user.ho_ten.charAt(0).toUpperCase();
    }

    getRoleText(role) {
        const roles = {
            'Admin': 'Quản trị viên',
            'GiaoVien': 'Giáo viên',
            'HocSinh': 'Học sinh',
            'PhuHuynh': 'Phụ huynh'
        };
        return roles[role] || role;
    }

    setupSidebar() {
        const role = this.currentUser.vai_tro;
        const sidebarNav = document.getElementById('sidebarNav');

        const menus = {
            'Admin': [
                { icon: 'house', text: 'Trang chủ', module: 'home' },
                { icon: 'people', text: 'Quản lý học sinh', module: 'hocsinh' },
                { icon: 'person-video3', text: 'Quản lý giáo viên', module: 'giaovien' },
                { icon: 'book', text: 'Quản lý khóa học', module: 'khoahoc' },
                { icon: 'door-open', text: 'Quản lý lớp học', module: 'lophoc' },
                { icon: 'cash-coin', text: 'Quản lý học phí', module: 'hocphi' },
                { icon: 'calendar-check', text: 'Điểm danh', module: 'diemdanh' },
                { icon: 'bell', text: 'Gửi thông báo', module: 'thongbao' },
                { icon: 'bar-chart', text: 'Báo cáo thống kê', module: 'baocao' }
            ],
            'GiaoVien': [
                { icon: 'house', text: 'Trang chủ', module: 'home' },
                { icon: 'door-open', text: 'Lớp học của tôi', module: 'lophoc' },
                { icon: 'clipboard-check', text: 'Nhập điểm', module: 'nhapdiem' },
                { icon: 'calendar-check', text: 'Điểm danh', module: 'diemdanh' },
                { icon: 'file-text', text: 'Bài tập', module: 'baitap' },
                { icon: 'people', text: 'Học sinh', module: 'hocsinh' }
            ],
            'HocSinh': [
                { icon: 'house', text: 'Trang chủ', module: 'home' },
                { icon: 'book', text: 'Khóa học của tôi', module: 'khoahoc' },
                { icon: 'trophy', text: 'Xem điểm', module: 'xemdiem' },
                { icon: 'file-earmark-text', text: 'Bài tập', module: 'baitap' },
                { icon: 'cash', text: 'Học phí', module: 'hocphi' },
                { icon: 'bell', text: 'Thông báo', module: 'thongbao' }
            ],
            'PhuHuynh': [
                { icon: 'house', text: 'Trang chủ', module: 'home' },
                { icon: 'people', text: 'Con em', module: 'hocsinh' },
                { icon: 'trophy', text: 'Kết quả học tập', module: 'xemdiem' },
                { icon: 'book', text: 'Đăng ký khóa học', module: 'dangky' },
                { icon: 'cash', text: 'Học phí', module: 'hocphi' },
                { icon: 'bell', text: 'Thông báo', module: 'thongbao' }
            ]
        };

        const menuItems = menus[role] || menus['HocSinh'];

        sidebarNav.innerHTML = menuItems.map(item => `
            <li class="nav-item">
                <a class="nav-link" href="#" data-module="${item.module}">
                    <i class="bi bi-${item.icon}"></i>
                    ${item.text}
                </a>
            </li>
        `).join('');

        // Set active for first item
        sidebarNav.querySelector('.nav-link').classList.add('active');
    }

    setupEventListeners() {
        // Navigation
        document.getElementById('sidebarNav').addEventListener('click', (e) => {
            e.preventDefault();
            const link = e.target.closest('.nav-link');
            if (link) {
                const module = link.dataset.module;
                this.loadModule(module);

                // Update active state
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', async (e) => {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
                await API.Auth.logout();
                localStorage.removeItem('user');
                window.location.href = 'DangNhap(chung).html';
            }
        });
    }

    async loadModule(moduleName) {
        this.currentModule = moduleName;
        const contentArea = document.getElementById('mainContent');

        // Show loading
        contentArea.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-3">Đang tải...</p></div>';

        try {
            // Load module content
            let content = '';

            switch(moduleName) {
                case 'home':
                    content = await this.loadHomeModule();
                    break;
                case 'hocsinh':
                    content = await this.loadHocSinhModule();
                    break;
                case 'lophoc':
                    content = await this.loadLopHocModule();
                    break;
                case 'nhapdiem':
                    content = await this.loadNhapDiemModule();
                    break;
                case 'xemdiem':
                    content = await this.loadXemDiemModule();
                    break;
                case 'hocphi':
                    content = await this.loadHocPhiModule();
                    break;
                case 'dangky':
                    content = await this.loadDangKyModule();
                    break;
                default:
                    content = '<div class="alert alert-info">Module đang được phát triển...</div>';
            }

            contentArea.innerHTML = content;

            // Initialize module-specific scripts
            this.initModuleScripts(moduleName);
        } catch (error) {
            console.error('Error loading module:', error);
            contentArea.innerHTML = '<div class="alert alert-danger">Lỗi tải module. Vui lòng thử lại.</div>';
        }
    }

    async loadHomeModule() {
        const role = this.currentUser.vai_tro;

        if (role === 'Admin') {
            return await this.getAdminDashboard();
        } else if (role === 'GiaoVien') {
            return await this.getGiaoVienDashboard();
        } else if (role === 'HocSinh') {
            return await this.getHocSinhDashboard();
        } else {
            return await this.getPhuHuynhDashboard();
        }
    }

    async getAdminDashboard() {
        // Fetch stats
        const stats = {
            students: 5,
            teachers: 3,
            classes: 4,
            revenue: 22300000
        };

        return `
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-0">Dashboard Quản trị</h1>
                    <p class="text-muted">Xin chào, ${this.currentUser.ho_ten}</p>
                </div>
                <div class="text-muted">
                    <i class="bi bi-calendar3 me-2"></i>
                    ${new Date().toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">${stats.students}</div>
                        <div class="stats-label">Học sinh</div>
                        <div class="stats-change positive">
                            <i class="bi bi-arrow-up"></i> 2 mới tuần này
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card success">
                        <div class="stats-number">${stats.teachers}</div>
                        <div class="stats-label">Giáo viên</div>
                        <div class="stats-change positive">
                            <i class="bi bi-check-circle"></i> Hoạt động
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card warning">
                        <div class="stats-number">${stats.classes}</div>
                        <div class="stats-label">Lớp học</div>
                        <div class="stats-change positive">
                            <i class="bi bi-arrow-up"></i> 1 lớp mới
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card danger">
                        <div class="stats-number">${(stats.revenue / 1000000).toFixed(1)}M</div>
                        <div class="stats-label">Doanh thu (VNĐ)</div>
                        <div class="stats-change positive">
                            <i class="bi bi-arrow-up"></i> +15% tháng này
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Hoạt động gần đây</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <strong>Học sinh mới đăng ký</strong>
                                    <p class="text-muted mb-0">Võ Thị Lan đăng ký khóa Giao tiếp Cơ bản</p>
                                    <small class="text-muted">2 giờ trước</small>
                                </div>
                                <div class="timeline-item">
                                    <strong>Thanh toán học phí</strong>
                                    <p class="text-muted mb-0">Phạm Thu Hằng đã đóng học phí 5,500,000₫</p>
                                    <small class="text-muted">5 giờ trước</small>
                                </div>
                                <div class="timeline-item">
                                    <strong>Lớp học mới</strong>
                                    <p class="text-muted mb-0">Tạo lớp IELTS 7.0+ (Sáng T2-4-6)</p>
                                    <small class="text-muted">1 ngày trước</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Học phí chưa đóng</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>1 học sinh</strong> chưa đóng học phí
                            </div>
                            <button class="btn btn-primary w-100" onclick="app.loadModule('hocphi')">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    async getGiaoVienDashboard() {
        return `
            <h1 class="h3 fw-bold mb-4">Dashboard Giáo viên</h1>
            <p>Xin chào, ${this.currentUser.ho_ten}</p>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Module dashboard giáo viên đang được phát triển...
            </div>
        `;
    }

    async getHocSinhDashboard() {
        return `
            <h1 class="h3 fw-bold mb-4">Dashboard Học sinh</h1>
            <p>Xin chào, ${this.currentUser.ho_ten}</p>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Module dashboard học sinh đang được phát triển...
            </div>
        `;
    }

    async getPhuHuynhDashboard() {
        return `
            <h1 class="h3 fw-bold mb-4">Dashboard Phụ huynh</h1>
            <p>Xin chào, ${this.currentUser.ho_ten}</p>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Module dashboard phụ huynh đang được phát triển...
            </div>
        `;
    }

    async loadHocSinhModule() {
        return '<div class="alert alert-info">Module quản lý học sinh đang được phát triển...</div>';
    }

    async loadLopHocModule() {
        // Load from QLLop(Admin).html content
        return await fetch('modules/lophoc.html').then(r => r.text()).catch(() => {
            return '<div class="alert alert-info">Module quản lý lớp học đang được phát triển...</div>';
        });
    }

    async loadNhapDiemModule() {
        return await fetch('modules/nhapdiem.html').then(r => r.text()).catch(() => {
            return '<div class="alert alert-info">Module nhập điểm đang được phát triển...</div>';
        });
    }

    async loadXemDiemModule() {
        return await fetch('modules/xemdiem.html').then(r => r.text()).catch(() => {
            return '<div class="alert alert-info">Module xem điểm đang được phát triển...</div>';
        });
    }

    async loadHocPhiModule() {
        return '<div class="alert alert-info">Module học phí đang được phát triển...</div>';
    }

    async loadDangKyModule() {
        return await fetch('modules/dangky.html').then(r => r.text()).catch(() => {
            return '<div class="alert alert-info">Module đăng ký khóa học đang được phát triển...</div>';
        });
    }

    initModuleScripts(moduleName) {
        // Initialize specific scripts for each module
        console.log('Initializing scripts for:', moduleName);
    }
}

// Initialize app when DOM is ready
let app;
document.addEventListener('DOMContentLoaded', () => {
    app = new DashboardApp();
});
