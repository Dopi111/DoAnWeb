# 🎓 HỆ THỐNG QUẢN LÝ TRUNG TÂM DẠY THÊM

Hệ thống quản lý toàn diện cho trung tâm dạy thêm với đầy đủ chức năng cho Admin, Giáo viên, Học sinh và Phụ huynh.

## 📋 MỤC LỤC

- [Tính năng](#-tính-năng)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
- [Hướng dẫn sử dụng](#-hướng-dẫn-sử-dụng)
- [API Documentation](#-api-documentation)
- [Tài khoản mặc định](#-tài-khoản-mặc-định)

---

## ✨ TÍNH NĂNG

### 👨‍💼 Admin
- ✅ Quản lý học sinh (CRUD)
- ✅ Quản lý giáo viên (CRUD)
- ✅ Quản lý khóa học và lớp học
- ✅ Phân công giáo viên cho lớp
- ✅ Quản lý học phí (tạo hóa đơn, xác nhận thanh toán)
- ✅ Gửi thông báo cho học sinh/phụ huynh
- ✅ Xem báo cáo thống kê
- ✅ Quản lý điểm danh

### 👨‍🏫 Giáo viên
- ✅ Xem danh sách lớp được phân công
- ✅ Điểm danh học sinh
- ✅ Nhập điểm (chuyên cần, giữa kỳ, cuối kỳ)
- ✅ Giao bài tập cho học sinh
- ✅ Chấm bài và nhận xét
- ✅ Xem thông tin học sinh trong lớp

### 👨‍🎓 Học sinh
- ✅ Đăng ký khóa học/lớp học
- ✅ Xem lịch học
- ✅ Xem điểm số và xếp loại
- ✅ Xem bài tập và nộp bài
- ✅ Xem thông báo
- ✅ Xem học phí

### 👨‍👩‍👧 Phụ huynh
- ✅ Theo dõi kết quả học tập của con
- ✅ Xem thông báo học phí
- ✅ Xem lịch học
- ✅ Nhận thông báo từ trung tâm

---

## 🛠 CÔNG NGHỆ SỬ DỤNG

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL 8.0+** - Database
- **PDO** - Database connection

### Frontend
- **HTML5** - Markup
- **CSS3** - Styling
- **JavaScript (ES6+)** - Client-side logic
- **Bootstrap 5.3** - UI Framework
- **Bootstrap Icons** - Icon library

---

## 💻 YÊU CẦU HỆ THỐNG

### Cài đặt cần thiết:

1. **XAMPP** hoặc **WAMP** (khuyến nghị XAMPP 8.0+)
   - PHP >= 7.4
   - MySQL >= 8.0
   - Apache Server

2. **Trình duyệt web hiện đại**
   - Google Chrome (khuyến nghị)
   - Firefox
   - Microsoft Edge

3. **Text Editor** (tùy chọn)
   - VS Code
   - Sublime Text
   - PhpStorm

---

## 📥 HƯỚNG DẪN CÀI ĐẶT

### Bước 1: Cài đặt XAMPP

1. **Download XAMPP** từ https://www.apachefriends.org/
2. Cài đặt XAMPP vào thư mục mặc định: `C:\xampp` (Windows) hoặc `/opt/lampp` (Linux)
3. Khởi động **Apache** và **MySQL** từ XAMPP Control Panel

![XAMPP Control Panel](https://via.placeholder.com/600x200?text=XAMPP+Control+Panel)

### Bước 2: Clone/Copy code về máy

#### Cách 1: Copy thư mục dự án
```bash
# Copy toàn bộ thư mục DoAnWeb vào thư mục htdocs của XAMPP
# Windows: C:\xampp\htdocs\DoAnWeb
# Linux: /opt/lampp/htdocs/DoAnWeb
```

#### Cách 2: Sử dụng Git (nếu có)
```bash
cd C:\xampp\htdocs  # Windows
# hoặc
cd /opt/lampp/htdocs  # Linux

git clone [URL_REPO] DoAnWeb
cd DoAnWeb
```

### Bước 3: Tạo Database

#### Cách 1: Sử dụng phpMyAdmin (Khuyến nghị cho người mới)

1. Mở trình duyệt và truy cập: **http://localhost/phpmyadmin**
2. Đăng nhập (mặc định: username `root`, password để trống)
3. Click vào tab **"SQL"** ở menu trên
4. Mở file `database_mysql.sql` bằng Notepad hoặc Text Editor
5. **Copy toàn bộ nội dung** trong file `database_mysql.sql`
6. **Paste vào ô SQL query** trong phpMyAdmin
7. Click nút **"Go"** hoặc **"Thực hiện"**
8. Chờ đợi... Nếu thành công, bạn sẽ thấy thông báo màu xanh

![phpMyAdmin Import](https://via.placeholder.com/600x300?text=phpMyAdmin+SQL+Import)

#### Cách 2: Sử dụng Import (Dễ hơn)

1. Mở **phpMyAdmin**: http://localhost/phpmyadmin
2. Đảm bảo **KHÔNG chọn database nào** (nhấn vào logo phpMyAdmin góc trên bên trái để về trang chủ)
3. Click vào tab **"Import"**
4. Click **"Choose File"** (Chọn tệp)
5. Chọn file **`database_mysql.sql`** từ thư mục dự án
6. Kéo xuống dưới cùng, click **"Go"** (Thực hiện)
7. Chờ import hoàn tất

![phpMyAdmin Import File](https://via.placeholder.com/600x300?text=phpMyAdmin+Import+File)

#### Cách 3: Sử dụng MySQL Command Line (Dành cho người có kinh nghiệm)

```bash
# Windows
cd C:\xampp\mysql\bin
mysql -u root -p < C:\xampp\htdocs\DoAnWeb\database_mysql.sql

# Linux
cd /opt/lampp/bin
./mysql -u root -p < /opt/lampp/htdocs/DoAnWeb/database_mysql.sql
```

### Bước 4: Cấu hình kết nối Database

1. Mở file `config/database.php`
2. Kiểm tra và chỉnh sửa thông tin kết nối (nếu cần):

```php
private $host = "localhost";        // Địa chỉ MySQL server
private $db_name = "QuanLyHocThem"; // Tên database
private $username = "root";         // Username MySQL
private $password = "";             // Password MySQL (mặc định là trống)
```

**Lưu ý quan trọng:**
- Nếu bạn đặt mật khẩu cho MySQL, cần thay đổi `$password`
- Nếu MySQL chạy trên port khác 3306, thêm port vào `$host`: `"localhost:3307"`

### Bước 5: Kiểm tra cài đặt

1. Mở trình duyệt
2. Truy cập: **http://localhost/DoAnWeb/DangNhap(chung).html**
3. Nếu trang đăng nhập hiển thị → Cài đặt thành công! 🎉

---

## 📁 CẤU TRÚC THƯ MỤC

```
DoAnWeb/
│
├── 📄 database_mysql.sql          # File SQL database (QUAN TRỌNG!)
├── 📄 cnpm_QuanLyHocThem.sql     # File SQL cũ (SQL Server - không dùng)
├── 📄 README.md                   # File hướng dẫn này
│
├── 📂 config/                     # Cấu hình hệ thống
│   └── database.php              # Kết nối database
│
├── 📂 includes/                   # File tiện ích
│   └── helpers.php               # Các hàm helper
│
├── 📂 api/                        # Backend API (PHP)
│   ├── auth.php                  # API đăng nhập/đăng xuất
│   ├── hocsinh.php               # API quản lý học sinh
│   ├── lophoc.php                # API quản lý lớp học
│   ├── diem.php                  # API quản lý điểm
│   └── hocphi.php                # API quản lý học phí
│
├── 📂 uploads/                    # Thư mục lưu file upload
│
├── 📂 assets/                     # Tài nguyên tĩnh
│   ├── css/                      # File CSS tùy chỉnh
│   ├── js/                       # File JavaScript
│   └── images/                   # Hình ảnh
│
└── 📄 HTML Files                  # Các trang giao diện
    ├── DangNhap(chung).html
    ├── Dashboard(chung).html
    ├── QLLop(Admin).html
    ├── NhapDiem(GiaoVien).html
    ├── XemDiem(Hocsinh).html
    └── DKKhoaHoc-ThanhToan(PhuHuynh).html
```

---

## 📖 HƯỚNG DẪN SỬ DỤNG

### 1. Đăng nhập vào hệ thống

**URL:** http://localhost/DoAnWeb/DangNhap(chung).html

#### Tài khoản Admin:
```
Email: admin@englishcenter.com
Password: password
```

#### Tài khoản Giáo viên:
```
Email: binh.gv@englishcenter.com
Password: password
```

#### Tài khoản Học sinh:
```
Email: an.nguyen@email.com
Password: password
```

**Lưu ý:** Tất cả mật khẩu mặc định là `password`

### 2. Chức năng theo vai trò

#### 👨‍💼 ADMIN

**a) Quản lý Học sinh**
1. Truy cập: Dashboard → Quản lý học viên
2. Thêm học sinh mới:
   - Click nút "Thêm học sinh"
   - Điền đầy đủ thông tin
   - Click "Lưu"
3. Chỉnh sửa/Xóa: Click icon tương ứng ở cột "Hành động"

**b) Quản lý Lớp học**
1. Truy cập: Dashboard → Quản lý lớp
2. Tạo lớp mới:
   - Click "Tạo lớp mới"
   - Chọn khóa học
   - Nhập tên lớp, lịch học
   - Phân công giáo viên
   - Click "Lưu"

**c) Quản lý Học phí**
1. Truy cập: Dashboard → Học phí
2. Xem danh sách học phí chưa đóng
3. Xác nhận thanh toán khi học sinh đóng tiền

#### 👨‍🏫 GIÁO VIÊN

**a) Nhập điểm**
1. Truy cập trang nhập điểm
2. Chọn lớp và loại điểm
3. Click "Tải danh sách"
4. Nhập điểm cho từng học sinh
5. Click "Lưu lại điểm"

**b) Điểm danh**
1. Chọn lớp và ngày học
2. Đánh dấu có mặt/vắng cho từng học sinh
3. Lưu điểm danh

#### 👨‍🎓 HỌC SINH

**a) Xem điểm**
1. Truy cập trang xem điểm
2. Xem bảng điểm các khóa học đã học

**b) Xem học phí**
1. Truy cập trang học phí
2. Xem trạng thái đã đóng/chưa đóng

