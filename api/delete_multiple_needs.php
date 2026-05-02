<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận mảng ID từ Javascript gửi lên dưới dạng JSON
    $data = json_decode(file_get_contents('php://input'), true);
    $ids = $data['ids'] ?? [];

    if (empty($ids) || !is_array($ids)) {
        echo json_encode(['success' => false, 'message' => 'Không có dữ liệu để xóa.']);
        exit;
    }

    // Ép kiểu tất cả ID về số nguyên để chống SQL Injection
    $safe_ids = array_map('intval', $ids);
    $id_string = implode(',', $safe_ids);

    // BẢO MẬT KÉP: Chỉ cho phép xóa dữ liệu của chính mình VÀ ở trạng thái Chưa gửi / Bỏ duyệt
    $fullname = $_SESSION['fullname'] ?? 'Học viên';
    $fullname_safe = $conn->real_escape_string($fullname);

    $sql = "DELETE FROM training_needs 
            WHERE id IN ($id_string) 
            AND proposer = '$fullname_safe' 
            AND status IN ('Chưa gửi', 'Bỏ duyệt')";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi xóa CSDL: ' . $conn->error]);
    }
}
$conn->close();
?>