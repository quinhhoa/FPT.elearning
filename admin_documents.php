<?php
session_start();
// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. KẾT NỐI CƠ SỞ DỮ LIỆU
require_once 'api/db.php'; 

// 3. TRUY VẤN DANH MỤC (Lấy danh mục để đổ vào thẻ <select>)
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
    <title>Quản lý tài liệu - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #73879C; font-size: 13px; }
        .table-header th { background-color: #f5f7fa; border: 1px solid #E6E9ED; color: #2A3F54; font-weight: bold; padding: 10px 5px; }
        input, select { border: 1px solid #ccc; outline: none; transition: border 0.2s; }
        input:focus, select:focus { border-color: #1ABB9C; }
    </style>
</head>
<body class="bg-[#F7F7F7]">

<div class="flex">
    <?php include 'includes/admin_sidebar.php'; ?>

    <div class="flex-1 ml-[200px] min-h-screen flex flex-col relative">
        <!-- HEADER -->
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
            <div class="flex justify-end gap-2 mb-6 relative">
                <button onclick="location.href='admin_add_document.php'" class="bg-[#1ABB9C] text-white px-4 py-1.5 rounded text-[12px] font-bold hover:bg-[#16a085] flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Thêm mới
                </button>
                <div class="relative group">
                    <button class="bg-[#BDC3C7] text-white px-4 py-1.5 rounded text-[12px] font-bold hover:bg-gray-400 flex items-center gap-2 shadow-sm">
                        Hành động <i class="fa-solid fa-caret-down"></i>
                    </button>
                    <!-- Menu Hành động Dropdown -->
                    <div class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 shadow-lg rounded hidden group-hover:block z-50">
                        <ul class="text-left text-xs text-gray-700 py-1">
                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100"><i class="fa-solid fa-check text-green-500 mr-2"></i>Duyệt chọn</a></li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-red-50 text-red-600" onclick="deleteSelected()"><i class="fa-solid fa-trash mr-2"></i>Xóa chọn</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- BỘ LỌC TÌM KIẾM -->
            <div class="bg-white p-5 rounded border border-[#E6E9ED] mb-6 shadow-sm flex flex-wrap gap-4 items-center">
                <input type="text" id="search-title" placeholder="Tên tài liệu" class="px-3 py-1.5 w-64 rounded text-gray-600">
                <select id="search-category" class="px-3 py-1.5 w-56 rounded text-gray-600 bg-white">
                    <option value="">--- Danh mục ---</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="loadDocuments()" class="bg-[#1ABB9C] text-white px-5 py-1.5 rounded font-bold hover:bg-[#16a085] flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>
            </div>

            <!-- BẢNG DANH SÁCH -->
            <div class="bg-white rounded border border-[#E6E9ED] shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="table-header">
                        <tr>
                            <th class="text-center w-10"><input type="checkbox" id="check-all" onclick="toggleAllCheckboxes(this)"></th>
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
                    <tbody id="document-list" class="text-[#73879C]">
                        <tr><td colspan="10" class="text-center py-4 text-gray-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Đang tải dữ liệu...</td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
    // --- LẤY DỮ LIỆU TỪ CSDL ---
    async function loadDocuments() {
        const title = document.getElementById('search-title').value;
        const categoryId = document.getElementById('search-category').value;
        const tbody = document.getElementById('document-list');
        
        tbody.innerHTML = '<tr><td colspan="10" class="text-center py-8 text-gray-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Đang tải dữ liệu...</td></tr>';

        try {
            const url = `api/get_documents.php?title=${encodeURIComponent(title)}&category_id=${categoryId}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center py-8 italic text-red-500">Không tìm thấy tài liệu phù hợp.</td></tr>';
                return;
            }

            let html = '';
            data.forEach(doc => {
                let iconHtml = '<i class="fa-solid fa-file text-gray-500 text-xl"></i>';
                if(doc.file_type === 'pdf') iconHtml = '<i class="fa-solid fa-file-pdf text-red-600 text-xl"></i>';
                if(doc.file_type === 'docx' || doc.file_type === 'doc') iconHtml = '<i class="fa-solid fa-file-word text-blue-600 text-xl"></i>';
                if(doc.file_type === 'xlsx' || doc.file_type === 'xls') iconHtml = '<i class="fa-solid fa-file-excel text-green-600 text-xl"></i>';

                html += `
                <tr class="hover:bg-gray-50 border-b transition-colors">
                    <td class="p-3 text-center border-r"><input type="checkbox" class="doc-checkbox" value="${doc.id}"></td>
                    <td class="p-3 text-center border-r">${iconHtml}</td>
                    <td class="p-3 border-r">
                        <a href="#" class="text-[#337AB7] font-medium hover:underline">${doc.title}</a>
                        <div class="text-[10px] text-gray-400 mt-0.5">${doc.category_name || 'Chưa phân loại'}</div>
                    </td>
                    <td class="p-3 border-r italic text-[11px]">${doc.created_at}</td>
                    <td class="p-3 border-r">${doc.file_size || '0 Kb'}</td>
                    <td class="p-3 border-r text-center">${doc.views || 0}</td>
                    <td class="p-3 border-r text-center">${doc.downloads || 0}</td>
                    <td class="p-3 border-r text-center"><i class="fa-regular ${doc.is_processed ? 'fa-square-check text-[#1ABB9C]' : 'fa-square text-gray-300'} text-lg"></i></td>
                    <td class="p-3 border-r text-center"><i class="fa-regular ${doc.status === 1 ? 'fa-square-check text-[#1ABB9C]' : 'fa-square text-gray-300'} text-lg"></i></td>
                    
                    <!-- CỘT HÀNH ĐỘNG ĐÃ SỬA THÀNH CLICK DROPDOWN -->
                    <td class="p-3 text-center flex justify-center gap-1 overflow-visible">
                        <button onclick="editDocument(${doc.id})" class="bg-[#337AB7] text-white px-3 py-1 rounded text-[11px] hover:bg-blue-600 shadow-sm">Sửa</button>
                        
                        <button onclick="deleteDocument(${doc.id})" class="bg-[#D9534F] text-white px-3 py-1 rounded text-[11px] hover:bg-red-600 shadow-sm">Xóa</button>
                        
                        <!-- Nút Khác (Dropdown bằng Click) -->
                        <div class="relative cursor-pointer action-dropdown-container">
                            <button type="button" onclick="toggleDocActionMenu('doc-action-${doc.id}')" class="bg-[#BDC3C7] text-white px-2 py-1 rounded text-[11px] hover:bg-gray-500 shadow-sm flex items-center gap-1 transition-colors">
                                Khác <i class="fa-solid fa-caret-down"></i>
                            </button>
                            
                            <!-- Menu xổ xuống (Thêm ID động và bỏ group-hover) -->
                            <div id="doc-action-${doc.id}" class="doc-action-menu absolute right-0 top-full mt-1 w-28 bg-white border border-gray-200 shadow-xl rounded z-50 hidden text-left py-1 text-[#2A3F54]">
                                <a href="javascript:void(0)" onclick="toggleDocStatus(${doc.id}, ${doc.status})" class="block px-4 py-1.5 hover:bg-gray-100 text-[11px] transition-colors">
                                    ${doc.status == 1 ? 'Bỏ duyệt' : 'Duyệt'}
                                </a>
                                <a href="api/download_doc.php?id=${doc.id}" target="_blank" class="block px-4 py-1.5 hover:bg-gray-100 text-[11px] text-[#337AB7] transition-colors">
                                    Tải về
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center py-8 text-red-500 font-bold">Lỗi kết nối API lấy dữ liệu.</td></tr>';
        }
    }

    // --- ĐIỀU KHIỂN CLICK MENU DROPDOWN "KHÁC" ---
    function toggleDocActionMenu(menuId) {
        // Đóng tất cả các menu đang mở khác
        document.querySelectorAll('.doc-action-menu').forEach(menu => {
            if (menu.id !== menuId) {
                menu.classList.add('hidden');
            }
        });
        
        // Bật/Tắt menu được click
        const targetMenu = document.getElementById(menuId);
        if (targetMenu) {
            targetMenu.classList.toggle('hidden');
        }
    }

    // --- TỰ ĐỘNG ĐÓNG MENU KHI CLICK RA NGOÀI ---
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.action-dropdown-container')) {
            document.querySelectorAll('.doc-action-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // --- MODAL CONTROLS ---
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.getElementById(modalId).classList.add('flex');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.getElementById(modalId).classList.remove('flex');
    }

    // --- SUBMIT FORM THÊM MỚI ---
    async function submitAddForm(e) {
        e.preventDefault();
        const title = document.getElementById('new-title').value;
        const category_id = document.getElementById('new-category').value;
        
        try {
            const res = await fetch('api/add_document.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, category_id })
            });
            const result = await res.json();
            if(result.success) {
                alert("Thêm tài liệu thành công!");
                closeModal('addModal');
                document.getElementById('add-doc-form').reset();
                loadDocuments();
            } else {
                alert("Lỗi: " + result.message);
            }
        } catch(err) {
            alert("Lỗi kết nối API thêm mới.");
        }
    }

    // --- XÓA TÀI LIỆU ---
    async function deleteDocument(id) {
        if(confirm("Bạn có chắc chắn muốn xóa tài liệu này?")) {
            try {
                const res = await fetch(`api/delete_document.php?id=${id}`);
                const result = await res.json();
                if(result.success) {
                    loadDocuments();
                } else { alert("Lỗi xóa: " + result.message); }
            } catch(err) { alert("Lỗi kết nối API xóa."); }
        }
    }

    // --- BỎ DUYỆT / DUYỆT TÀI LIỆU ---
    async function toggleDocStatus(id, currentStatus) {
        const newStatus = currentStatus == 1 ? 0 : 1;
        const actionText = currentStatus == 1 ? "bỏ duyệt" : "duyệt";
        
        if(confirm(`Bạn có chắc chắn muốn ${actionText} tài liệu này?`)) {
            try {
                alert(`Đã gửi yêu cầu ${actionText} thành công! (Cần tạo API xử lý)`);
                loadDocuments(); 
            } catch(err) {
                alert("Lỗi kết nối API.");
            }
        }
    }

    // --- CHECKBOX CONTROLS ---
    function toggleAllCheckboxes(source) {
        checkboxes = document.querySelectorAll('.doc-checkbox');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    // Chạy load data khi mở trang
    window.onload = loadDocuments;
</script>

</body>
</html>