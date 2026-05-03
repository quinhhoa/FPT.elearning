<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    
    // Khởi tạo mặc định
    $file_type = 'pdf';
    $file_size = '0 Kb';
    $final_file_name = ''; // Tên file sẽ lưu vào CSDL
    
    // 1. XỬ LÝ UPLOAD FILE VẬT LÝ VÀO THƯ MỤC
    if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] === UPLOAD_ERR_OK) {
        
        // Đường dẫn tới thư mục upload của bạn (tính từ file api/ ra ngoài)
        $upload_dir = '../uploads/lessons/'; 
        
        // Lấy tên gốc và đuôi file
        $original_name = $_FILES['document_file']['name'];
        $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        
        // Chỉ định loại file để hiển thị icon
        $file_type = in_array($file_ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp4']) ? $file_ext : 'pdf';
        
        // Tính dung lượng file
        $size_in_bytes = $_FILES['document_file']['size'];
        if ($size_in_bytes > 1048576) {
            $file_size = round($size_in_bytes / 1048576, 2) . ' Mb';
        } else {
            $file_size = round($size_in_bytes / 1024, 2) . ' Kb';
        }
        
        // Tạo tên file mới giống với hệ thống của bạn (VD: document_1777475648.pdf)
        $new_file_name = 'document_' . time() . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;
        
        // Di chuyển file từ bộ nhớ tạm vào thư mục uploads/lessons
        if (move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
            $final_file_name = $new_file_name; // Upload thành công, gán tên để lưu DB
        }
    }

    // 2. LƯU THÔNG TIN VÀO DATABASE
    if (!empty($title) && $category_id > 0) {
        $sql = "INSERT INTO documents (title, category_id, file_name, file_type, file_size, description) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sissss", $title, $category_id, $final_file_name, $file_type, $file_size, $description);
            if ($stmt->execute()) {
                $stmt->close();
                echo "<script>alert('Lưu tài liệu và upload file thành công!'); window.location.href='../admin_documents.php';</script>";
                exit;
            } else {
                echo "<script>alert('Lỗi lưu CSDL: " . $stmt->error . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Lỗi SQL: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Vui lòng nhập đủ Tên tài liệu và Danh mục!'); window.history.back();</script>";
    }
}
$conn->close();
?>