# 🚀 HƯỚNG DẪN CÀI ĐẶT TỪ ĐẦU
## Hệ thống Quản lý Trung tâm Dạy thêm

> **Thời gian cài đặt:** Khoảng 15-20 phút
> **Độ khó:** ⭐⭐☆☆☆ (Dễ)
> **Yêu cầu:** Máy tính Windows/Mac/Linux

---

## 📋 MỤC LỤC

1. [Cài đặt XAMPP](#bước-1-cài-đặt-xampp)
2. [Khởi động XAMPP](#bước-2-khởi-động-xampp)
3. [Copy code vào htdocs](#bước-3-copy-code-vào-htdocs)
4. [Import Database](#bước-4-import-database)
5. [Cấu hình kết nối](#bước-5-cấu-hình-kết-nối)
6. [Chạy hệ thống](#bước-6-chạy-hệ-thống)
7. [Khắc phục sự cố](#khắc-phục-sự-cố)

---

## 🎯 BƯỚC 1: CÀI ĐẶT XAMPP

### 1.1. Download XAMPP

**🔗 Link tải:** https://www.apachefriends.org/

#### Windows:
1. Truy cập https://www.apachefriends.org/
2. Click nút **"XAMPP for Windows"**
3. Download bản mới nhất (khuyến nghị: **XAMPP 8.2.x** trở lên)
4. File tải về: `xampp-windows-x64-8.2.x-installer.exe` (khoảng 150MB)

#### macOS:
1. Truy cập https://www.apachefriends.org/
2. Click nút **"XAMPP for OS X"**
3. Download bản mới nhất
4. File tải về: `xampp-osx-8.2.x-installer.dmg`

#### Linux:
1. Truy cập https://www.apachefriends.org/
2. Click nút **"XAMPP for Linux"**
3. Download bản mới nhất
4. File tải về: `xampp-linux-x64-8.2.x-installer.run`

---

### 1.2. Cài đặt XAMPP

#### 🪟 Trên Windows:

**Bước 1:** Double-click file `xampp-windows-x64-8.2.x-installer.exe`

**Bước 2:** Nếu có cảnh báo Windows Defender, click **"Yes"** để cho phép

**Bước 3:** Cửa sổ Setup xuất hiện
```
┌─────────────────────────────────────┐
│   Setup - XAMPP                     │
├─────────────────────────────────────┤
│                                     │
│   Welcome to the XAMPP Setup       │
│   Wizard                           │
│                                     │
│   [Next >]              [Cancel]   │
└─────────────────────────────────────┘
```
→ Click **"Next"**

**Bước 4:** Chọn các components cần cài
```
Select Components:
☑ Apache           ← BẮT BUỘC
☑ MySQL            ← BẮT BUỘC
☑ PHP              ← BẮT BUỘC
☑ phpMyAdmin       ← BẮT BUỘC
☐ Perl
☐ Tomcat
☐ Webalizer
```
→ Đảm bảo chọn: **Apache, MySQL, PHP, phpMyAdmin**
→ Click **"Next"**

**Bước 5:** Chọn thư mục cài đặt
```
Installation folder:
C:\xampp           ← KHUYẾN NGHỊ giữ mặc định

[Browse...]

[< Back]  [Next >]  [Cancel]
```
→ **QUAN TRỌNG:** Nên giữ đường dẫn mặc định `C:\xampp`
→ Click **"Next"**

**Bước 6:** Bỏ chọn Learn more about Bitnami
```
☐ Learn more about Bitnami for XAMPP
```
→ Bỏ tick (không cần thiết)
→ Click **"Next"**

**Bước 7:** Bắt đầu cài đặt
```
Ready to Install

Setup is now ready to begin installing
XAMPP on your computer.

[< Back]  [Next >]  [Cancel]
```
→ Click **"Next"** để bắt đầu cài

**Bước 8:** Đợi cài đặt (khoảng 3-5 phút)
```
Installing...
████████████░░░░░░░░ 65%
Extracting files...
```

**Bước 9:** Hoàn tất
```
Completing the XAMPP Setup Wizard

☑ Do you want to start the Control Panel now?

[Finish]
```
→ Tick "Do you want to start the Control Panel now?"
→ Click **"Finish"**

---

#### 🍎 Trên macOS:

1. Double-click file `.dmg` đã tải
2. Kéo XAMPP icon vào thư mục Applications
3. Mở Applications → XAMPP → manager-osx
4. Nhập password Mac khi được yêu cầu

---

#### 🐧 Trên Linux:

```bash
# 1. Mở Terminal
cd ~/Downloads

# 2. Cấp quyền thực thi
chmod +x xampp-linux-x64-8.2.x-installer.run

# 3. Chạy installer với quyền sudo
sudo ./xampp-linux-x64-8.2.x-installer.run

# 4. Follow the graphical installer
```

---

## 🎮 BƯỚC 2: KHỞI ĐỘNG XAMPP

### 2.1. Mở XAMPP Control Panel

#### Windows:
- **Cách 1:** Start Menu → Search "XAMPP" → Click "XAMPP Control Panel"
- **Cách 2:** Double-click icon XAMPP trên Desktop
- **Cách 3:** Chạy `C:\xampp\xampp-control.exe`

#### macOS:
- Applications → XAMPP → manager-osx

#### Linux:
```bash
sudo /opt/lampp/manager-linux-x64.run
```

---

### 2.2. Khởi động Apache và MySQL

Cửa sổ XAMPP Control Panel sẽ hiện ra:

```
┌────────────────────────────────────────────────┐
│  XAMPP Control Panel v3.3.0                    │
├────────────────────────────────────────────────┤
│  Module   │ PID  │ Port  │ Actions           │
├───────────┼──────┼───────┼───────────────────┤
│  Apache   │      │ 80,443│ [Start] [Admin]   │ ← 1. Click Start
│  MySQL    │      │ 3306  │ [Start] [Admin]   │ ← 2. Click Start
│  FileZilla│      │       │ [Start] [Admin]   │
│  Mercury  │      │       │ [Start] [Admin]   │
│  Tomcat   │      │       │ [Start] [Admin]   │
└────────────────────────────────────────────────┘
```

**Bước 1:** Click nút **[Start]** ở dòng **Apache**
- Đợi vài giây
- Nếu thành công, dòng Apache sẽ chuyển sang màu **xanh lá**
- Hiển thị: `Apache [Port 80] started`

**Bước 2:** Click nút **[Start]** ở dòng **MySQL**
- Đợi vài giây
- Nếu thành công, dòng MySQL sẽ chuyển sang màu **xanh lá**
- Hiển thị: `MySQL [Port 3306] started`

**Kết quả thành công:**
```
┌────────────────────────────────────────────────┐
│  Module   │ PID  │ Port  │ Actions           │
├───────────┼──────┼───────┼───────────────────┤
│  Apache   │ 1234 │ 80,443│ [Stop] [Admin]    │ 🟢 Xanh
│  MySQL    │ 5678 │ 3306  │ [Stop] [Admin]    │ 🟢 Xanh
└────────────────────────────────────────────────┘
```

---

### 2.3. Kiểm tra Apache hoạt động

1. Mở trình duyệt web (Chrome, Firefox, Edge...)
2. Truy cập: **http://localhost**
3. Nếu thấy trang XAMPP Dashboard → **Thành công!** ✅

```
┌─────────────────────────────────────┐
│  🟧 XAMPP                           │
│                                     │
│  Welcome to XAMPP for Windows!     │
│  Apache + MariaDB + PHP + Perl     │
│                                     │
│  [phpMyAdmin] [Documentation]      │
└─────────────────────────────────────┘
```

---

### ⚠️ Nếu Apache không Start được

#### Lỗi: Port 80 đang được sử dụng

**Nguyên nhân:** Skype, IIS, hoặc ứng dụng khác đang dùng port 80

**Giải pháp:**

**Cách 1: Đổi port Apache**
1. XAMPP Control Panel → Click **[Config]** ở dòng Apache
2. Chọn **"Apache (httpd.conf)"**
3. Tìm dòng: `Listen 80`
4. Đổi thành: `Listen 8080`
5. Save và đóng file
6. Restart Apache
7. Truy cập: **http://localhost:8080**

**Cách 2: Tắt Skype (nếu đang dùng)**
1. Mở Skype
2. Tools → Options → Advanced → Connection
3. Bỏ tick "Use port 80 and 443 as alternatives"
4. Restart Skype và XAMPP

**Cách 3: Tắt IIS (Windows)**
1. Start → Run → `services.msc`
2. Tìm "World Wide Web Publishing Service"
3. Click phải → Stop

---

## 📂 BƯỚC 3: COPY CODE VÀO HTDOCS

### 3.1. Tìm thư mục htdocs

Đây là nơi chứa code website:

#### Windows:
```
C:\xampp\htdocs\
```

#### macOS:
```
/Applications/XAMPP/htdocs/
```

#### Linux:
```
/opt/lampp/htdocs/
```

---

### 3.2. Copy code vào htdocs

**Cách 1: Copy thư mục DoAnWeb**

1. Mở File Explorer (Windows) hoặc Finder (Mac)
2. Navigate đến thư mục chứa code `DoAnWeb`
3. **Copy toàn bộ thư mục `DoAnWeb`**
4. Paste vào `C:\xampp\htdocs\`

**Kết quả:**
```
C:\xampp\htdocs\DoAnWeb\
├── api/
├── assets/
├── config/
├── includes/
├── modules/
├── uploads/
├── DangNhap(chung).html
├── Dashboard(chung).html
├── database_mysql.sql
└── README.md
```

**Cách 2: Sử dụng Git (Nếu biết Git)**

```bash
# Mở Command Prompt/Terminal
cd C:\xampp\htdocs

# Clone repository
git clone [URL_REPOSITORY] DoAnWeb

# Hoặc nếu đã có code
# Copy vào thư mục htdocs
```

---

### 3.3. Kiểm tra quyền truy cập (Linux/Mac)

```bash
# Cấp quyền cho thư mục uploads
chmod 777 /opt/lampp/htdocs/DoAnWeb/uploads

# Hoặc
sudo chown -R daemon:daemon /opt/lampp/htdocs/DoAnWeb
```

---

## 💾 BƯỚC 4: IMPORT DATABASE

### 4.1. Mở phpMyAdmin

**Cách 1:** Từ XAMPP Control Panel
- Click nút **[Admin]** ở dòng MySQL
- Tự động mở trình duyệt với phpMyAdmin

**Cách 2:** Truy cập trực tiếp
- Mở trình duyệt
- Truy cập: **http://localhost/phpmyadmin**

---

### 4.2. Đăng nhập phpMyAdmin

```
┌─────────────────────────────────────┐
│  phpMyAdmin                         │
├─────────────────────────────────────┤
│  Username: root                     │ ← Nhập "root"
│  Password: ████                     │ ← Để trống (mặc định)
│                                     │
│  [Go]                               │
└─────────────────────────────────────┘
```

**Thông tin đăng nhập mặc định:**
- **Username:** `root`
- **Password:** (để trống - không nhập gì)

→ Click **[Go]** hoặc nhấn Enter

---

### 4.3. Import file SQL

#### Phương pháp 1: Sử dụng Import (KHUYẾN NGHỊ) ⭐

**Bước 1:** Click tab **"Import"** ở menu trên

```
┌───────────────────────────────────────────┐
│ 🏠 Databases | SQL | Status | Users      │
│ ▼ [Import] | Export | Settings          │
└───────────────────────────────────────────┘
```

**Bước 2:** Trang Import hiển thị

```
┌─────────────────────────────────────────┐
│  File to Import:                        │
│                                         │
│  [Choose File]  No file chosen          │
│                                         │
│  Character set of the file:             │
│  └─ utf-8 ▼                            │
│                                         │
│  Format:                                │
│  └─ SQL ▼                              │
│                                         │
│  [Go]                                   │
└─────────────────────────────────────────┘
```

**Bước 3:** Click nút **[Choose File]** (hoặc **[Chọn tệp]**)

**Bước 4:** Navigate đến thư mục code
```
C:\xampp\htdocs\DoAnWeb\database_mysql.sql
```
→ Chọn file **`database_mysql.sql`**
→ Click **[Open]**

**Bước 5:** Kiểm tra các thiết lập
- ✅ **Character set:** `utf-8`
- ✅ **Format:** `SQL`
- ✅ **Partial import:** Bỏ tick (nếu có)

**Bước 6:** Kéo xuống dưới cùng → Click **[Go]**

**Bước 7:** Đợi import (5-10 giây)

**Bước 8:** Kết quả thành công
```
┌─────────────────────────────────────────┐
│  ✅ Import has been successfully        │
│     finished.                           │
│                                         │
│  • 18 tables created                   │
│  • 50 rows inserted                    │
│  • 5 views created                     │
│  • 4 triggers created                  │
│  • 2 procedures created                │
└─────────────────────────────────────────┘
```

---

#### Phương pháp 2: Sử dụng SQL Tab

**Bước 1:** Click tab **"SQL"** ở menu trên

**Bước 2:** Mở file `database_mysql.sql` bằng Notepad

**Bước 3:** Copy toàn bộ nội dung (Ctrl+A → Ctrl+C)

**Bước 4:** Paste vào ô SQL query trong phpMyAdmin

```
┌─────────────────────────────────────────┐
│  Run SQL query/queries on database:     │
│  ┌─────────────────────────────────────┐│
│  │ DROP DATABASE IF EXISTS ...         ││
│  │ CREATE DATABASE QuanLyHocThem ...   ││
│  │ USE QuanLyHocThem;                  ││
│  │ CREATE TABLE NguoiDung (...         ││
│  │ ...                                 ││
│  │ (Paste toàn bộ nội dung SQL vào đây)││
│  └─────────────────────────────────────┘│
│  [Go]                                   │
└─────────────────────────────────────────┘
```

**Bước 5:** Click **[Go]**

**Bước 6:** Chờ thực thi (có thể mất 30s - 1 phút)

---

### 4.4. Kiểm tra Database đã được tạo

**Bước 1:** Click logo **phpMyAdmin** ở góc trên trái để về trang chủ

**Bước 2:** Xem danh sách database ở sidebar bên trái

```
┌─────────────────────┐
│  Databases          │
├─────────────────────┤
│  information_schema │
│  mysql              │
│  performance_schema │
│  phpmyadmin         │
│  test               │
│  ▼ QuanLyHocThem   │ ← Phải thấy database này
└─────────────────────┘
```

**Bước 3:** Click vào **`QuanLyHocThem`**

**Bước 4:** Kiểm tra các bảng đã được tạo

```
┌─────────────────────────────────────────┐
│  Database: QuanLyHocThem                │
├─────────────────────────────────────────┤
│  Table                    │ Records     │
├───────────────────────────┼─────────────┤
│  Admin                    │ 2           │
│  BaoCao                   │ 2           │
│  BaiTap                   │ 3           │
│  DangKyLop                │ 5           │
│  Diem                     │ 5           │
│  DiemDanh                 │ 8           │
│  GiaoVien                 │ 3           │
│  GuiThongBao              │ 7           │
│  HocPhi                   │ 5           │
│  HocSinh                  │ 5           │
│  KhoaHoc                  │ 5           │
│  LichHoc                  │ 8           │
│  LopHoc                   │ 4           │
│  NguoiDung                │ 13          │
│  NopBaiTap                │ 3           │
│  PhanCongGiaoVien         │ 3           │
│  PhuHuynh                 │ 3           │
│  ThongBao                 │ 3           │
└─────────────────────────────────────────┘
```

**✅ Nếu thấy 18 bảng như trên → Import thành công!**

---

### 4.5. Xem dữ liệu mẫu

Click vào bảng **`NguoiDung`** để xem dữ liệu:

```
┌────────────────────────────────────────────────────────┐
│  Showing rows 0 - 12 (13 total)                       │
├────┬──────────────────┬─────────────────────┬─────────┤
│ ID │ HoTen            │ Email               │ VaiTro  │
├────┼──────────────────┼─────────────────────┼─────────┤
│ 1  │ Nguyễn Văn Admin │ admin@english...    │ Admin   │
│ 2  │ Trần Thị Quản Lý │ manager@english...  │ Admin   │
│ 3  │ Nguyễn Văn Bình  │ binh.gv@english...  │ GiaoVien│
│ 4  │ Trần Thị Cúc     │ cuc.gv@english...   │ GiaoVien│
│ 5  │ Lê Hoàng Dũng    │ dung.gv@english...  │ GiaoVien│
│ 9  │ Nguyễn Thị An    │ an.nguyen@email...  │ HocSinh │
│ 10 │ Lê Văn Bình      │ binh.le@email...    │ HocSinh │
└────┴──────────────────┴─────────────────────┴─────────┘
```

**✅ Thấy dữ liệu → Hoàn tất import database!**

---

## ⚙️ BƯỚC 5: CẤU HÌNH KẾT NỐI

### 5.1. Kiểm tra file config

Mở file: `C:\xampp\htdocs\DoAnWeb\config\database.php`

```php
<?php
class Database {
    private $host = "localhost";        // ← Kiểm tra
    private $db_name = "QuanLyHocThem"; // ← Kiểm tra
    private $username = "root";         // ← Kiểm tra
    private $password = "";             // ← Kiểm tra (mặc định để trống)
    ...
}
?>
```

### 5.2. Chỉnh sửa (nếu cần)

**Trường hợp 1: MySQL có password**

Nếu bạn đã đặt password cho MySQL:
```php
private $password = "your_password_here";
```

**Trường hợp 2: MySQL chạy port khác 3306**

```php
private $host = "localhost:3307"; // Thay 3307 bằng port của bạn
```

**Trường hợp 3: Cài trên server khác**

```php
private $host = "192.168.1.100"; // IP server MySQL
private $username = "your_username";
private $password = "your_password";
```

---

### 5.3. Test kết nối

Tạo file test: `C:\xampp\htdocs\DoAnWeb\test_connection.php`

```php
<?php
require_once 'config/database.php';

echo "<h1>Test Kết Nối Database</h1>";

try {
    $database = new Database();
    $conn = $database->getConnection();

    echo "<p style='color: green;'>✅ Kết nối thành công!</p>";

    // Test query
    $query = "SELECT COUNT(*) as total FROM NguoiDung";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    echo "<p>Số lượng người dùng: " . $result['total'] . "</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
}
?>
```

**Test:**
- Truy cập: http://localhost/DoAnWeb/test_connection.php
- Nếu thấy **"✅ Kết nối thành công!"** → OK!

---

## 🎉 BƯỚC 6: CHẠY HỆ THỐNG

### 6.1. Truy cập trang đăng nhập

Mở trình duyệt và truy cập:

```
http://localhost/DoAnWeb/DangNhap(chung).html
```

**Kết quả:** Trang đăng nhập đẹp hiển thị với:
- Logo EnglishCenter
- Form đăng nhập
- Danh sách tài khoản demo

---

### 6.2. Đăng nhập

**Cách 1: Click vào tài khoản demo (NHANH)**

Click vào một trong các tài khoản demo:
- **Admin:** admin@englishcenter.com
- **Giáo viên:** binh.gv@englishcenter.com
- **Học sinh:** an.nguyen@email.com
- **Phụ huynh:** cha1@email.com

→ Email và password tự động được điền

**Cách 2: Nhập thủ công**

```
Email:    admin@englishcenter.com
Password: password
```

→ Click nút **"Đăng nhập"**

---

### 6.3. Vào Dashboard

Sau khi đăng nhập thành công:
- Thông báo: ✅ "Đăng nhập thành công! Đang chuyển hướng..."
- Tự động chuyển đến Dashboard
- Sidebar hiển thị menu theo vai trò
- User info hiển thị ở cuối sidebar

---

### 6.4. Test các chức năng

#### Admin:
1. **Dashboard** → Xem stats cards với số liệu thực
2. **Quản lý lớp học** → Xem danh sách 4 lớp từ database
3. Click **"Xem danh sách"** → Xem học sinh trong lớp
4. Click **"Tạo lớp mới"** → Form modal hiển thị

#### Giáo viên:
1. **Nhập điểm** → Chọn lớp → Nhập điểm → Lưu
2. **Lớp học của tôi** → Xem các lớp được phân công

#### Học sinh:
1. **Xem điểm** → Xem bảng điểm với xếp loại
2. **Khóa học của tôi** → Xem các khóa đang học

---

## 🎯 CHECKLIST HOÀN THÀNH

Đánh dấu ✅ khi hoàn thành mỗi bước:

- [ ] **Bước 1:** XAMPP đã cài đặt thành công
- [ ] **Bước 2:** Apache và MySQL đang chạy (màu xanh)
- [ ] **Bước 3:** Code đã copy vào `C:\xampp\htdocs\DoAnWeb`
- [ ] **Bước 4:** Database `QuanLyHocThem` đã được import
- [ ] **Bước 5:** File config đã cấu hình đúng
- [ ] **Bước 6:** Đăng nhập thành công và vào Dashboard

**✅ Nếu tất cả đều OK → HỆ THỐNG ĐÃ CHẠY!**

---

## 🐛 KHẮC PHỤC SỰ CỐ

### Sự cố 1: Apache không Start

**Triệu chứng:**
```
[Apache] Error: Apache shutdown unexpectedly.
[Apache] This may be due to a blocked port...
```

**Nguyên nhân:** Port 80 đang được sử dụng bởi app khác

**Giải pháp:**

**Cách 1: Tìm app đang dùng port 80**
```cmd
# Mở Command Prompt as Administrator
netstat -ano | findstr :80

# Kết quả:
TCP    0.0.0.0:80    0.0.0.0:0    LISTENING    1234

# Kill process
taskkill /PID 1234 /F
```

**Cách 2: Đổi port Apache sang 8080**
1. XAMPP Control Panel → Config → Apache (httpd.conf)
2. Tìm: `Listen 80`
3. Đổi: `Listen 8080`
4. Save → Restart Apache
5. Truy cập: `http://localhost:8080/DoAnWeb/`

---

### Sự cố 2: MySQL không Start

**Triệu chứng:**
```
[MySQL] Error: MySQL shutdown unexpectedly.
[MySQL] This may be due to a blocked port...
```

**Nguyên nhân:** Port 3306 đang được sử dụng

**Giải pháp:**

**Kiểm tra port 3306:**
```cmd
netstat -ano | findstr :3306
```

**Đổi port MySQL:**
1. XAMPP Control Panel → Config → my.ini
2. Tìm: `port=3306`
3. Đổi: `port=3307`
4. Save → Restart MySQL
5. Update `config/database.php`:
   ```php
   private $host = "localhost:3307";
   ```

---

### Sự cố 3: Lỗi "404 Not Found"

**Triệu chứng:**
```
Not Found
The requested URL was not found on this server.
```

**Nguyên nhân:** Đường dẫn sai hoặc file không tồn tại

**Giải pháp:**

**Kiểm tra đường dẫn:**
```
✅ Đúng: http://localhost/DoAnWeb/DangNhap(chung).html
❌ Sai:  http://localhost/dangnhap.html
❌ Sai:  http://localhost/DoAnWeb
```

**Kiểm tra file tồn tại:**
```
C:\xampp\htdocs\DoAnWeb\DangNhap(chung).html  ← Phải có file này
```

---

### Sự cố 4: Lỗi "Lỗi kết nối database"

**Triệu chứng:**
```
{
  "success": false,
  "message": "Lỗi kết nối database: ..."
}
```

**Nguyên nhân:** Thông tin database sai hoặc MySQL chưa chạy

**Giải pháp:**

**1. Kiểm tra MySQL đang chạy**
- XAMPP Control Panel → MySQL phải màu xanh

**2. Kiểm tra database tồn tại**
- phpMyAdmin → Phải thấy `QuanLyHocThem`

**3. Kiểm tra config/database.php**
```php
private $host = "localhost";        // ← Đúng chưa?
private $db_name = "QuanLyHocThem"; // ← Đúng chưa?
private $username = "root";         // ← Đúng chưa?
private $password = "";             // ← Có password không?
```

**4. Test kết nối**
```
http://localhost/DoAnWeb/test_connection.php
```

---

### Sự cố 5: Trang trắng (Blank page)

**Nguyên nhân:** Lỗi PHP

**Giải pháp:**

**Bật error reporting:**
Thêm vào đầu file PHP:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

**Xem error log:**
```
C:\xampp\php\logs\php_error_log
```

---

### Sự cố 6: Lỗi "API is not defined"

**Triệu chứng:** Console báo lỗi
```
Uncaught ReferenceError: API is not defined
```

**Nguyên nhân:** Chưa load file `api.js`

**Giải pháp:**

Kiểm tra file HTML có include:
```html
<script src="assets/js/api.js"></script>
```

Kiểm tra file tồn tại:
```
C:\xampp\htdocs\DoAnWeb\assets\js\api.js
```

---

### Sự cố 7: Import SQL lỗi

**Triệu chứng:**
```
#1064 - You have an error in your SQL syntax...
```

**Nguyên nhân:** File SQL không tương thích hoặc bị lỗi

**Giải pháp:**

**1. Kiểm tra MySQL version**
```
phpMyAdmin → Home → Server version: 8.x.x
```
→ Cần >= 8.0

**2. Import từng phần**
- Mở file `database_mysql.sql`
- Copy phần tạo database và bảng trước
- Import từng bảng một

**3. Sử dụng command line**
```cmd
cd C:\xampp\mysql\bin
mysql -u root -p < C:\xampp\htdocs\DoAnWeb\database_mysql.sql
```

---

### Sự cố 8: Session không hoạt động

**Triệu chứng:** Đăng nhập xong bị logout ngay

**Nguyên nhân:** Session không được lưu

**Giải pháp:**

**1. Kiểm tra session folder**
```
C:\xampp\tmp\  ← Phải tồn tại
```

**2. Cấu hình PHP**
File: `C:\xampp\php\php.ini`
```ini
session.save_path = "C:\xampp\tmp"
session.auto_start = 0
```

**3. Restart Apache**

---

## 📞 HỖ TRỢ THÊM

### Debug với Browser DevTools

**Mở DevTools:**
- **Chrome/Edge:** F12 hoặc Ctrl+Shift+I
- **Firefox:** F12

**Console Tab:**
- Xem lỗi JavaScript
- Test API:
  ```javascript
  API.Auth.login('admin@englishcenter.com', 'password')
  ```

**Network Tab:**
- Xem các API call
- Check status code (200 = OK, 404 = Not Found, 500 = Error)
- Xem response data

---

### Logs để debug

**Apache Error Log:**
```
C:\xampp\apache\logs\error.log
```

**MySQL Error Log:**
```
C:\xampp\mysql\data\mysql_error.log
```

**PHP Error Log:**
```
C:\xampp\php\logs\php_error_log
```

---

## 🎓 VIDEO HƯỚNG DẪN (Tham khảo)

Tìm trên YouTube:
- "Cài đặt XAMPP"
- "Import database MySQL phpMyAdmin"
- "Chạy PHP MySQL trên localhost"

---

## ✅ TỔNG KẾT

**Bạn đã hoàn thành:**
1. ✅ Cài đặt XAMPP
2. ✅ Khởi động Apache và MySQL
3. ✅ Copy code vào htdocs
4. ✅ Import database thành công
5. ✅ Cấu hình kết nối
6. ✅ Chạy hệ thống thành công

**Hệ thống đã sẵn sàng sử dụng! 🎉**

---

## 📚 TÀI LIỆU THAM KHẢO

- **XAMPP:** https://www.apachefriends.org/
- **PHP Manual:** https://www.php.net/manual/en/
- **MySQL Docs:** https://dev.mysql.com/doc/
- **Bootstrap 5:** https://getbootstrap.com/docs/5.3/

---

**Chúc bạn thành công! 🚀**

*Nếu gặp vấn đề khác, đọc file `README.md` hoặc `FRONTEND_GUIDE.md` để biết thêm chi tiết.*
