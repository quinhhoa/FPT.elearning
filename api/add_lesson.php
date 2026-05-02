<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = (int)$_POST['course_id'];
    $module_id = (int)$_POST['module_id'];
    $lesson_type = $_POST['lesson_type'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $is_required = isset($_POST['is_required']) ? 1 : 0;
    
    $file_url = "";
    $duration = $_POST['duration'] ?? NULL;
    $file_size = $_POST['file_size'] ?? NULL;
    $embed_code = $_POST['embed_code'] ?? NULL;

    // XỬ LÝ UPLOAD FILE
    if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == 0) {
        $file_name = $_FILES['lesson_file']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Thư mục lưu trữ (đường dẫn tương đối từ file api này lùi ra 1 cấp)
        $upload_dir = '../uploads/lessons/';
        
        // Tự động tạo thư mục nếu chưa có
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Đổi tên file để không bị trùng (vd: document_1700000000.pdf)
        $new_name = $lesson_type . "_" . time() . "." . $file_ext;
        $target_file = $upload_dir . $new_name;

        // Lưu file từ bộ nhớ tạm vào thư mục thật
        if (move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)) {
            // Lưu đường dẫn sạch vào CSDL để HTML dễ đọc
            $file_url = 'uploads/lessons/' . $new_name; 
        } else {
            die("<script>alert('Lỗi: Hệ thống không thể lưu file. Hãy kiểm tra thư mục uploads/lessons/'); window.history.back();</script>");
        }
    }

    // LƯU DATA VÀO DATABASE
    $sql = "INSERT INTO lessons (course_id, module_id, lesson_type, title, description, file_url, duration, file_size, embed_code, is_required) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if($stmt) {
        $stmt->bind_param("iisssssssi", $course_id, $module_id, $lesson_type, $title, $description, $file_url, $duration, $file_size, $embed_code, $is_required);
        if ($stmt->execute()) {
            echo "<script>alert('Lưu bài giảng thành công!'); window.location.href='../admin_lessons.php?course_id=$course_id';</script>";
        } else {
            echo "<script>alert('Lỗi CSDL: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Lỗi truy vấn SQL!'); window.history.back();</script>";
    }
}
?>