<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$keyword = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '%%';
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Tìm user trong danh bạ NHƯNG loại trừ những người đã có trong course_students của khóa này
$sql = "SELECT id, username, fullname, email, department 
        FROM users 
        WHERE (fullname LIKE ? OR username LIKE ? OR email LIKE ?)
        AND username NOT IN (SELECT username FROM course_students WHERE course_id = ?)";

$stmt = $conn->prepare($sql);
if($stmt) {
    $stmt->bind_param("sssi", $keyword, $keyword, $keyword, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while($row = $result->fetch_assoc()) { $users[] = $row; }
    echo json_encode($users);
    $stmt->close();
}
$conn->close();
?>