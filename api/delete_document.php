<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $sql = "DELETE FROM documents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Xóa tài liệu thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa khỏi CSDL']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi SQL']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID tài liệu không hợp lệ']);
}

$conn->close();
?>