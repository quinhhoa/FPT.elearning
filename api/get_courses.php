<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? 0; 
$view = isset($_GET['view']) ? $_GET['view'] : 'catalog'; 

$sql = "";

if ($view === 'admin') {
    $sql = "SELECT * FROM courses ORDER BY id DESC";
} 
else if ($view === 'enrolled') {
    // JOIN với bảng course_student (số ít) dùng user_id
    $sql = "SELECT c.id, c.title, c.thumbnail_url, c.time_range, 
                   cs.progress_percentage, cs.status as enroll_status 
            FROM courses c
            INNER JOIN course_student cs ON c.id = cs.course_id 
            WHERE cs.user_id = $user_id AND c.status = 1
            ORDER BY c.id DESC";
} 
else {
    $sql = "SELECT c.*, cs.status as enroll_status 
            FROM courses c
            LEFT JOIN course_student cs ON c.id = cs.course_id AND cs.user_id = $user_id
            WHERE c.registration_type IN ('Đăng ký tự do', 'Kiểm duyệt') 
            AND c.status = 1 
            ORDER BY c.id DESC";
}

$result = $conn->query($sql);
$courses = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['progress_percentage'] = $row['progress_percentage'] ?? 0;
        $courses[] = $row;
    }
}
echo json_encode($courses);
$conn->close();
?>