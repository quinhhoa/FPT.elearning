<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Lấy ID khóa học cần xóa
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 1. Thực hiện lệnh xóa trong Database
    $sql = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Trả về kết quả thành công cho Javascript
        echo json_encode(["success" => true, "message" => "Đã xóa khóa học thành công!"]);
    } else {
        // Trả về lỗi nếu không xóa được (ví dụ do ràng buộc khóa ngoại)
        echo json_encode(["success" => false, "message" => "Lỗi hệ thống: " . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID khóa học không hợp lệ."]);
}

$conn->close();
?>