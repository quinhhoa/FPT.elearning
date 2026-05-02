<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Hỗ trợ tìm kiếm cơ bản (Tên bài thi)
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM exams";
if (!empty($search)) {
    $sql .= " WHERE title LIKE '%$search%'";
}
$sql .= " ORDER BY id ASC";

$result = $conn->query($sql);
$exams = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format ngày giờ: 21/07/2026 00:00 AM
        $row['start_time_formatted'] = $row['start_time'] ? date('d/m/Y h:i A', strtotime($row['start_time'])) : '';
        $row['end_time_formatted'] = $row['end_time'] ? date('d/m/Y h:i A', strtotime($row['end_time'])) : '';
        $exams[] = $row;
    }
    echo json_encode($exams);
} else {
    echo json_encode(["error" => true, "message" => "Lỗi CSDL: " . $conn->error]);
}
$conn->close();
?>