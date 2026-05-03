<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Dò tìm file db.php
$possible_paths = ['../db.php', 'db.php', '../../db.php', '../includes/db.php'];
$db_path = '';
foreach ($possible_paths as $path) {
    if (file_exists($path)) { $db_path = $path; break; }
}
if (empty($db_path)) {
    echo json_encode(["error" => "Không tìm thấy file db.php"]);
    exit;
}
require_once $db_path;

try {
    $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    $searchParam = "%$keyword%";
    $users = [];

    // ĐÃ SỬA: So sánh `id` của users với `user_id` của course_student
    $sql = "SELECT id, username, fullname, email, department 
            FROM users 
            WHERE (fullname LIKE ? OR username LIKE ? OR email LIKE ?)
            AND role = 'student'
            AND id NOT IN (SELECT user_id FROM course_student WHERE course_id = ?)
            LIMIT 15";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Lỗi SQL: " . $conn->error);

    $stmt->bind_param("sssi", $searchParam, $searchParam, $searchParam, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) { 
        $users[] = $row; 
    }
    
    echo json_encode($users);
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
$conn->close();
?>