---

## 🔌 API DOCUMENTATION

### Authentication API (`api/auth.php`)

#### 1. Đăng nhập
```http
POST /api/auth.php?action=login
Content-Type: application/json

{
  "email": "admin@englishcenter.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Đăng nhập thành công",
  "data": {
    "user": {
      "id": 1,
      "ho_ten": "Nguyễn Văn Admin",
      "email": "admin@englishcenter.com",
      "vai_tro": "Admin"
    }
  }
}
```

#### 2. Đăng xuất
```http
POST /api/auth.php?action=logout
```

#### 3. Kiểm tra session
```http
GET /api/auth.php?action=check
```

### Học sinh API (`api/hocsinh.php`)

#### 1. Lấy danh sách học sinh
```http
GET /api/hocsinh.php
GET /api/hocsinh.php?search=nguyen&page=1&per_page=20
```

#### 2. Lấy chi tiết học sinh
```http
GET /api/hocsinh.php?action=detail&id=9
```

#### 3. Thêm học sinh mới
```http
POST /api/hocsinh.php?action=create
Content-Type: application/json

{
  "ho_ten": "Nguyễn Văn A",
  "email": "vana@email.com",
  "so_dien_thoai": "0901234567",
  "ngay_sinh": "2008-05-15",
  "gioi_tinh": "Nam",
  "lop_hien_tai": "10A1",
  "truong_hoc": "THPT ABC"
}
```

