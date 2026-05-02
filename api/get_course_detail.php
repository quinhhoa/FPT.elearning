<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? 1; 
$view = isset($_GET['view']) ? $_GET['view'] : 'catalog'; 

$sql = "";

if ($view === 'admin') {
    // 1. MÀN ADMIN: Hiển thị TOÀN BỘ
    $sql = "SELECT * FROM courses ORDER BY id DESC";
} 
else if ($view === 'enrolled') {
    // 2. MÀN HỌC VIÊN CHUNG: Dùng LEFT JOIN để lấy tất cả khóa học đang Public.
    // Cột enroll_status sẽ chứa 'Đang học'/'Chờ duyệt' (nếu đã đăng ký), hoặc NULL (nếu chưa)
    $sql = "SELECT c.*, cs.progress_percentage, cs.status as enroll_status 
            FROM courses c
            LEFT JOIN course_student cs ON c.id = cs.course_id AND cs.user_id = $user_id
            WHERE c.status = 1 
            ORDER BY c.id DESC";
} 
else {
    // 3. MÀN DANH SÁCH CATALOG: Chỉ hiện tự do & kiểm duyệt
    $sql = "SELECT * FROM courses 
            WHERE registration_type IN ('Đăng ký tự do', 'Kiểm duyệt') 
            AND status = 1 
            ORDER BY id DESC";
}

$result = $conn->query($sql);
$courses = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (!isset($row['progress_percentage'])) {
            $row['progress_percentage'] = 0;
        }
        $courses[] = $row;
    }
}

echo json_encode($courses);
$conn->close();
?>