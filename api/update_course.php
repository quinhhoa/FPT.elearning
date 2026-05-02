<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        die("<script>alert('Lỗi: Không tìm thấy ID khóa học!'); window.history.back();</script>");
    }

    // 1. Nhận dữ liệu Tab Thông tin chung
    $course_code = trim($_POST['course_code'] ?? ''); // BỔ SUNG MÃ KHÓA HỌC
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // BỔ SUNG BẢO VỆ ẢNH
    $thumbnail_url = trim($_POST['thumbnail_url'] ?? '');
    if (empty($thumbnail_url)) {
        $thumbnail_url = 'https://placehold.co/600x400/115293/FFFFFF?text=ELEARNING+FPT';
    }

    $students = isset($_POST['students']) ? (int)$_POST['students'] : 0;
    
    // BỔ SUNG XỬ LÝ THỜI GIAN HỌC
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
    $registration_type = $_POST['registration_type'] ?? 'Không cho đăng ký';
    $status = isset($_POST['status']) ? 1 : 0; 

    // 2. Nhận dữ liệu Tab Điều kiện tham gia
    $condition_exam_id = !empty($_POST['condition_exam_id']) ? (int)$_POST['condition_exam_id'] : NULL;
    $condition_course_id = !empty($_POST['condition_course_id']) ? (int)$_POST['condition_course_id'] : NULL;
    $condition_survey_id = !empty($_POST['condition_survey_id']) ? (int)$_POST['condition_survey_id'] : NULL;
    $condition_cert_id = !empty($_POST['condition_cert_id']) ? (int)$_POST['condition_cert_id'] : NULL;

    // 3. Nhận dữ liệu Tab Điều kiện hoàn thành
    $required_learning_time = isset($_POST['required_learning_time']) ? (int)$_POST['required_learning_time'] : 0;

    // Câu lệnh UPDATE (Đã thêm course_code)
    $sql = "UPDATE courses SET 
                course_code = ?,
                title = ?, 
                thumbnail_url = ?, 
                description = ?, 
                students = ?, 
                time_range = ?, 
                category_id = ?, 
                registration_type = ?, 
                status = ?,
                condition_exam_id = ?, 
                condition_course_id = ?, 
                condition_survey_id = ?, 
                condition_certificate_id = ?, 
                required_learning_time = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("<script>alert('Lỗi CSDL: " . $conn->error . "'); window.history.back();</script>");
    }

    // Bind 15 tham số (Thêm chữ 's' ở đầu cho course_code)
    $stmt->bind_param(
        "ssssisisiiiiiii", 
        $course_code, $title, $thumbnail_url, $description, $students, $time_range, 
        $category_id, $registration_type, $status,
        $condition_exam_id, $condition_course_id, $condition_survey_id, 
        $condition_cert_id, $required_learning_time, $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật khóa học thành công!'); window.location.href = '../admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>