#### 4. Cập nhật thông tin học sinh
```http
PUT /api/hocsinh.php?action=update
Content-Type: application/json

{
  "id": 9,
  "ho_ten": "Nguyễn Văn A Updated",
  "so_dien_thoai": "0987654321"
}
```

#### 5. Xóa học sinh
```http
DELETE /api/hocsinh.php?action=delete&id=9
```

### Lớp học API (`api/lophoc.php`)

#### 1. Lấy danh sách lớp học
```http
GET /api/lophoc.php
```

#### 2. Lấy chi tiết lớp học
```http
GET /api/lophoc.php?action=detail&id=1
```

#### 3. Lấy danh sách học sinh trong lớp
```http
GET /api/lophoc.php?action=hocsinh&id=1
```

#### 4. Tạo lớp học mới
```http
POST /api/lophoc.php?action=create
Content-Type: application/json

{
  "ten_lop": "IELTS 7.0+ (Sáng T2-4-6)",
  "ma_khoa_hoc": 2,
  "phong_hoc": "P301",
  "si_so_toi_da": 12,
  "ngay_bat_dau": "2025-03-01",
  "ngay_ket_thuc": "2025-05-01",
  "ma_giao_vien": 3,
  "lich_hoc": [
    {"thu": "2", "gio_bat_dau": "08:00", "gio_ket_thuc": "10:00"},
    {"thu": "4", "gio_bat_dau": "08:00", "gio_ket_thuc": "10:00"},
    {"thu": "6", "gio_bat_dau": "08:00", "gio_ket_thuc": "10:00"}
  ]
}
```

