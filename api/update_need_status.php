<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($id <= 0 || empty($status)) {
    echo json_encode(["success" => false, "message" => "Thiếu dữ liệu (ID hoặc Status)"]);
    exit;
}

$sql = "UPDATE training_needs SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cập nhật thành công!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi thực thi: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Lỗi CSDL: " . $conn->error]);
}

$conn->close();
?>