<?php
session_start();
// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài liệu - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #73879C; font-size: 13px; }
        /* Căn chỉnh header và bảng */
        .table-header th { background-color: #f5f7fa; border: 1px solid #E6E9ED; color: #2A3F54; font-weight: bold; padding: 10px 5px; }
        input, select { border: 1px solid #ccc; outline: none; transition: border 0.2s; }
        input:focus, select:focus { border-color: #1ABB9C; }
    </style>
</head>
<body class="bg-[#F7F7F7]">

<div class="flex">
    <!-- SIDEBAR ADMIN (Rộng 200px theo file bạn gửi) -->
    <?php include 'includes/admin_sidebar.php'; ?>

    <!-- NỘI DUNG CHÍNH - Thêm ml-[200px] để không bị lệch -->
    <div class="flex-1 ml-[200px] min-h-screen flex flex-col">
        
        <!-- HEADER - Đã sửa lỗi căn lề và màu sắc -->
        <header class="h-12 bg-[#EDEDED] border-b border-[#D9DEE4] flex justify-between items-center px-4 sticky top-0 z-40">
            <div class="flex items-center text-[#73879C]">
                <i class="fa-solid fa-bars mr-4 cursor-pointer hover:text-[#1ABB9C]"></i>
                <nav class="text-[11px] flex items-center gap-1">
                    <i class="fa-solid fa-house"></i> 
                    <span class="hover:text-[#1ABB9C] cursor-pointer">DashBoard</span> 
                    <i class="fa-solid fa-chevron-right text-[8px] mx-1 opacity-50"></i> 
                    <span class="text-gray-500">Quản lý tài liệu</span>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 cursor-pointer group">
                    <span class="bg-[#1ABB9C] text-white w-6 h-6 flex items-center justify-center rounded-full text-[10px] font-bold">AD</span>
                    <span class="text-xs font-medium group-hover:text-[#1ABB9C]">Quỳnh Hoa</span>
                    <i class="fa-solid fa-caret-down text-[10px]"></i>
                </div>
            </div>
        </header>

        <main class="p-6">
            <!-- NÚT THÊM MỚI & HÀNH ĐỘNG -->
            <div class="flex justify-end gap-2 mb-6">
                <button class="bg-[#1ABB9C] text-white px-4 py-1.5 rounded text-[12px] font-bold hover:bg-[#16a085] flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Thêm mới
                </button>
                <button class="bg-[#BDC3C7] text-white px-4 py-1.5 rounded text-[12px] font-bold hover:bg-gray-400 flex items-center gap-2 shadow-sm">
                    Hành động <i class="fa-solid fa-caret-down"></i>
                </button>
            </div>

            <!-- BỘ LỌC TÌM KIẾM -->
            <div class="bg-white p-5 rounded border border-[#E6E9ED] mb-6 shadow-sm flex flex-wrap gap-4 items-center">
                <input type="text" placeholder="Tên tài liệu" class="px-3 py-1.5 w-64 rounded text-gray-600">
                <select class="px-3 py-1.5 w-56 rounded text-gray-400 bg-white">
                    <option>--- Danh mục ---</option>
                    <option>Quy định học tập FPT</option>
                    <option>CTTV</option>
                </select>
                <button class="bg-[#1ABB9C] text-white px-5 py-1.5 rounded font-bold hover:bg-[#16a085] flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>
            </div>

            <!-- BẢNG DANH SÁCH -->
            <div class="bg-white rounded border border-[#E6E9ED] shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="table-header">
                        <tr>
                            <th class="text-center w-10"><input type="checkbox"></th>
                            <th class="w-12 text-center">Icon</th>
                            <th>Tên tài liệu</th>
                            <th class="w-44">Ngày tạo</th>
                            <th class="w-28">Dung lượng</th>
                            <th class="w-20 text-center">Lượt xem</th>
                            <th class="w-20 text-center">Lượt tải</th>
                            <th class="w-24 text-center">Xử lý</th>
                            <th class="w-24 text-center">Trạng thái</th>
                            <th class="w-48 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-[#73879C]">
                        <!-- Dữ liệu mẫu 1 -->
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-3 text-center border-r"><input type="checkbox"></td>
                            <td class="p-3 text-center border-r text-blue-800"><i class="fa-solid fa-file-pdf text-xl"></i></td>
                            <td class="p-3 border-r"><a href="#" class="text-[#337AB7] font-medium hover:underline">Dog whisperer - Cesar Millan</a></td>
                            <td class="p-3 border-r italic text-[11px]">07/07/2016 10:07:45 SA</td>
                            <td class="p-3 border-r">3,07 Mb</td>
                            <td class="p-3 border-r text-center">9</td>
                            <td class="p-3 border-r text-center">11</td>
                            <td class="p-3 border-r text-center"><i class="fa-regular fa-square-check text-[#1ABB9C] text-lg"></i></td>
                            <td class="p-3 border-r text-center"><i class="fa-regular fa-square-check text-[#1ABB9C] text-lg"></i></td>
                            <td class="p-3 text-center flex justify-center gap-1">
                                <button class="bg-[#3498DB] text-white px-3 py-1 rounded text-[11px] hover:bg-blue-600">Sửa</button>
                                <button class="bg-[#E74C3C] text-white px-3 py-1 rounded text-[11px] hover:bg-red-600">Xóa</button>
                                <button class="bg-[#BDC3C7] text-white px-2 py-1 rounded text-[11px] hover:bg-gray-400">Khác <i class="fa-solid fa-caret-down"></i></button>
                            </td>
                        </tr>
                        <!-- Dữ liệu mẫu 2 -->
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-3 text-center border-r"><input type="checkbox"></td>
                            <td class="p-3 text-center border-r text-blue-800"><i class="fa-solid fa-file-pdf text-xl"></i></td>
                            <td class="p-3 border-r"><a href="#" class="text-[#337AB7] font-medium hover:underline">Test tuna26</a></td>
                            <td class="p-3 border-r italic text-[11px]">23/06/2016 3:27:32 CH</td>
                            <td class="p-3 border-r">380,55 Kb</td>
                            <td class="p-3 border-r text-center">3</td>
                            <td class="p-3 border-r text-center">2</td>
                            <td class="p-3 border-r text-center"><i class="fa-regular fa-square-check text-[#1ABB9C] text-lg"></i></td>
                            <td class="p-3 border-r text-center"><i class="fa-regular fa-square-check text-[#1ABB9C] text-lg"></i></td>
                            <td class="p-3 text-center flex justify-center gap-1">
                                <button class="bg-[#3498DB] text-white px-3 py-1 rounded text-[11px] hover:bg-blue-600">Sửa</button>
                                <button class="bg-[#E74C3C] text-white px-3 py-1 rounded text-[11px] hover:bg-red-600">Xóa</button>
                                <button class="bg-[#BDC3C7] text-white px-2 py-1 rounded text-[11px] hover:bg-gray-400">Khác <i class="fa-solid fa-caret-down"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

</body>
</html>