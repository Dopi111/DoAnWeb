# 🎨 HƯỚNG DẪN FRONTEND - HỆ THỐNG QUẢN LÝ TRUNG TÂM DẠY THÊM

## 📋 TỔNG QUAN

Hệ thống frontend đã được tích hợp hoàn toàn với backend API, sử dụng kiến trúc Single Page Application (SPA) với routing động.

---

## 🚀 CÁCH CHẠY HỆ THỐNG

### Bước 1: Import Database
```bash
# Mở phpMyAdmin: http://localhost/phpmyadmin
# Click Import → Chọn file database_mysql.sql → Go
```

### Bước 2: Truy cập trang đăng nhập
```
URL: http://localhost/DoAnWeb/DangNhap(chung).html
```

### Bước 3: Đăng nhập với tài khoản demo

**Admin:**
- Email: `admin@englishcenter.com`
- Password: `password`

**Giáo viên:**
- Email: `binh.gv@englishcenter.com`
- Password: `password`

**Học sinh:**
- Email: `an.nguyen@email.com`
- Password: `password`

**Phụ huynh:**
- Email: `cha1@email.com`
- Password: `password`

---

## 🎯 CÁC TRANG ĐÃ HOÀN THÀNH

### 1. **Trang Đăng nhập** (`DangNhap(chung).html`)
✅ Tích hợp API login
✅ Validation form
✅ Loading state
✅ Error handling
✅ Auto redirect sau đăng nhập
✅ Click tài khoản demo để fill nhanh

**Features:**
- Gradient background đẹp
- Form validation
- Session management
- Remember me (future)

---

### 2. **Dashboard** (`Dashboard(chung).html`)

✅ SPA routing - không reload trang
✅ Sidebar động theo vai trò
✅ User info hiển thị
✅ Menu động theo quyền
✅ Module loading system

**Dashboard theo vai trò:**

#### 👨‍💼 Admin Dashboard
- 📊 Stats cards (Học sinh, Giáo viên, Lớp học, Doanh thu)
- 📈 Biểu đồ thống kê
- ⏰ Hoạt động gần đây
- ⚠️ Cảnh báo học phí chưa đóng

#### 👨‍🏫 Giáo viên Dashboard
- 📚 Lớp học của tôi
- 📝 Nhập điểm nhanh
- 📅 Lịch dạy hôm nay
- 👥 Thống kê học sinh

#### 👨‍🎓 Học sinh Dashboard
- 📖 Khóa học đang học
- 🏆 Điểm số gần nhất
- 📋 Bài tập chưa nộp
- 💰 Học phí cần đóng

#### 👨‍👩‍👧 Phụ huynh Dashboard
- 👶 Thông tin con em
- 📊 Kết quả học tập
- 💵 Học phí
- 📢 Thông báo

---

### 3. **Module Quản lý Lớp học** (`modules/lophoc.html`)

✅ Load danh sách lớp từ API
✅ Hiển thị thông tin: Tên lớp, Khóa học, Giáo viên, Sĩ số, Lịch học, Trạng thái
✅ Xem danh sách học sinh trong lớp
✅ Tạo lớp mới (Modal form)
✅ Chỉnh sửa lớp học
✅ Xóa lớp học

**API endpoints sử dụng:**
```javascript
API.LopHoc.getAll()           // Lấy tất cả lớp
API.LopHoc.getDetail(id)      // Chi tiết lớp
API.LopHoc.getHocSinh(id)     // DS học sinh trong lớp
API.LopHoc.create(data)       // Tạo lớp mới
```

**Tính năng:**
- Tìm kiếm lớp học
- Filter theo trạng thái
- Phân trang
- Export Excel (future)

---

### 4. **Module Nhập điểm** (`modules/nhapdiem.html`)

✅ Chọn lớp để nhập điểm
✅ Chọn loại điểm (Chuyên cần, Giữa kỳ, Cuối kỳ)
✅ Nhập điểm cho nhiều học sinh
✅ Nhận xét cho từng học sinh
✅ Lưu điểm lên server
✅ Auto-save (future)

