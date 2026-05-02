<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$data = [];

if ($course_id > 0) {
    // JOIN bảng course_student với users bằng user_id để lấy thông tin hiển thị
    $sql = "SELECT cs.*, u.fullname, u.email, u.department, u.username 
            FROM course_student cs
            JOIN users u ON cs.user_id = u.id 
            WHERE cs.course_id = ? 
            ORDER BY cs.id ASC";
            
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
    }
}
echo json_encode($data);
$conn->close();
?>