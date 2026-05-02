<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$modules = [];

if ($course_id > 0) {
    // Lấy các học phần của khóa học, sắp xếp theo thứ tự (sort_order) hoặc mới nhất
    $sql = "SELECT * FROM course_modules WHERE course_id = ? ORDER BY sort_order ASC, id ASC";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $modules[] = $row;
        }
        $stmt->close();
    }
}

echo json_encode($modules);
$conn->close();
?>