<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Nhận dữ liệu từ màn hình Admin gửi xuống
$data = json_decode(file_get_contents('php://input'), true);
$course_id = isset($data['course_id']) ? (int)$data['course_id'] : 0;
$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
// Trạng thái Admin truyền xuống sẽ là 'Đang học' (Duyệt) hoặc 'Chờ duyệt' (Bỏ duyệt)
$status = $conn->real_escape_string($data['status'] ?? ''); 

if ($course_id === 0 || $user_id === 0 || empty($status)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu bắt buộc!']);
    exit;
}

// CẬP NHẬT THẲNG VÀO DATABASE
$sql = "UPDATE course_student SET status = '$status' WHERE course_id = $course_id AND user_id = $user_id";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi DB: ' . $conn->error]);
}
$conn->close();
?>