#### 5. Đăng ký học sinh vào lớp
```http
POST /api/lophoc.php?action=dangky
Content-Type: application/json

{
  "ma_hoc_sinh": 9,
  "ma_lop": 1
}
```

### Điểm API (`api/diem.php`)

#### 1. Xem điểm của học sinh
```http
GET /api/diem.php?action=hocsinh&id=9
```

#### 2. Xem điểm theo lớp
```http
GET /api/diem.php?action=lop&id=1
```

#### 3. Nhập điểm
```http
POST /api/diem.php?action=create
Content-Type: application/json

{
  "ma_hoc_sinh": 9,
  "ma_khoa_hoc": 2,
  "diem_chuyen_can": 9.0,
  "diem_giua_ky": 8.0,
  "diem_cuoi_ky": 7.5,
  "nhan_xet": "Học sinh chăm chỉ"
}
```

### Học phí API (`api/hocphi.php`)

#### 1. Xem học phí của học sinh
```http
GET /api/hocphi.php?action=hocsinh&id=9
```

#### 2. Xem học phí chưa đóng
```http
GET /api/hocphi.php?action=chuadong
```

#### 3. Tạo hóa đơn học phí
```http
POST /api/hocphi.php?action=create
Content-Type: application/json

{
  "ma_hoc_sinh": 9,
  "ma_khoa_hoc": 2,
  "han_dong": "2025-02-15"
}
```

#### 4. Xác nhận thanh toán
```http
PUT /api/hocphi.php?action=thanhtoan
Content-Type: application/json

{
  "ma_hoc_phi": 1,
  "phuong_thuc": "Chuyển khoản"
}
```

#### 5. Thống kê học phí
```http
GET /api/hocphi.php?action=thongke
```

---

## 👥 TÀI KHOẢN MẶC ĐỊNH

### Admin
| Email | Password | Vai trò |
|-------|----------|---------|
| admin@englishcenter.com | password | Giám đốc |
| manager@englishcenter.com | password | Quản lý |

