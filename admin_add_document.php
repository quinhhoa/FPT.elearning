<?php
session_start();
// KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'api/db.php'; 

// TRUY VẤN DANH MỤC
$categories = [];
$cat_query = "SELECT id, name FROM document_categories";
if ($result = $conn->query($cat_query)) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm mới tài liệu - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Nhúng CKEditor 4 -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
        body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #73879C; font-size: 13px; }
        input[type="text"], select { transition: border 0.2s; }
        input[type="text"]:focus, select:focus { border-color: #1ABB9C; outline: none; box-shadow: 0 0 3px rgba(26,187,156,0.3); }
    </style>
</head>
<body class="bg-[#F7F7F7]">

<div class="flex">
    <!-- SIDEBAR -->
    <?php include 'includes/admin_sidebar.php'; ?>

    <div class="flex-1 ml-[200px] min-h-screen flex flex-col relative">
        <!-- HEADER -->
        <header class="h-12 bg-[#EDEDED] border-b border-[#D9DEE4] flex justify-between items-center px-4 sticky top-0 z-40">
            <div class="flex items-center text-[#73879C]">
                <i class="fa-solid fa-bars mr-4 cursor-pointer hover:text-[#1ABB9C]"></i>
                <nav class="text-[11px] flex items-center gap-1">
                    <i class="fa-solid fa-house"></i> 
                    <span class="hover:text-[#1ABB9C] cursor-pointer" onclick="location.href='index.php'">DashBoard</span> 
                    <i class="fa-solid fa-chevron-right text-[8px] mx-1 opacity-50"></i> 
                    <span class="hover:text-[#1ABB9C] cursor-pointer" onclick="location.href='admin_documents.php'">Quản lý tài liệu</span>
                    <i class="fa-solid fa-chevron-right text-[8px] mx-1 opacity-50"></i> 
                    <span class="text-gray-500">Thêm mới</span>
                </nav>
            </div>
        </header>

        <main class="p-6">
            <div class="bg-white rounded border border-[#E6E9ED] shadow-sm overflow-hidden">
                
                <!-- TITLE -->
                <div class="border-b border-[#E6E9ED] px-4 py-3 flex justify-between items-center bg-[#fbfbfb]">
                    <h2 class="font-bold text-[#D9534F] uppercase text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-circle-plus"></i> THÊM MỚI/CẬP NHẬT TÀI LIỆU
                    </h2>
                    <button onclick="location.href='admin_documents.php'" class="text-gray-500 hover:text-gray-800 text-lg"><i class="fa-solid fa-angle-left mr-1 text-sm"></i> Quay lại</button>
                </div>

                <!-- FORM -->
                <form action="api/add_document_process.php" method="POST" enctype="multipart/form-data" class="text-[13px]">
                    
                    <div class="flex border-b border-[#E6E9ED]">
                        <div class="w-1/4 bg-[#f9f9f9] p-4 text-right font-medium text-[#73879C] border-r border-[#E6E9ED]">
                            Kiểu tài liệu: <span class="text-red-500">*</span>
                        </div>
                        <div class="w-3/4 p-4">
                            <select name="category_id" required class="w-full border border-gray-300 px-3 py-1.5 rounded text-gray-600 bg-white">
                                <option value="">--- Danh mục ---</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex border-b border-[#E6E9ED]">
                        <div class="w-1/4 bg-[#f9f9f9] p-4 text-right font-medium text-[#73879C] border-r border-[#E6E9ED]">
                            Tên tài liệu: <span class="text-red-500">*</span>
                        </div>
                        <div class="w-3/4 p-4">
                            <input type="text" name="title" required class="w-full border border-gray-300 px-3 py-1.5 rounded text-gray-700">
                        </div>
                    </div>

                    <div class="flex border-b border-[#E6E9ED]">
                        <div class="w-1/4 bg-[#f9f9f9] p-4 text-right font-medium text-[#73879C] border-r border-[#E6E9ED]">
                            Mô tả tài liệu:
                        </div>
                        <div class="w-3/4 p-4">
                            <textarea name="description" id="editor"></textarea>
                        </div>
                    </div>

                    <div class="flex border-b border-[#E6E9ED]">
                        <div class="w-1/4 bg-[#f9f9f9] p-4 text-right font-medium text-[#73879C] border-r border-[#E6E9ED]">
                            Tập tin tải về: <span class="text-red-500">*</span>
                        </div>
                        <div class="w-3/4 p-4">
                            <input type="text" id="file-name-display" readonly placeholder="Chưa có file nào được chọn" class="w-full border border-gray-300 px-3 py-1.5 rounded bg-gray-50 text-gray-500 mb-2 cursor-not-allowed">
                            
                            <!-- Đã xóa nút Upload ở đây -->
                            <div class="flex gap-2">
                                <label class="bg-[#5CB85C] text-white px-4 py-1.5 rounded text-[12px] cursor-pointer hover:bg-green-600 transition font-bold flex items-center gap-1 border border-[#4cae4c]">
                                    <i class="fa-solid fa-plus"></i> Chọn tập tin
                                    <input type="file" name="document_file" id="file-upload" class="hidden" onchange="updateFileName(this)">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Nút Lưu -->
                    <div class="p-4 bg-gray-50 text-center border-t border-[#E6E9ED]">
                        <button type="submit" class="bg-[#337AB7] text-white px-8 py-2 rounded font-bold hover:bg-[#286090] shadow-sm transition-colors text-sm">
                            <i class="fa-solid fa-save mr-2"></i> LƯU TÀI LIỆU
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</div>

<script>
    // ĐÃ THÊM: versionCheck: false để ẩn cái biển báo màu đỏ đi
    CKEDITOR.replace('editor', {
        versionCheck: false, 
        height: 200,
        toolbar: [
            ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'],
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],
            '/',
            ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'],
            ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'],
            ['Link', 'Unlink', 'Anchor'],
            ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'],
            '/',
            ['Styles', 'Format', 'Font', 'FontSize'],
            ['TextColor', 'BGColor'],
            ['Maximize', 'ShowBlocks']
        ]
    });

    function updateFileName(input) {
        const displayBox = document.getElementById('file-name-display');
        if (input.files && input.files.length > 0) {
            displayBox.value = input.files[0].name;
            displayBox.classList.remove('text-gray-500', 'bg-gray-50');
            displayBox.classList.add('text-[#337AB7]', 'font-medium', 'bg-white');
        } else {
            displayBox.value = "";
            displayBox.classList.add('text-gray-500', 'bg-gray-50');
            displayBox.classList.remove('text-[#337AB7]', 'font-medium', 'bg-white');
        }
    }
</script>

</body>
</html>