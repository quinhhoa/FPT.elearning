<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$data = [];

if ($course_id > 0) {
    // Ưu tiên 1: Dùng Prepared Statement để bảo mật tuyệt đối
    // Ưu tiên 2: Sắp xếp theo Chương (module_id) và Thứ tự (sort_order)
    $sql = "SELECT * FROM lessons WHERE course_id = ? ORDER BY module_id ASC, sort_order ASC, id ASC";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Kiểm tra xem có kết quả không trước khi fetch
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $data[] = $row; 
            }
        }
        $stmt->close();
    }
}

// Luôn trả về mảng (dù rỗng) để Javascript không bị lỗi phân tích cú pháp (parse error)
echo json_encode($data);

$conn->close();
?>