### Giáo viên
| Email | Password | Chuyên môn |
|-------|----------|------------|
| binh.gv@englishcenter.com | password | IELTS |
| cuc.gv@englishcenter.com | password | Giao tiếp |
| dung.gv@englishcenter.com | password | TOEIC |

### Học sinh
| Email | Password | Họ tên |
|-------|----------|--------|
| an.nguyen@email.com | password | Nguyễn Thị An |
| binh.le@email.com | password | Lê Văn Bình |
| hang.pham@email.com | password | Phạm Thu Hằng |

### Phụ huynh
| Email | Password | Họ tên |
|-------|----------|--------|
| cha1@email.com | password | Nguyễn Văn Cha1 |
| me2@email.com | password | Trần Thị Mẹ2 |

---

## ⚠️ XỬ LÝ SỰ CỐ

### Lỗi kết nối Database

**Triệu chứng:** Báo lỗi "Lỗi kết nối database"

**Giải pháp:**
1. Kiểm tra MySQL đã chạy trong XAMPP chưa
2. Kiểm tra thông tin trong `config/database.php`
3. Kiểm tra database `QuanLyHocThem` đã được tạo chưa

### Lỗi 404 Not Found

**Triệu chứng:** Không tìm thấy trang

**Giải pháp:**
1. Kiểm tra Apache đã chạy trong XAMPP chưa
2. Kiểm tra đường dẫn: `http://localhost/DoAnWeb/...`
3. Kiểm tra tên thư mục có đúng là `DoAnWeb` không

### Lỗi Permission Denied

**Triệu chứng:** Không thể ghi file hoặc upload

**Giải pháp:**
```bash
# Linux/Mac
chmod -R 777 uploads/

# Windows: Click chuột phải vào thư mục uploads → Properties → Security → Edit → Full Control
```

### Database import lỗi

**Triệu chứng:** Import SQL bị lỗi

**Giải pháp:**
1. Đảm bảo MySQL version >= 8.0
2. Import từng phần nhỏ nếu file quá lớn
3. Kiểm tra charset: utf8mb4

---

## 🔐 BẢO MẬT

### Thay đổi mật khẩu mặc định

**Quan trọng:** Sau khi cài đặt, nên đổi mật khẩu cho tất cả tài khoản!

1. Đăng nhập vào phpMyAdmin
2. Chọn database `QuanLyHocThem`
3. Chọn bảng `NguoiDung`
4. Chỉnh sửa cột `MatKhau`
5. Dùng tool online để hash password: https://bcrypt-generator.com/
6. Copy hash và paste vào cột `MatKhau`

### Bảo mật khi deploy lên server thật

1. Thay đổi thông tin database trong `config/database.php`
2. Tắt error reporting trong PHP
3. Đặt mật khẩu mạnh cho MySQL
4. Sử dụng HTTPS
5. Backup database định kỳ

---

## 📞 HỖ TRỢ

### Liên hệ
- **Email:** support@englishcenter.com
- **Hotline:** 1900-xxxx

### Tài liệu tham khảo
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)

---

## 📝 CHANGELOG

### Version 1.0.0 (2025-01-12)
- ✅ Khởi tạo dự án
- ✅ Hoàn thành database schema
- ✅ Hoàn thành API backend
- ✅ Hoàn thành giao diện frontend cơ bản
- ✅ Thêm dữ liệu mẫu
- ✅ Viết documentation đầy đủ

---

## 📜 LICENSE

Copyright © 2025 EnglishCenter. All rights reserved.

---

## 🙏 LỜI CẢM ƠN

Cảm ơn bạn đã sử dụng hệ thống Quản lý Trung tâm Dạy thêm!

Nếu gặp vấn đề, vui lòng tạo issue hoặc liên hệ qua email.

**Chúc bạn triển khai thành công! 🎉**
