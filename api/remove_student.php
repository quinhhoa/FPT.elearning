<?php
header('Content-Type: application/json; charset=utf-8');

// Dò tìm file db.php
$possible_paths = ['../db.php', 'db.php', '../../db.php'];
$db_path = '';
foreach ($possible_paths as $path) {
    if (file_exists($path)) { $db_path = $path; break; }
}
if (!empty($db_path)) require_once $db_path;

// Lấy dữ liệu gửi từ giao diện (Javascript fetch)
$data = json_decode(file_get_contents("php://input"), true);
$course_id = isset($data['course_id']) ? (int)$data['course_id'] : 0;
$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;

if ($course_id > 0 && $user_id > 0) {
    // ĐÃ SỬA: Xóa dựa trên user_id thay vì username
    $sql = "DELETE FROM course_student WHERE course_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ii", $course_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Đã loại bỏ học viên']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa khỏi CSDL']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi câu lệnh SQL']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID khóa học hoặc ID học viên']);
}

$conn->close();
?>