-- 1. BẢNG NGƯỜI DÙNG (Quản lý Admin và Học viên)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `department` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. BẢNG KHÓA HỌC (Thông tin hiển thị ở màn Dashboard)
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `registration_type` varchar(100) DEFAULT 'Bài thi cho khóa học',
  `time_range` varchar(100) DEFAULT 'Không giới hạn',
  `progress_percentage` int(11) DEFAULT 0,
  `students` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. BẢNG CHƯƠNG MỤC KHÓA HỌC (Các phần của khóa học)
CREATE TABLE `course_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `order_index` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. BẢNG BÀI GIẢNG (Nội dung chi tiết bên trong từng chương)
CREATE TABLE `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lesson_type` enum('Video','PDF','Test') DEFAULT 'Video',
  `content_url` varchar(255) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `order_index` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. BẢNG NHU CẦU ĐÀO TẠO
CREATE TABLE `training_needs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `target` text DEFAULT NULL,
  `duration` decimal(5,2) DEFAULT '0.00',
  `students` int(11) DEFAULT '0',
  `unit` varchar(255) DEFAULT '',
  `teacher` varchar(100) DEFAULT '',
  `department` varchar(100) DEFAULT '',
  `proposer` varchar(100) DEFAULT '',
  `manager` varchar(100) DEFAULT '',
  `competency` varchar(255) DEFAULT '',
  `time` varchar(50) DEFAULT '',
  `status` varchar(50) DEFAULT 'Chưa gửi',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. BẢNG BÀI THI
CREATE TABLE `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `exam_type` varchar(100) DEFAULT '',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- 1. BẢNG DANH MỤC KHÓA HỌC (categories)
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm dữ liệu mẫu cho Danh mục (Khớp với màn hình Danh sách khóa học của bạn)
INSERT INTO `categories` (`name`) VALUES 
('Chuyên môn IT'),
('Kỹ năng mềm'),
('Quản lý dự án'),
('Hội nhập nhân viên');

-- --------------------------------------------------------

-- 2. BẢNG TIẾN ĐỘ HỌC VIÊN - KHÓA HỌC (course_student)
CREATE TABLE `course_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `progress_percentage` int(11) DEFAULT 0,
  `status` enum('Đang học','Đã hoàn thành') DEFAULT 'Đang học',
  `enroll_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  -- Ràng buộc tránh 1 người đăng ký 1 khóa nhiều lần
  UNIQUE KEY `unique_enrollment` (`course_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm dữ liệu mẫu: Giả sử user_id = 1 (Tài khoản của bạn) đang học 2 khóa
INSERT INTO `course_student` (`course_id`, `user_id`, `progress_percentage`, `status`) VALUES
(1, 1, 100, 'Đã hoàn thành'), -- Khóa số 1 đã học xong 100%
(2, 1, 45, 'Đang học'),       -- Khóa số 2 đang học dở 45%
(3, 1, 0, 'Đang học');        -- Khóa số 3 vừa đăng ký, chưa học (0%)

-- Tạo bảng Danh mục tài liệu
CREATE TABLE `document_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho danh mục
INSERT INTO `document_categories` (`id`, `name`) VALUES
(1, 'Quy định học tập FPT'),
(2, 'Tài liệu chuyên môn IT'),
(3, 'Tài liệu Kỹ năng mềm'),
(4, 'Hội nhập nhân viên mới');

-- Tạo bảng Tài liệu
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'pdf',
  `file_size` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '0 Kb',
  `views` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `is_processed` tinyint(1) DEFAULT 1,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `document_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho tài liệu
INSERT INTO `documents` (`title`, `category_id`, `file_type`, `file_size`, `created_at`) VALUES
('Sổ tay bảo mật thông tin FPT IS', 1, 'pdf', '3.45 Mb', '2026-05-01 08:30:00'),
('Hướng dẫn viết tài liệu SRS cho BA', 2, 'docx', '1.20 Mb', '2026-05-02 09:15:00'),
('Kỹ năng giao tiếp khách hàng', 3, 'pdf', '5.10 Mb', '2026-05-03 14:20:00');

ALTER TABLE `documents` ADD `file_name` varchar(255) NULL;

ALTER TABLE `documents` ADD `file_type` varchar(20) DEFAULT 'pdf';

ALTER TABLE `documents` ADD `description` text NULL;

ALTER TABLE `documents` 
ADD `views` INT DEFAULT 0,
ADD `downloads` INT DEFAULT 0;

