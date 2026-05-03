<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Đọc dữ liệu JSON từ giao diện gửi lên
$data = json_decode(file_get_contents("php://input"), true);

$title = isset($data['title']) ? trim($data['title']) : '';
$category_id = isset($data['category_id']) ? (int)$data['category_id'] : 0;

if (empty($title) || $category_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ tên và chọn danh mục.']);
    exit;
}

// Mặc định file_type là pdf cho sinh động
$file_type = 'pdf';
$file_size = rand(100, 999) . ' Kb'; // Random dung lượng ảo

$sql = "INSERT INTO documents (title, category_id, file_type, file_size) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("siss", $title, $category_id, $file_type, $file_size);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm tài liệu thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi lưu CSDL']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi câu lệnh SQL']);
}

$conn->close();
?>