<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $course_code = trim($_POST['course_code'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // --- TỰ ĐỘNG GÁN ẢNH MẶC ĐỊNH ---
    $thumbnail_url = trim($_POST['thumbnail_url'] ?? '');
    if (empty($thumbnail_url)) {
        $thumbnail_url = 'https://placehold.co/600x400/115293/FFFFFF?text=ELEARNING+FPT';
    }

    $students = isset($_POST['students']) ? (int)$_POST['students'] : 0;
    
    // --- XỬ LÝ LOGIC THỜI GIAN ---
    $time_type = $_POST['time_type'] ?? 'days';
    
    if ($time_type === 'days') {
        $duration = (int)($_POST['duration_days'] ?? 0);
        $time_range = ($duration > 0) ? $duration . ' ngày' : 'Không giới hạn';
    } else {
        $start = $_POST['start_date'] ?? '';
        $end = $_POST['end_date'] ?? '';
        
        if (!empty($start) && !empty($end)) {
            $start_fmt = date('d/m/Y', strtotime($start));
            $end_fmt = date('d/m/Y', strtotime($end));
            $time_range = $start_fmt . ' - ' . $end_fmt;
        } else {
            $time_range = 'Không giới hạn';
        }
    }
    
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 1;
    
    // BIẾN NÀY ĐÃ TỰ ĐỘNG NHẬN "Kiểm duyệt" TỪ FORM HTML NẾU BẠN CHỌN
    $registration_type = $_POST['registration_type'] ?? 'Không cho đăng ký';
    $status = isset($_POST['status']) ? 1 : 0; 

    $condition_exam_id = !empty($_POST['condition_exam_id']) ? (int)$_POST['condition_exam_id'] : NULL;
    $condition_course_id = !empty($_POST['condition_course_id']) ? (int)$_POST['condition_course_id'] : NULL;
    $condition_survey_id = !empty($_POST['condition_survey_id']) ? (int)$_POST['condition_survey_id'] : NULL;
    $condition_cert_id = !empty($_POST['condition_cert_id']) ? (int)$_POST['condition_cert_id'] : NULL;
    $required_learning_time = isset($_POST['required_learning_time']) ? (int)$_POST['required_learning_time'] : 0;

    $expire_text = "Vừa mới thêm"; 
    $progress_percentage = 0; 

    $sql = "INSERT INTO courses (
                course_code, title, thumbnail_url, expire_text, progress_percentage, 
                description, students, time_range, category_id, registration_type, status,
                condition_exam_id, condition_course_id, condition_survey_id, 
                condition_certificate_id, required_learning_time
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        // Nâng cấp: Hiển thị popup lỗi chính xác để dễ debug
        die("<script>alert('Lỗi CSDL (Thiếu cột): " . addslashes($conn->error) . "'); window.history.back();</script>");
    }

    $stmt->bind_param(
        "ssssisisisiiiiii", 
        $course_code, $title, $thumbnail_url, $expire_text, $progress_percentage, 
        $description, $students, $time_range, $category_id, $registration_type, $status,
        $condition_exam_id, $condition_course_id, $condition_survey_id, 
        $condition_cert_id, $required_learning_time
    );

    if ($stmt->execute()) {
        echo "<script>alert('Lưu khóa học thành công!'); window.location.href = '../admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . addslashes($conn->error) . "'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>