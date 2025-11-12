-- =============================================
-- HỆ THỐNG QUẢN LÝ TRUNG TÂM DẠY THÊM
-- Database: MySQL
-- =============================================

DROP DATABASE IF EXISTS QuanLyHocThem;
CREATE DATABASE QuanLyHocThem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE QuanLyHocThem;

-- =============================================
-- 1. BẢNG NGƯỜI DÙNG
-- =============================================
CREATE TABLE NguoiDung (
    MaNguoiDung INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    MatKhau VARCHAR(255) NOT NULL,
    SoDienThoai VARCHAR(15),
    DiaChi VARCHAR(255),
    VaiTro ENUM('HocSinh','PhuHuynh','GiaoVien','Admin') NOT NULL,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    TrangThai ENUM('KichHoat','NgungHoatDong') DEFAULT 'KichHoat',
    INDEX idx_email (Email),
    INDEX idx_vaitro (VaiTro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 2. BẢNG HỌC SINH
-- =============================================
CREATE TABLE HocSinh (
    MaHocSinh INT PRIMARY KEY,
    MaPhuHuynh INT,
    NgaySinh DATE,
    GioiTinh ENUM('Nam','Nu','Khac'),
    LopHienTai VARCHAR(50),
    TruongHoc VARCHAR(100),
    FOREIGN KEY (MaHocSinh) REFERENCES NguoiDung(MaNguoiDung) ON DELETE CASCADE,
    FOREIGN KEY (MaPhuHuynh) REFERENCES NguoiDung(MaNguoiDung) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 3. BẢNG PHỤ HUYNH
-- =============================================
CREATE TABLE PhuHuynh (
    MaPhuHuynh INT PRIMARY KEY,
    NgheNghiep VARCHAR(100),
    FOREIGN KEY (MaPhuHuynh) REFERENCES NguoiDung(MaNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 4. BẢNG GIÁO VIÊN
-- =============================================
CREATE TABLE GiaoVien (
    MaGiaoVien INT PRIMARY KEY,
    ChuyenMon VARCHAR(100),
    TrinhDo VARCHAR(50),
    KinhNghiem INT DEFAULT 0,
    Luong DECIMAL(10,2),
    FOREIGN KEY (MaGiaoVien) REFERENCES NguoiDung(MaNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 5. BẢNG ADMIN
-- =============================================
CREATE TABLE Admin (
    MaAdmin INT PRIMARY KEY,
    QuyenHan TEXT,
    ChucVu VARCHAR(50),
    FOREIGN KEY (MaAdmin) REFERENCES NguoiDung(MaNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 6. BẢNG KHÓA HỌC
-- =============================================
CREATE TABLE KhoaHoc (
    MaKhoaHoc INT AUTO_INCREMENT PRIMARY KEY,
    TenKhoaHoc VARCHAR(150) NOT NULL,
    MoTa TEXT,
    HocPhi DECIMAL(10,2) NOT NULL,
    SoBuoi INT DEFAULT 0,
    ThoiLuong INT DEFAULT 0, -- Số giờ mỗi buổi
    TrangThai ENUM('DangMo','DaDong') DEFAULT 'DangMo',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 7. BẢNG LỚP HỌC
-- =============================================
CREATE TABLE LopHoc (
    MaLop INT AUTO_INCREMENT PRIMARY KEY,
    TenLop VARCHAR(50) NOT NULL,
    MaKhoaHoc INT,
    PhongHoc VARCHAR(20),
    SiSoToiDa INT DEFAULT 12,
    SiSoHienTai INT DEFAULT 0,
    NgayBatDau DATE,
    NgayKetThuc DATE,
    TrangThai ENUM('ChuaBatDau','DangHoc','DaKetThuc') DEFAULT 'ChuaBatDau',
    FOREIGN KEY (MaKhoaHoc) REFERENCES KhoaHoc(MaKhoaHoc) ON DELETE CASCADE,
    INDEX idx_trangthailop (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 8. BẢNG ĐĂNG KÝ LỚP
-- =============================================
CREATE TABLE DangKyLop (
    MaDangKy INT AUTO_INCREMENT PRIMARY KEY,
    MaHocSinh INT,
    MaLop INT,
    NgayDangKy DATETIME DEFAULT CURRENT_TIMESTAMP,
    TrangThai ENUM('DangCho','DaXacNhan','DaHuy') DEFAULT 'DangCho',
    GhiChu TEXT,
    UNIQUE KEY unique_hocsinh_lop (MaHocSinh, MaLop),
    FOREIGN KEY (MaHocSinh) REFERENCES HocSinh(MaHocSinh) ON DELETE CASCADE,
    FOREIGN KEY (MaLop) REFERENCES LopHoc(MaLop) ON DELETE CASCADE,
    INDEX idx_trangthai (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 9. BẢNG PHÂN CÔNG GIÁO VIÊN
-- =============================================
CREATE TABLE PhanCongGiaoVien (
    MaPhanCong INT AUTO_INCREMENT PRIMARY KEY,
    MaLop INT,
    MaGiaoVien INT,
    NgayPhanCong DATE,
    GhiChu TEXT,
    FOREIGN KEY (MaLop) REFERENCES LopHoc(MaLop) ON DELETE CASCADE,
    FOREIGN KEY (MaGiaoVien) REFERENCES GiaoVien(MaGiaoVien) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 10. BẢNG ĐIỂM
-- =============================================
CREATE TABLE Diem (
    MaDiem INT AUTO_INCREMENT PRIMARY KEY,
    MaHocSinh INT,
    MaKhoaHoc INT,
    DiemChuyenCan DECIMAL(4,2),
    DiemGiuaKy DECIMAL(4,2),
    DiemCuoiKy DECIMAL(4,2),
    DiemTongKet DECIMAL(4,2),
    XepLoai VARCHAR(20),
    NhanXet TEXT,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (MaHocSinh) REFERENCES HocSinh(MaHocSinh) ON DELETE CASCADE,
    FOREIGN KEY (MaKhoaHoc) REFERENCES KhoaHoc(MaKhoaHoc) ON DELETE CASCADE,
    UNIQUE KEY unique_hocsinh_khoahoc (MaHocSinh, MaKhoaHoc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 11. BẢNG LỊCH HỌC
-- =============================================
CREATE TABLE LichHoc (
    MaLich INT AUTO_INCREMENT PRIMARY KEY,
    MaLop INT,
    ThuTrongTuan ENUM('2','3','4','5','6','7','CN'),
    GioBatDau TIME,
    GioKetThuc TIME,
    FOREIGN KEY (MaLop) REFERENCES LopHoc(MaLop) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 12. BẢNG HỌC PHÍ
-- =============================================
CREATE TABLE HocPhi (
    MaHocPhi INT AUTO_INCREMENT PRIMARY KEY,
    MaHocSinh INT,
    MaKhoaHoc INT,
    SoTien DECIMAL(10,2) NOT NULL,
    NgayTaoBill DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayDong DATE,
    HanDong DATE,
    TrangThai ENUM('ChuaDong','DaDong','QuaHan') DEFAULT 'ChuaDong',
    PhuongThucThanhToan VARCHAR(50),
    NhanVienThuPhi INT,
    GhiChu TEXT,
    FOREIGN KEY (MaHocSinh) REFERENCES HocSinh(MaHocSinh) ON DELETE CASCADE,
    FOREIGN KEY (MaKhoaHoc) REFERENCES KhoaHoc(MaKhoaHoc) ON DELETE CASCADE,
    FOREIGN KEY (NhanVienThuPhi) REFERENCES Admin(MaAdmin) ON DELETE SET NULL,
    INDEX idx_trangthaihocphi (TrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 13. BẢNG ĐIỂM DANH
-- =============================================
CREATE TABLE DiemDanh (
    MaDiemDanh INT AUTO_INCREMENT PRIMARY KEY,
    MaLop INT,
    MaHocSinh INT,
    NgayHoc DATE,
    TrangThai ENUM('CoMat','Vang','VangCoPhep') DEFAULT 'CoMat',
    GhiChu TEXT,
    FOREIGN KEY (MaLop) REFERENCES LopHoc(MaLop) ON DELETE CASCADE,
    FOREIGN KEY (MaHocSinh) REFERENCES HocSinh(MaHocSinh) ON DELETE CASCADE,
    UNIQUE KEY unique_diemdanh (MaLop, MaHocSinh, NgayHoc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 14. BẢNG THÔNG BÁO
-- =============================================
CREATE TABLE ThongBao (
    MaThongBao INT AUTO_INCREMENT PRIMARY KEY,
    TieuDe VARCHAR(200) NOT NULL,
    NoiDung TEXT NOT NULL,
    LoaiThongBao ENUM('HocPhi','LichHoc','DiemSo','Khac') DEFAULT 'Khac',
    MaNguoiGui INT,
    NgayGui DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaNguoiGui) REFERENCES NguoiDung(MaNguoiDung) ON DELETE SET NULL,
    INDEX idx_ngaygui (NgayGui)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 15. BẢNG GỬI THÔNG BÁO
-- =============================================
CREATE TABLE GuiThongBao (
    MaGui INT AUTO_INCREMENT PRIMARY KEY,
    MaThongBao INT,
    MaNguoiNhan INT,
    DaDoc BOOLEAN DEFAULT FALSE,
    NgayDoc DATETIME,
    FOREIGN KEY (MaThongBao) REFERENCES ThongBao(MaThongBao) ON DELETE CASCADE,
    FOREIGN KEY (MaNguoiNhan) REFERENCES NguoiDung(MaNguoiDung) ON DELETE CASCADE,
    INDEX idx_nguoinhan (MaNguoiNhan),
    INDEX idx_dadoc (DaDoc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 16. BẢNG BÀI TẬP
-- =============================================
CREATE TABLE BaiTap (
    MaBaiTap INT AUTO_INCREMENT PRIMARY KEY,
    MaLop INT,
    TieuDe VARCHAR(200) NOT NULL,
    NoiDung TEXT,
    NgayGiao DATETIME DEFAULT CURRENT_TIMESTAMP,
    HanNop DATE,
    MaGiaoVien INT,
    FOREIGN KEY (MaLop) REFERENCES LopHoc(MaLop) ON DELETE CASCADE,
    FOREIGN KEY (MaGiaoVien) REFERENCES GiaoVien(MaGiaoVien) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 17. BẢNG NỘP BÀI TẬP
-- =============================================
CREATE TABLE NopBaiTap (
    MaNop INT AUTO_INCREMENT PRIMARY KEY,
    MaBaiTap INT,
    MaHocSinh INT,
    NoiDung TEXT,
    FileDinhKem VARCHAR(255),
    NgayNop DATETIME DEFAULT CURRENT_TIMESTAMP,
    DiemSo DECIMAL(4,2),
    NhanXet TEXT,
    FOREIGN KEY (MaBaiTap) REFERENCES BaiTap(MaBaiTap) ON DELETE CASCADE,
    FOREIGN KEY (MaHocSinh) REFERENCES HocSinh(MaHocSinh) ON DELETE CASCADE,
    UNIQUE KEY unique_baitap_hocsinh (MaBaiTap, MaHocSinh)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 18. BẢNG BÁO CÁO
-- =============================================
CREATE TABLE BaoCao (
    MaBaoCao INT AUTO_INCREMENT PRIMARY KEY,
    TieuDe VARCHAR(200) NOT NULL,
    NoiDung TEXT,
    LoaiBaoCao ENUM('Diem','HocPhi','LopHoc','DiemDanh','Khac') DEFAULT 'Khac',
    MaNguoiTao INT,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaNguoiTao) REFERENCES NguoiDung(MaNguoiDung) ON DELETE SET NULL,
    INDEX idx_loaibaocao (LoaiBaoCao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- DỮ LIỆU MẪU
-- =============================================

-- Thêm Admin
INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai, DiaChi, VaiTro) VALUES
('Nguyễn Văn Admin', 'admin@englishcenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', '123 Nguyễn Văn Cừ, Q.5, TP.HCM', 'Admin'),
('Trần Thị Quản Lý', 'manager@englishcenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234568', '456 Lê Lợi, Q.1, TP.HCM', 'Admin');

INSERT INTO Admin (MaAdmin, QuyenHan, ChucVu) VALUES
(1, 'full', 'Giám đốc'),
(2, 'manager', 'Quản lý');

-- Thêm Giáo viên
INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai, DiaChi, VaiTro) VALUES
('Nguyễn Văn Bình', 'binh.gv@englishcenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345671', '789 Võ Văn Tần, Q.3, TP.HCM', 'GiaoVien'),
('Trần Thị Cúc', 'cuc.gv@englishcenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345672', '101 Nguyễn Huệ, Q.1, TP.HCM', 'GiaoVien'),
('Lê Hoàng Dũng', 'dung.gv@englishcenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345673', '202 Hai Bà Trưng, Q.1, TP.HCM', 'GiaoVien');

INSERT INTO GiaoVien (MaGiaoVien, ChuyenMon, TrinhDo, KinhNghiem, Luong) VALUES
(3, 'IELTS', 'Thạc sĩ', 5, 15000000),
(4, 'Giao tiếp', 'Cử nhân', 3, 12000000),
(5, 'TOEIC', 'Thạc sĩ', 4, 14000000);

-- Thêm Phụ huynh
INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai, DiaChi, VaiTro) VALUES
('Nguyễn Văn Cha1', 'cha1@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0923456781', '1A Lê Lợi, Q.1, TP.HCM', 'PhuHuynh'),
('Trần Thị Mẹ2', 'me2@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0923456782', '2B Nguyễn Trãi, Q.5, TP.HCM', 'PhuHuynh'),
('Lê Văn Cha3', 'cha3@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0923456783', '3C Võ Văn Kiệt, Q.5, TP.HCM', 'PhuHuynh');

INSERT INTO PhuHuynh (MaPhuHuynh, NgheNghiep) VALUES
(6, 'Kỹ sư'),
(7, 'Giáo viên'),
(8, 'Bác sĩ');

-- Thêm Học sinh
INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai, DiaChi, VaiTro) VALUES
('Nguyễn Thị An', 'an.nguyen@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567891', '1A Lê Lợi, Q.1, TP.HCM', 'HocSinh'),
('Lê Văn Bình', 'binh.le@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567892', '2B Nguyễn Trãi, Q.5, TP.HCM', 'HocSinh'),
('Phạm Thu Hằng', 'hang.pham@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567893', '3C Võ Văn Kiệt, Q.5, TP.HCM', 'HocSinh'),
('Trần Minh Đức', 'duc.tran@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567894', '4D Lý Thường Kiệt, Q.10, TP.HCM', 'HocSinh'),
('Võ Thị Lan', 'lan.vo@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567895', '5E Trần Hưng Đạo, Q.1, TP.HCM', 'HocSinh');

INSERT INTO HocSinh (MaHocSinh, MaPhuHuynh, NgaySinh, GioiTinh, LopHienTai, TruongHoc) VALUES
(9, 6, '2008-05-15', 'Nu', '10A1', 'THPT Nguyễn Thị Minh Khai'),
(10, 7, '2009-08-20', 'Nam', '9B2', 'THCS Lê Quý Đôn'),
(11, 6, '2007-03-10', 'Nu', '11A3', 'THPT Trần Đại Nghĩa'),
(12, 8, '2008-11-25', 'Nam', '10C1', 'THPT Gia Định'),
(13, 7, '2009-01-18', 'Nu', '9A1', 'THCS Nguyễn Văn Trỗi');

-- Thêm Khóa học
INSERT INTO KhoaHoc (TenKhoaHoc, MoTa, HocPhi, SoBuoi, ThoiLuong, TrangThai) VALUES
('Giao tiếp Cơ bản', 'Dành cho người mất gốc, tập trung Speaking & Listening. Xây dựng nền tảng từ vựng và ngữ pháp cơ bản.', 2900000, 24, 2, 'DangMo'),
('Luyện thi IELTS 6.0+', 'Lộ trình 8 tuần, chiến lược 4 kỹ năng có bài mock test. Đảm bảo đầu ra IELTS 6.0+', 5500000, 32, 2, 'DangMo'),
('TOEIC Foundation', 'Ôn trọng tâm Part 2,3,5,7; mẹo làm bài, bộ đề cập nhật. Mục tiêu 600+', 3400000, 24, 2, 'DangMo'),
('English for Kids', 'Tiếng Anh cho trẻ em 6-10 tuổi. Học qua game, nhạc, truyện tranh.', 2500000, 20, 1.5, 'DangMo'),
('Business English', 'Tiếng Anh thương mại cho dân văn phòng. Email, presentation, negotiation.', 4200000, 28, 2, 'DangMo');

-- Thêm Lớp học
INSERT INTO LopHoc (TenLop, MaKhoaHoc, PhongHoc, SiSoToiDa, SiSoHienTai, NgayBatDau, NgayKetThuc, TrangThai) VALUES
('IELTS 6.0+ (Tối T2-4-6)', 2, 'P201', 12, 3, '2025-01-15', '2025-03-15', 'DangHoc'),
('Giao tiếp Căn bản (Tối T3-5-7)', 1, 'P102', 12, 2, '2025-01-10', '2025-03-10', 'DangHoc'),
('TOEIC Foundation (Cuối tuần)', 3, 'P103', 12, 1, '2025-02-01', '2025-04-01', 'ChuaBatDau'),
('IELTS 6.0+ (Sáng T2-4-6)', 2, 'P201', 12, 0, '2025-03-01', '2025-05-01', 'ChuaBatDau');

-- Thêm Lịch học
INSERT INTO LichHoc (MaLop, ThuTrongTuan, GioBatDau, GioKetThuc) VALUES
(1, '2', '18:30', '20:30'),
(1, '4', '18:30', '20:30'),
(1, '6', '18:30', '20:30'),
(2, '3', '19:00', '21:00'),
(2, '5', '19:00', '21:00'),
(2, '7', '19:00', '21:00'),
(3, '7', '09:00', '11:00'),
(3, 'CN', '14:00', '16:00');

-- Phân công giáo viên
INSERT INTO PhanCongGiaoVien (MaLop, MaGiaoVien, NgayPhanCong, GhiChu) VALUES
(1, 3, '2025-01-10', 'Giáo viên chính'),
(2, 4, '2025-01-05', 'Giáo viên chính'),
(3, 5, '2025-01-20', 'Giáo viên chính');

-- Đăng ký lớp
INSERT INTO DangKyLop (MaHocSinh, MaLop, NgayDangKy, TrangThai, GhiChu) VALUES
(9, 1, '2025-01-05 10:30:00', 'DaXacNhan', 'Đã đóng học phí'),
(10, 1, '2025-01-06 14:20:00', 'DaXacNhan', 'Đã đóng học phí'),
(11, 1, '2025-01-07 09:15:00', 'DaXacNhan', 'Đã đóng học phí'),
(12, 2, '2025-01-04 16:45:00', 'DaXacNhan', 'Đã đóng học phí'),
(13, 2, '2025-01-05 11:00:00', 'DaXacNhan', 'Đã đóng học phí');

-- Thêm Điểm
INSERT INTO Diem (MaHocSinh, MaKhoaHoc, DiemChuyenCan, DiemGiuaKy, DiemCuoiKy, DiemTongKet, XepLoai, NhanXet) VALUES
(9, 2, 9.0, 8.0, 7.5, 8.0, 'Giỏi', 'Học sinh chăm chỉ, tiến bộ tốt'),
(10, 2, 8.5, 7.5, 8.0, 7.8, 'Khá', 'Cần cải thiện kỹ năng Writing'),
(11, 2, 10.0, 9.0, 9.5, 9.3, 'Xuất sắc', 'Học sinh xuất sắc nhất lớp'),
(12, 1, 8.0, NULL, NULL, NULL, NULL, 'Đang theo học'),
(13, 1, 9.0, NULL, NULL, NULL, NULL, 'Đang theo học');

-- Thêm Học phí
INSERT INTO HocPhi (MaHocSinh, MaKhoaHoc, SoTien, NgayTaoBill, NgayDong, HanDong, TrangThai, PhuongThucThanhToan, NhanVienThuPhi) VALUES
(9, 2, 5500000, '2025-01-05', '2025-01-05', '2025-01-12', 'DaDong', 'Chuyển khoản', 1),
(10, 2, 5500000, '2025-01-06', '2025-01-06', '2025-01-13', 'DaDong', 'Tiền mặt', 1),
(11, 2, 5500000, '2025-01-07', '2025-01-07', '2025-01-14', 'DaDong', 'Thẻ', 2),
(12, 1, 2900000, '2025-01-04', '2025-01-04', '2025-01-11', 'DaDong', 'Chuyển khoản', 1),
(13, 1, 2900000, '2025-01-05', NULL, '2025-01-12', 'ChuaDong', NULL, NULL);

-- Thêm Điểm danh
INSERT INTO DiemDanh (MaLop, MaHocSinh, NgayHoc, TrangThai, GhiChu) VALUES
(1, 9, '2025-01-15', 'CoMat', ''),
(1, 10, '2025-01-15', 'CoMat', ''),
(1, 11, '2025-01-15', 'CoMat', ''),
(1, 9, '2025-01-17', 'CoMat', ''),
(1, 10, '2025-01-17', 'Vang', 'Bị ốm'),
(1, 11, '2025-01-17', 'CoMat', ''),
(2, 12, '2025-01-10', 'CoMat', ''),
(2, 13, '2025-01-10', 'CoMat', '');

-- Thêm Bài tập
INSERT INTO BaiTap (MaLop, TieuDe, NoiDung, NgayGiao, HanNop, MaGiaoVien) VALUES
(1, 'IELTS Writing Task 2 - Education', 'Some people think that education should be free for all students. Discuss both views and give your opinion. (250 words minimum)', '2025-01-15 18:30:00', '2025-01-20', 3),
(1, 'IELTS Reading Practice', 'Complete Cambridge IELTS Book 15 Test 1 Reading passages', '2025-01-17 18:30:00', '2025-01-22', 3),
(2, 'Conversation Practice', 'Record a 3-minute introduction about yourself in English', '2025-01-10 19:00:00', '2025-01-15', 4);

-- Nộp bài tập
INSERT INTO NopBaiTap (MaBaiTap, MaHocSinh, NoiDung, NgayNop, DiemSo, NhanXet) VALUES
(1, 9, 'Education plays a vital role in society...', '2025-01-19 20:15:00', 7.5, 'Good structure, need to work on grammar'),
(1, 10, 'In modern world, education is...', '2025-01-20 10:30:00', 6.5, 'Ideas are good but vocabulary needs improvement'),
(3, 12, 'Recording submitted', '2025-01-14 21:00:00', 8.0, 'Clear pronunciation, good confidence');

-- Thêm Thông báo
INSERT INTO ThongBao (TieuDe, NoiDung, LoaiThongBao, MaNguoiGui, NgayGui) VALUES
('Nhắc nhở đóng học phí', 'Kính gửi Phụ huynh học sinh Võ Thị Lan, vui lòng đóng học phí trước ngày 12/01/2025 để đảm bảo quyền lợi học tập của con em.', 'HocPhi', 1, '2025-01-08 09:00:00'),
('Thông báo lịch thi giữa kỳ', 'Lớp IELTS 6.0+ sẽ có bài thi giữa kỳ vào ngày 25/01/2025. Vui lòng chuẩn bị kỹ lưỡng.', 'LichHoc', 1, '2025-01-10 14:00:00'),
('Chúc mừng học sinh xuất sắc', 'Chúc mừng em Phạm Thu Hằng đạt điểm tổng kết 9.3 - học sinh xuất sắc nhất lớp IELTS 6.0+', 'DiemSo', 2, '2025-01-20 10:00:00');

-- Gửi thông báo
INSERT INTO GuiThongBao (MaThongBao, MaNguoiNhan, DaDoc, NgayDoc) VALUES
(1, 7, TRUE, '2025-01-08 15:30:00'),
(1, 13, FALSE, NULL),
(2, 9, TRUE, '2025-01-10 20:00:00'),
(2, 10, TRUE, '2025-01-11 08:30:00'),
(2, 11, FALSE, NULL),
(3, 11, TRUE, '2025-01-20 18:00:00'),
(3, 6, TRUE, '2025-01-20 19:15:00');

-- Thêm Báo cáo
INSERT INTO BaoCao (TieuDe, NoiDung, LoaiBaoCao, MaNguoiTao, NgayTao) VALUES
('Báo cáo doanh thu tháng 1/2025', 'Tổng thu: 22,300,000 VNĐ\nĐã thu: 19,400,000 VNĐ\nCòn nợ: 2,900,000 VNĐ', 'HocPhi', 1, '2025-01-31 16:00:00'),
('Báo cáo điểm danh lớp IELTS 6.0+', 'Tỷ lệ tham gia: 94%\nSố buổi vắng: 2\nHọc sinh cần theo dõi: Lê Văn Bình', 'DiemDanh', 2, '2025-01-25 10:00:00');

-- =============================================
-- VIEW HỖ TRỢ TRUY VẤN
-- =============================================

-- View: Danh sách học sinh đầy đủ thông tin
CREATE VIEW v_DanhSachHocSinh AS
SELECT
    hs.MaHocSinh,
    nd.HoTen,
    nd.Email,
    nd.SoDienThoai,
    hs.NgaySinh,
    hs.GioiTinh,
    hs.LopHienTai,
    hs.TruongHoc,
    ph.HoTen as TenPhuHuynh,
    ph.SoDienThoai as SDTPhuHuynh,
    nd.TrangThai
FROM HocSinh hs
JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
LEFT JOIN NguoiDung ph ON hs.MaPhuHuynh = ph.MaNguoiDung;

-- View: Danh sách lớp học với thông tin chi tiết
CREATE VIEW v_DanhSachLopHoc AS
SELECT
    lh.MaLop,
    lh.TenLop,
    kh.TenKhoaHoc,
    kh.HocPhi,
    lh.PhongHoc,
    lh.SiSoHienTai,
    lh.SiSoToiDa,
    lh.NgayBatDau,
    lh.NgayKetThuc,
    lh.TrangThai,
    gv.HoTen as GiaoVienPhuTrach,
    GROUP_CONCAT(CONCAT(lc.ThuTrongTuan, ' ', lc.GioBatDau, '-', lc.GioKetThuc) SEPARATOR ', ') as LichHoc
FROM LopHoc lh
JOIN KhoaHoc kh ON lh.MaKhoaHoc = kh.MaKhoaHoc
LEFT JOIN PhanCongGiaoVien pc ON lh.MaLop = pc.MaLop
LEFT JOIN NguoiDung gv ON pc.MaGiaoVien = gv.MaNguoiDung
LEFT JOIN LichHoc lc ON lh.MaLop = lc.MaLop
GROUP BY lh.MaLop;

-- View: Bảng điểm học sinh
CREATE VIEW v_BangDiem AS
SELECT
    d.MaDiem,
    nd.HoTen as TenHocSinh,
    kh.TenKhoaHoc,
    d.DiemChuyenCan,
    d.DiemGiuaKy,
    d.DiemCuoiKy,
    d.DiemTongKet,
    d.XepLoai,
    d.NhanXet,
    d.NgayCapNhat
FROM Diem d
JOIN HocSinh hs ON d.MaHocSinh = hs.MaHocSinh
JOIN NguoiDung nd ON hs.MaHocSinh = nd.MaNguoiDung
JOIN KhoaHoc kh ON d.MaKhoaHoc = kh.MaKhoaHoc;

-- View: Học phí còn nợ
CREATE VIEW v_HocPhiNo AS
SELECT
    hp.MaHocPhi,
    nd.HoTen as TenHocSinh,
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
WHERE hp.TrangThai IN ('ChuaDong', 'QuaHan');

-- =============================================
-- TRIGGER TỰ ĐỘNG
-- =============================================

-- Trigger: Cập nhật sĩ số khi đăng ký lớp
DELIMITER //
CREATE TRIGGER trg_CapNhatSiSo_Insert
AFTER INSERT ON DangKyLop
FOR EACH ROW
BEGIN
    IF NEW.TrangThai = 'DaXacNhan' THEN
        UPDATE LopHoc
        SET SiSoHienTai = SiSoHienTai + 1
        WHERE MaLop = NEW.MaLop;
    END IF;
END//

-- Trigger: Cập nhật sĩ số khi hủy đăng ký
CREATE TRIGGER trg_CapNhatSiSo_Update
AFTER UPDATE ON DangKyLop
FOR EACH ROW
BEGIN
    IF OLD.TrangThai = 'DaXacNhan' AND NEW.TrangThai = 'DaHuy' THEN
        UPDATE LopHoc
        SET SiSoHienTai = SiSoHienTai - 1
        WHERE MaLop = NEW.MaLop;
    ELSEIF OLD.TrangThai != 'DaXacNhan' AND NEW.TrangThai = 'DaXacNhan' THEN
        UPDATE LopHoc
        SET SiSoHienTai = SiSoHienTai + 1
        WHERE MaLop = NEW.MaLop;
    END IF;
END//

-- Trigger: Tính điểm tổng kết tự động
CREATE TRIGGER trg_TinhDiemTongKet
BEFORE UPDATE ON Diem
FOR EACH ROW
BEGIN
    IF NEW.DiemChuyenCan IS NOT NULL AND NEW.DiemGiuaKy IS NOT NULL AND NEW.DiemCuoiKy IS NOT NULL THEN
        SET NEW.DiemTongKet = (NEW.DiemChuyenCan * 0.1) + (NEW.DiemGiuaKy * 0.3) + (NEW.DiemCuoiKy * 0.6);

        IF NEW.DiemTongKet >= 9.0 THEN
            SET NEW.XepLoai = 'Xuất sắc';
        ELSEIF NEW.DiemTongKet >= 8.0 THEN
            SET NEW.XepLoai = 'Giỏi';
        ELSEIF NEW.DiemTongKet >= 6.5 THEN
            SET NEW.XepLoai = 'Khá';
        ELSEIF NEW.DiemTongKet >= 5.0 THEN
            SET NEW.XepLoai = 'Trung bình';
        ELSE
            SET NEW.XepLoai = 'Yếu';
        END IF;
    END IF;
END//

-- Trigger: Cập nhật trạng thái học phí quá hạn
CREATE TRIGGER trg_KiemTraHocPhiQuaHan
BEFORE UPDATE ON HocPhi
FOR EACH ROW
BEGIN
    IF NEW.TrangThai = 'ChuaDong' AND NEW.HanDong < CURDATE() THEN
        SET NEW.TrangThai = 'QuaHan';
    END IF;
END//

DELIMITER ;

-- =============================================
-- STORED PROCEDURE
-- =============================================

DELIMITER //

-- Procedure: Đăng ký học sinh vào lớp
CREATE PROCEDURE sp_DangKyLop(
    IN p_MaHocSinh INT,
    IN p_MaLop INT,
    OUT p_KetQua VARCHAR(255)
)
BEGIN
    DECLARE v_SiSoHienTai INT;
    DECLARE v_SiSoToiDa INT;
    DECLARE v_DaDangKy INT;

    -- Kiểm tra đã đăng ký chưa
    SELECT COUNT(*) INTO v_DaDangKy
    FROM DangKyLop
    WHERE MaHocSinh = p_MaHocSinh AND MaLop = p_MaLop AND TrangThai != 'DaHuy';

    IF v_DaDangKy > 0 THEN
        SET p_KetQua = 'Học sinh đã đăng ký lớp này';
    ELSE
        -- Kiểm tra sĩ số
        SELECT SiSoHienTai, SiSoToiDa INTO v_SiSoHienTai, v_SiSoToiDa
        FROM LopHoc
        WHERE MaLop = p_MaLop;

        IF v_SiSoHienTai >= v_SiSoToiDa THEN
            SET p_KetQua = 'Lớp đã đầy';
        ELSE
            -- Đăng ký
            INSERT INTO DangKyLop (MaHocSinh, MaLop, TrangThai)
            VALUES (p_MaHocSinh, p_MaLop, 'DangCho');
            SET p_KetQua = 'Đăng ký thành công';
        END IF;
    END IF;
END//

-- Procedure: Tạo hóa đơn học phí
CREATE PROCEDURE sp_TaoHoaDonHocPhi(
    IN p_MaHocSinh INT,
    IN p_MaKhoaHoc INT,
    IN p_HanDong DATE
)
BEGIN
    DECLARE v_HocPhi DECIMAL(10,2);

    SELECT HocPhi INTO v_HocPhi FROM KhoaHoc WHERE MaKhoaHoc = p_MaKhoaHoc;

    INSERT INTO HocPhi (MaHocSinh, MaKhoaHoc, SoTien, HanDong, TrangThai)
    VALUES (p_MaHocSinh, p_MaKhoaHoc, v_HocPhi, p_HanDong, 'ChuaDong');
END//

DELIMITER ;

-- =============================================
-- HOÀN TẤT
-- =============================================

SELECT 'Database QuanLyHocThem đã được tạo thành công!' as ThongBao;
SELECT COUNT(*) as SoLuongHocSinh FROM HocSinh;
SELECT COUNT(*) as SoLuongGiaoVien FROM GiaoVien;
SELECT COUNT(*) as SoLuongLopHoc FROM LopHoc;
SELECT COUNT(*) as SoLuongKhoaHoc FROM KhoaHoc;