**API endpoints:**
```javascript
API.LopHoc.getAll()           // Load danh sách lớp
API.Diem.getByLop(id)        // Load điểm của lớp
API.Diem.create(data)        // Nhập điểm mới
API.Diem.update(data)        // Cập nhật điểm
```

**Validation:**
- Điểm từ 0-10
- Bước nhảy 0.5
- Bắt buộc chọn lớp

---

### 5. **Module Xem điểm** (`modules/xemdiem.html`)

✅ Load điểm của học sinh đăng nhập
✅ Hiển thị: Chuyên cần, Giữa kỳ, Cuối kỳ, Tổng kết
✅ Xếp loại với badge màu
✅ Xem nhận xét của giáo viên
✅ Export PDF (future)

**API endpoints:**
```javascript
API.Diem.getByHocSinh(id)    // Lấy điểm theo học sinh
```

**Xếp loại:**
- 🏆 Xuất sắc: >= 9.0 (Green)
- ⭐ Giỏi: >= 8.0 (Blue)
- 👍 Khá: >= 6.5 (Cyan)
- 📝 Trung bình: >= 5.0 (Yellow)
- ⚠️ Yếu: < 5.0 (Gray)

---

## 📁 CẤU TRÚC FRONTEND

```
DoAnWeb/
│
├── 📄 DangNhap(chung).html          ← Trang đăng nhập
├── 📄 Dashboard(chung).html         ← Dashboard chính
│
├── 📂 assets/
│   ├── 📂 css/
│   │   ├── common.css              ← Design system chung
│   │   └── style.css               ← Custom styles
│   │
│   └── 📂 js/
│       ├── api.js                  ← API client
│       └── dashboard.js            ← Dashboard controller
│
└── 📂 modules/                      ← Các module chức năng
    ├── lophoc.html                 ← Quản lý lớp học
    ├── nhapdiem.html               ← Nhập điểm
    ├── xemdiem.html                ← Xem điểm
    ├── dangky.html                 ← Đăng ký khóa học (future)
    ├── hocphi.html                 ← Quản lý học phí (future)
    └── thongbao.html               ← Thông báo (future)
```

---

## 🎨 DESIGN SYSTEM

### Colors
```css
--primary: #0d6efd      /* Blue - Actions */
--success: #28a745      /* Green - Success */
--danger: #dc3545       /* Red - Errors */
--warning: #ffc107      /* Yellow - Warnings */
--info: #17a2b8         /* Cyan - Info */
```

### Components
- **Cards**: `class="card"`
- **Buttons**: `class="btn btn-primary"`
- **Badges**: `class="badge bg-success"`
- **Tables**: `class="table table-striped"`
- **Forms**: `class="form-control"`
- **Alerts**: `class="alert alert-success"`

### Utilities
```css
.text-center        /* Text align center */
.d-flex            /* Display flex */
.mb-3              /* Margin bottom 1.5rem */
.p-4               /* Padding 2rem */
.gap-2             /* Gap 1rem */
```

---

## 🔧 JAVASCRIPT API CLIENT

### Authentication
```javascript
// Login
const result = await API.Auth.login(email, password);

// Logout
await API.Auth.logout();

// Check session
const session = await API.Auth.checkSession();
```

### Học sinh
```javascript
// Lấy tất cả
const students = await API.HocSinh.getAll(search, page);

// Chi tiết
const student = await API.HocSinh.getDetail(id);

// Tạo mới
const result = await API.HocSinh.create(data);

// Cập nhật
await API.HocSinh.update(data);

// Xóa
await API.HocSinh.delete(id);
```

### Lớp học
```javascript
// Danh sách
const classes = await API.LopHoc.getAll();

// Học sinh trong lớp
const students = await API.LopHoc.getHocSinh(lopId);

// Tạo lớp mới
const result = await API.LopHoc.create(data);

// Đăng ký học sinh vào lớp
await API.LopHoc.dangKy(maHocSinh, maLop);
```

### Điểm
```javascript
// Xem điểm học sinh
const scores = await API.Diem.getByHocSinh(hocSinhId);

// Xem điểm theo lớp
const scores = await API.Diem.getByLop(lopId);

// Nhập điểm
await API.Diem.create(data);

// Cập nhật điểm
await API.Diem.update(data);
```

