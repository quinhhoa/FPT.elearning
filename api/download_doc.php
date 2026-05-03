<?php
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 1. Lấy thông tin file từ DB
    $stmt = $conn->prepare("SELECT file_name, title FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doc = $result->fetch_assoc();

    if ($doc && !empty($doc['file_name'])) {
        $file_path = '../uploads/lessons/' . $doc['file_name'];

        if (file_exists($file_path)) {
            // 2. Cập nhật số lượt tải (+1)
            $update = $conn->prepare("UPDATE documents SET downloads = downloads + 1 WHERE id = ?");
            $update->bind_param("i", $id);
            $update->execute();

            // 3. Tiến hành gửi file về trình duyệt
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $doc['title'] . '.' . pathinfo($doc['file_name'], PATHINFO_EXTENSION) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        }
    }
}
echo "Lỗi: Không tìm thấy file hoặc tài liệu không tồn tại.";
?>