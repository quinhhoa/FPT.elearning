<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$title = isset($_GET['title']) ? '%' . trim($_GET['title']) . '%' : '%%';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

$sql = "SELECT d.*, c.name as category_name 
        FROM documents d 
        LEFT JOIN document_categories c ON d.category_id = c.id 
        WHERE d.title LIKE ?";

// Nếu có chọn danh mục để lọc
if ($category_id !== '') {
    $sql .= " AND d.category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $title, $category_id);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $title);
}

$documents = [];
if ($stmt && $stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        // Format lại ngày tháng cho đẹp
        $row['created_at'] = date('d/m/Y H:i A', strtotime($row['created_at']));
        $documents[] = $row;
    }
    $stmt->close();
}

echo json_encode($documents);
$conn->close();
?>