### Học phí
```javascript
// Xem học phí
const fees = await API.HocPhi.getByHocSinh(hocSinhId);

// Học phí chưa đóng
const unpaid = await API.HocPhi.getChuaDong();

// Tạo hóa đơn
await API.HocPhi.create(data);

// Xác nhận thanh toán
await API.HocPhi.thanhToan(maHocPhi, phuongThuc);

// Thống kê
const stats = await API.HocPhi.thongKe();
```

### Helper Functions
```javascript
// Hiển thị alert
Utils.showAlert('Thành công!', 'success');

// Loading
Utils.showLoading();
Utils.hideLoading();

// Format
Utils.formatMoney(5500000);      // 5,500,000 ₫
Utils.formatDate('2025-01-15');  // 15/01/2025
Utils.formatDateTime(datetime);  // 15/01/2025 14:30

// Auth helpers
await Utils.checkAuth();         // Redirect if not logged in
await Utils.handleLogout();      // Logout
```

---

## 📱 RESPONSIVE DESIGN

### Breakpoints
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

### Mobile Features
- Sidebar collapse/expand
- Touch-friendly buttons
- Responsive tables
- Mobile-optimized forms

---

## ⚡ PERFORMANCE

### Optimizations
✅ Lazy loading modules
✅ API caching (future)
✅ Debounce search (future)
✅ Virtual scrolling for long lists (future)

### Best Practices
- Minimize API calls
- Load data on demand
- Show loading states
- Handle errors gracefully

---

## 🐛 TROUBLESHOOTING

### Lỗi "API is not defined"
```javascript
// Đảm bảo đã include api.js trước
<script src="assets/js/api.js"></script>
<script src="assets/js/dashboard.js"></script>
```

### Lỗi CORS
```php
// Thêm vào đầu file PHP API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
```

### Session không hoạt động
```php
// Đảm bả có session_start() ở đầu file API
session_start();
```

---

## 🔮 FEATURES SẮP CÓ

### Module đang phát triển:
- [ ] 📝 Quản lý học sinh (CRUD đầy đủ)
- [ ] 👨‍🏫 Quản lý giáo viên (CRUD đầy đủ)
- [ ] 📚 Quản lý khóa học
- [ ] 💰 Quản lý học phí chi tiết
- [ ] ✅ Điểm danh
- [ ] 📋 Bài tập và chấm bài
- [ ] 📢 Gửi thông báo
- [ ] 📊 Báo cáo thống kê
- [ ] 📱 Mobile App (PWA)
- [ ] 🔔 Real-time notifications
- [ ] 📧 Email integration
- [ ] 💳 Payment gateway
- [ ] 📈 Analytics dashboard

---

## 💡 TIPS & TRICKS

### 1. Debug API calls
```javascript
// Mở DevTools (F12) → Console
// Test API
const result = await API.Auth.login('admin@englishcenter.com', 'password');
console.log(result);
```

### 2. Xem dữ liệu trong database
```
http://localhost/phpmyadmin
→ Database: QuanLyHocThem
→ Xem các bảng
```

### 3. Clear cache khi update code
```
Ctrl + Shift + R (Hard reload)
hoặc
Ctrl + F5
```

### 4. Test với tài khoản khác nhau
- Mở nhiều tab/trình duyệt
- Sử dụng Incognito mode
- Test từng vai trò riêng biệt

---

## 📞 SUPPORT

Nếu gặp vấn đề:
1. Kiểm tra Console (F12) xem có lỗi gì
2. Kiểm tra Network tab xem API call có thành công không
3. Kiểm tra database có dữ liệu chưa
4. Đọc lại README.md

---

## ✨ SUMMARY

🎉 **Hoàn thành:**
- ✅ Trang đăng nhập đẹp với API
- ✅ Dashboard SPA với routing
- ✅ 3 modules hoạt động: Lớp học, Nhập điểm, Xem điểm
- ✅ Design system đồng bộ
- ✅ Tích hợp hoàn toàn với backend
- ✅ Role-based access control

🚀 **Sẵn sàng sử dụng!**

Code đã được push lên branch:
```
claude/survey-feature-implementation-011CV4L5BU3qh1ipDnVx3HjW
```

**Enjoy coding! 🎨💻**
