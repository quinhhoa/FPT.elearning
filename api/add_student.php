<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Nhận dữ liệu JSON từ giao diện gửi xuống
$data = json_decode(file_get_contents('php://input'), true);
$course_id = isset($data['course_id']) ? (int)$data['course_id'] : 0;
$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;

if ($course_id === 0 || $user_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
    exit;
}

// Kiểm tra xem học viên này đã có trong khóa học chưa
$check_sql = "SELECT id FROM course_student WHERE course_id = $course_id AND user_id = $user_id";
if ($conn->query($check_sql)->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Học viên này đã có trong danh sách!']);
    exit;
}

// Thực hiện gán học viên. Vì Admin tự tay gán nên trạng thái mặc định là 'Đang học' (Tương đương Đã duyệt)
$sql = "INSERT INTO course_student (course_id, user_id, progress_percentage, status) 
        VALUES ($course_id, $user_id, 0, '1')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Thêm học viên thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi Database: ' . $conn->error]);
}

$conn->close();
?>