<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$fullname = $_SESSION['fullname'] ?? 'Học viên';
$role = $_SESSION['role'] ?? 'student'; 
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&background=115293&color=fff";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Hòm thư - FPT IS E-Learning</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { fptblue: "#115293", fptdark: "#003366", fptorange: "#f97316" } } }
    }
  </script>
  <style>
    .transition-all-300 { transition: all 0.3s ease-in-out; }
    .sidebar-text { white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.2s; }
    .expanded .sidebar-text { opacity: 1; visibility: visible; }
    .mail-active { color: #115293; font-weight: bold; background-color: #f8fafc; border-right: 3px solid #115293; }
  </style>
</head>
<body class="bg-gray-100 overflow-x-hidden">

<div class="flex min-h-screen relative">
  <!-- SIDEBAR DÙNG CHUNG -->
  <?php include 'includes/sidebar.php'; ?>

  <div id="content-wrapper" class="flex-1 flex flex-col ml-16 transition-all-300 w-full">
    <!-- HEADER ĐỒNG BỘ -->
    <header class="bg-white h-16 flex justify-between items-center px-6 border-b z-40 sticky top-0">
      <div class="flex items-center gap-3 cursor-pointer" onclick="location.href='index.php'">
        <img src="https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg" class="h-8 w-auto" alt="Logo"/>
        <div class="border-l pl-3 border-gray-200">
          <p class="text-[10px] text-gray-500 italic leading-tight">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-base uppercase leading-tight">Elearning</p>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <input type="text" placeholder="Tìm kiếm thư..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64 shadow-sm"/>
        <div class="relative cursor-pointer">
          <i class="far fa-bell text-gray-600 text-xl mt-1"></i>
          <span class="absolute -top-1 -right-2 bg-fptorange text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">1</span>
        </div>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border shadow-sm" alt="Avatar"/>
      </div>
    </header>

    <!-- GIAO DIỆN HÒM THƯ -->
    <main class="p-4 flex-1 flex flex-col">
      <div class="flex flex-1 gap-4">
        
        <!-- CỘT 1: MENU THƯ -->
        <aside class="w-64 bg-white border border-gray-200 rounded shadow-sm flex flex-col">
          <div class="p-4">
            <button class="w-full bg-[#337ab7] text-white py-2 rounded text-sm font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 uppercase tracking-tight">
              <i class="fa-regular fa-envelope"></i> Thư mới
            </button>
          </div>
          <ul class="text-[13px] text-gray-600">
            <li class="px-4 py-3 flex justify-between items-center mail-active cursor-pointer">
              <div class="flex items-center gap-3"><i class="fa-solid fa-inbox w-4"></i> Hộp thư đến</div>
              <span class="text-[10px]">1</span>
            </li>
            <li class="px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
              <div class="flex items-center gap-3"><i class="fa-solid fa-pen-to-square w-4"></i> Bản nháp</div>
              <span class="text-[10px]">1</span>
            </li>
            <li class="px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
              <div class="flex items-center gap-3"><i class="fa-solid fa-paper-plane w-4"></i> Thư đã gửi</div>
              <span class="text-[10px]">0</span>
            </li>
            <li class="px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
              <div class="flex items-center gap-3"><i class="fa-solid fa-trash-can w-4"></i> Đã xóa bỏ</div>
              <span class="text-[10px]">0</span>
            </li>
            <li class="px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
              <div class="flex items-center gap-3"><i class="fa-solid fa-ban w-4"></i> Thùng rác</div>
              <span class="text-[10px]">0</span>
            </li>
            <li class="px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer border-t mt-2">
              <div class="flex items-center gap-3"><i class="fa-solid fa-box-archive w-4"></i> Lưu trữ</div>
              <span class="text-[10px]">0</span>
            </li>
          </ul>
        </aside>

        <!-- CỘT 2: DANH SÁCH THƯ ĐẾN -->
        <section class="w-72 bg-white border border-gray-200 rounded shadow-sm flex flex-col">
          <div class="p-3 border-b bg-gray-50 flex items-center gap-2 font-bold text-fptblue text-sm">
            <i class="fa-solid fa-envelope-open-text"></i> Hộp thư đến
          </div>
          <div class="flex-1 overflow-y-auto">
            <!-- Một item thư -->
            <div class="p-4 border-b hover:bg-blue-50 cursor-pointer relative bg-white transition-colors">
              <div class="flex items-start gap-3">
                <input type="checkbox" class="mt-1">
                <div class="flex-1">
                  <p class="text-[13px] font-bold text-gray-800">Nguyễn Bá Bình</p>
                  <p class="text-[12px] text-gray-500 truncate w-40">Thư chào mừng học viên mới...</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- CỘT 3: CHI TIẾT/SOẠN THẢO -->
        <section class="flex-1 bg-white border border-gray-200 rounded shadow-sm flex flex-col overflow-hidden">
          <!-- Toolbar trên cùng -->
          <div class="p-2 border-b bg-white flex justify-end gap-2">
            <button class="px-3 py-1 border rounded text-[11px] text-gray-600 hover:bg-gray-100"><i class="fa-solid fa-trash mr-1"></i> Xóa</button>
            <button class="px-3 py-1 border rounded text-[11px] text-gray-600 hover:bg-gray-100"><i class="fa-solid fa-ban mr-1"></i> Bỏ</button>
          </div>
          
          <div class="flex-1 p-4 flex flex-col gap-px bg-gray-50/30">
            <!-- Header soạn thảo -->
            <div class="bg-white border rounded-t p-3 flex items-center gap-4">
              <span class="bg-blue-50 text-fptblue px-3 py-1 rounded text-[12px] font-bold">Tới</span>
              <input type="text" placeholder="Tới" class="flex-1 outline-none text-[13px]">
            </div>
            <div class="bg-white border-x p-3">
              <input type="text" placeholder="Chủ đề" class="w-full outline-none text-[13px] font-medium">
            </div>
            
            <!-- Khu vực nội dung soạn thảo -->
            <div class="flex-1 bg-white border p-4">
              <textarea class="w-full h-full outline-none text-[13px] resize-none" placeholder="Viết nội dung thư ở đây..."></textarea>
            </div>

            <!-- Editor Toolbar (Giả lập Rich Text) -->
            <div class="bg-white border border-t-0 p-2 flex flex-wrap gap-3 text-gray-400 text-xs border-b">
              <i class="fa-solid fa-bold cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-italic cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-underline cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-strikethrough cursor-pointer hover:text-gray-800"></i>
              <span class="border-l mx-1"></span>
              <i class="fa-solid fa-list-ul cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-list-ol cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-align-left cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-link cursor-pointer hover:text-gray-800"></i>
              <i class="fa-solid fa-image cursor-pointer hover:text-gray-800"></i>
            </div>

            <!-- Nút Gửi -->
            <div class="bg-white p-3 border border-t-0 rounded-b flex gap-2">
              <div class="flex shadow-sm">
                <button class="bg-[#1ABB9C] text-white px-6 py-1.5 rounded-l text-sm font-bold hover:bg-[#16a085]">Gửi</button>
                <button class="bg-[#1ABB9C] text-white px-2 py-1.5 rounded-r border-l border-white/20 hover:bg-[#16a085]"><i class="fa-solid fa-chevron-down text-[10px]"></i></button>
              </div>
              <button class="px-6 py-1.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-100">Bỏ</button>
            </div>
          </div>
        </section>

      </div>
    </main>
  </div>
</div>

<script>
  // Logic Sidebar
  const sidebar = document.getElementById('main-sidebar');
  const menuToggle = document.getElementById('menu-toggle');
  const contentWrapper = document.getElementById('content-wrapper');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('w-16'); sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('expanded'); contentWrapper.classList.toggle('ml-16');
    contentWrapper.classList.toggle('ml-64');
  });
</script>
</body>
</html>