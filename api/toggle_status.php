<?php
header('Content-Type: application/json');
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? (int)$_GET['status'] : 0;

if ($id > 0) {
    // Cập nhật trạng thái mới vào CSDL
    $sql = "UPDATE courses SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID không hợp lệ"]);
}
$conn->close();
?>