CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Active, 0: Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Thêm tài khoản admin: 
INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `email`, `department`, `status`, `role`) 
VALUES (2, 'hoantq29', 1 ,'Nguyễn Thị Quỳnh Hoa', 'hoantq29@fpt.com.vn', 'FPT IS - Dev', 1, 'admin');

-- 2. BẢNG KHÓA HỌC 
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `expire_text` varchar(100) DEFAULT NULL,
  `progress_percentage` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `students` int(11) DEFAULT 0,
  `time_range` varchar(100) DEFAULT NULL,
  `registration_type` varchar(100) DEFAULT 'Đăng ký tự do',
  `condition_exam_id` int(11) DEFAULT NULL,
  `condition_course_id` int(11) DEFAULT NULL,
  `condition_survey_id` int(11) DEFAULT NULL,
  `condition_certificate_id` int(11) DEFAULT NULL,
  `required_learning_time` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  `time_type` varchar(50) DEFAULT 'days',
  `duration_days` int(11) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_code` (`course_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thêm dữ liệu mẫu cho khóa học
INSERT INTO `courses` 
(`id`, `course_code`, `title`, `thumbnail_url`, `expire_text`, `progress_percentage`, `description`, `students`, `time_range`, `registration_type`, `condition_exam_id`, `condition_course_id`, `condition_survey_id`, `condition_certificate_id`, `required_learning_time`, `status`, `category_id`, `time_type`, `duration_days`, `start_date`, `end_date`) 
VALUES
(3, 'Course-3', 'Kỹ năng Phân tích nghiệp vụ (BA)', 'https://images.unsplash.com/photo-1531497865144-0462e29c0138', 'Không giới hạn', 30, 'Cung cấp kỹ năng lấy yêu cầu (elicitation), viết tài liệu, mô hình hóa quy trình.', 210, 'Không giới hạn', 'Đăng ký tự do', NULL, NULL, NULL, NULL, 0, 1, 1, 'days', 0, NULL, NULL),
(4, 'Course-4', 'Bảo mật Thông tin doanh nghiệp', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d', 'Còn lại 5 ngày', 100, 'Nâng cao nhận thức về an toàn bảo mật thông tin, phòng chống rủi ro an ninh mạng.', 450, '01/04/2026 - 30/04/2026', 'Kiểm duyệt', NULL, NULL, NULL, NULL, 0, 1, 1, 'days', 0, NULL, NULL);

-- 3. BẢNG CHƯƠNG MỤC KHÓA HỌC (Các phần của khóa học)
CREATE TABLE `course_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL COMMENT 'Liên kết với id của bảng courses',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự hiển thị của chương học',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Hiển thị, 0: Ẩn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. BẢNG BÀI GIẢNG (Nội dung chi tiết bên trong từng chương)
CREATE TABLE `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL COMMENT 'ID của khóa học',
  `module_id` int(11) DEFAULT NULL COMMENT 'ID của chương học/module',
  `lesson_type` varchar(50) DEFAULT 'video' COMMENT 'Loại bài học: video, document, virtual_class, test...',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_url` text DEFAULT NULL COMMENT 'Đường dẫn file lưu trữ (mp4, pdf...)',
  `duration` varchar(50) DEFAULT NULL COMMENT 'Thời lượng bài học',
  `file_size` varchar(50) DEFAULT NULL COMMENT 'Dung lượng file',
  `embed_code` text DEFAULT NULL COMMENT 'Mã nhúng (ví dụ iframe Youtube/Zoom)',
  `virtual_class_type` varchar(50) DEFAULT NULL COMMENT 'Nền tảng lớp ảo: zoom, teams, google_meet...',
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `presenter` varchar(255) DEFAULT NULL COMMENT 'Người trình bày / Giảng viên',
  `test_id` int(11) DEFAULT NULL COMMENT 'Liên kết với ID bài thi nếu loại bài học là test',
  `is_required` tinyint(1) DEFAULT 1 COMMENT '1: Bắt buộc học, 0: Tự chọn',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- 7. BẢNG DANH MỤC KHÓA HỌC (categories)
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thêm dữ liệu mẫu cho Danh mục (Khớp với màn hình Danh sách khóa học của bạn)
INSERT INTO `categories` (`id`, `name`) 
VALUES
(1, 'Công nghệ thông tin (IT)'),
(2, 'Kỹ năng mềm'),
(3, 'Nghiệp vụ Quản lý & BA'),
(4, 'Đào tạo tân binh (Onboarding)');

-- 8. BẢNG HỌC VIÊN - KHÓA HỌC (course_student)
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


-- 9. Tạo bảng Tài liệu
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT 'Liên kết với bảng document_categories',
  `title` varchar(255) NOT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Hiện, 0: Ẩn',
  `created_at` datetime DEFAULT current_timestamp(),
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thêm dữ liệu mẫu cho tài liệu
INSERT INTO `documents` (`title`, `category_id`, `file_type`, `file_size`, `created_at`) VALUES
('Sổ tay bảo mật thông tin FPT IS', 1, 'pdf', '3.45 Mb', '2026-05-01 08:30:00'),
('Hướng dẫn viết tài liệu SRS cho BA', 2, 'docx', '1.20 Mb', '2026-05-02 09:15:00'),
('Kỹ năng giao tiếp khách hàng', 3, 'pdf', '5.10 Mb', '2026-05-03 14:20:00');

-- 10. Tạo bảng danh mục tài liệu
CREATE TABLE `document_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Thêm dữ liệu mẫu cho danh mục
INSERT INTO `document_categories` (`id`, `name`) VALUES
(1, 'Quy định học tập FPT'),
(2, 'Tài liệu chuyên môn IT'),
(3, 'Tài liệu Kỹ năng mềm'),
(4, 'Hội nhập nhân viên mới');
