<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Nhận dữ liệu
$data = json_decode(file_get_contents('php://input'), true);
$course_id = isset($data['course_id']) ? (int)$data['course_id'] : 0;
// Lấy ID học viên đang đăng nhập (nếu không có mặc định là 1 để test)
$user_id = $_SESSION['user_id'] ?? 1; 

if ($course_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID khóa học.']);
    exit;
}

// 1. Kiểm tra xem khóa học này là Tự do hay Kiểm duyệt
$sql_course = "SELECT registration_type FROM courses WHERE id = $course_id";
$result = $conn->query($sql_course);
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Khóa học không tồn tại.']);
    exit;
}
$course = $result->fetch_assoc();
$reg_type = $course['registration_type'];

// 2. THIẾT LẬP TRẠNG THÁI MẶC ĐỊNH CHUẨN NGHIỆP VỤ
$status = ($reg_type === 'Kiểm duyệt') ? 'Chờ duyệt' : 'Đang học';

// 3. Kiểm tra chống đăng ký trùng
$sql_check = "SELECT id FROM course_student WHERE course_id = $course_id AND user_id = $user_id";
if ($conn->query($sql_check)->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Bạn đã đăng ký khóa học này rồi.']);
    exit;
}

// 4. Lưu vào Database
$sql_insert = "INSERT INTO course_student (course_id, user_id, progress_percentage, status) 
               VALUES ($course_id, $user_id, 0, '$status')";

if ($conn->query($sql_insert)) {
    // Trả về câu thông báo tương ứng
    $msg = ($status === 'Chờ duyệt') ? 'Đã gửi yêu cầu! Vui lòng chờ Admin phê duyệt.' : 'Đăng ký thành công! Bạn có thể vào học ngay.';
    echo json_encode(['success' => true, 'message' => $msg]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi DB: ' . $conn->error]);
}
$conn->close();
?>