<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    // 1. Tìm xem bài giảng này có lưu file vật lý không (Video, PDF...) để xóa cho sạch rác
    $sql_find = "SELECT file_url FROM lessons WHERE id = ?";
    $stmt_find = $conn->prepare($sql_find);
    if ($stmt_find) {
        $stmt_find->bind_param("i", $id);
        $stmt_find->execute();
        $result = $stmt_find->get_result();
        if ($row = $result->fetch_assoc()) {
            $file_path = '../' . $row['file_url'];
            if (!empty($row['file_url']) && file_exists($file_path)) {
                unlink($file_path); // Xóa file khỏi thư mục uploads/lessons/
            }
        }
        $stmt_find->close();
    }

    // 2. Xóa bản ghi trong Database
    $sql_del = "DELETE FROM lessons WHERE id = ?";
    $stmt_del = $conn->prepare($sql_del);
    if ($stmt_del) {
        $stmt_del->bind_param("i", $id);
        if ($stmt_del->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        $stmt_del->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn SQL']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
$conn->close();
?>