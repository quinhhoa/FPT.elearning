
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($course_id > 0 && !empty($title)) {
        $sql = "INSERT INTO course_modules (course_id, title, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("iss", $course_id, $title, $description);
            if ($stmt->execute()) {
                // Lưu xong thì tự động quay lại trang giáo trình của khóa học đó
                echo "<script>window.location.href='../admin_lessons.php?course_id=$course_id';</script>";
            } else {
                echo "<script>alert('Lỗi khi lưu: " . $conn->error . "'); window.history.back();</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Vui lòng nhập đầy đủ tên học phần!'); window.history.back();</script>";
    }
}
$conn->close();
?>