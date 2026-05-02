<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$fullname = $_SESSION['fullname'] ?? 'Nguyễn Thị Quỳnh Hoa';
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&background=115293&color=fff";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Thông tin cá nhân - FPT IS E-Learning</title>
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
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; }
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
        <input type="text" placeholder="Tìm kiếm..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64 shadow-sm"/>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 shadow-sm" alt="Avatar"/>
      </div>
    </header>

    <main class="p-6">
      <!-- Breadcrumb -->
      <nav class="text-[11px] text-gray-400 mb-4 flex gap-1">
        <span>Trang cá nhân</span> / <span class="text-gray-600 font-medium">Thông tin cá nhân</span>
      </nav>

      <!-- KHUNG THÔNG TIN CHÍNH[cite: 3] -->
      <div class="bg-white border border-gray-200 rounded shadow-sm flex flex-col md:flex-row p-8 gap-10">
        
        <!-- BÊN TRÁI: AVATAR -->
        <div class="flex flex-col items-center md:w-1/4 border-r border-gray-100 pr-10">
          <div class="relative group">
            <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-4 border-gray-50 shadow-inner">
               <i class="fa-solid fa-user text-6xl text-fptdark/20"></i>
               <img src="<?= $avatar_url ?>" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
            </div>
          </div>
          <h2 class="mt-4 font-bold text-gray-800 text-lg text-center"><?= $fullname ?></h2>
          <button class="mt-3 border border-gray-300 px-4 py-1 rounded text-[10px] text-gray-500 hover:bg-gray-50 flex items-center gap-2 uppercase font-bold">
            <i class="fa-solid fa-camera"></i> Tải ảnh
          </button>
        </div>

        <!-- BÊN PHẢI: CHI TIẾT THÔNG TIN[cite: 3] -->
        <div class="flex-1">
          <h3 class="font-bold text-gray-800 uppercase text-sm border-b pb-3 mb-6">Thông tin cá nhân</h3>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-4 text-[13px]">
            <!-- Cột 1 -->
            <div class="space-y-4">
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-user-tag w-4"></i> Họ và tên:</span> <span class="font-bold text-gray-700"><?= $fullname ?></span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-venus-mars w-4"></i> Giới tính:</span> <span class="text-gray-700">Nữ</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-calendar-days w-4"></i> Ngày sinh:</span> <span class="text-gray-700">12/05/2008</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-mobile-screen w-4"></i> Số điện thoại:</span> <span class="text-gray-700 italic">Chưa cập nhật</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-location-dot w-4"></i> Địa chỉ:</span> <span class="text-gray-700 italic">Chưa cập nhật</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-heart w-4"></i> Sở thích:</span> <span class="text-fptblue cursor-pointer hover:underline"><i class="fa-solid fa-pencil text-[10px] mr-1"></i> Sửa</span></div>
            </div>

            <!-- Cột 2 -->
            <div class="space-y-4">
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-id-badge w-4"></i> Account:</span> <span class="font-bold text-gray-700">quinhhoa3773</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-envelope w-4"></i> Email:</span> <span class="text-gray-700 italic">Chưa cập nhật</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-lock w-4"></i> Mật khẩu:</span> <span class="text-fptblue font-medium cursor-pointer hover:underline">Đổi mật khẩu</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-address-card w-4"></i> Mã nhân viên:</span> <span class="text-gray-700 font-bold">NV2006</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-briefcase w-4"></i> Vị trí công việc:</span> <span class="text-gray-700 italic">Chưa cập nhật</span></div>
              <div class="flex items-center"><span class="w-32 text-gray-400 flex items-center gap-2"><i class="fa-solid fa-bullhorn w-4"></i> Giới thiệu chung:</span> <span class="text-fptblue cursor-pointer hover:underline"><i class="fa-solid fa-pencil text-[10px] mr-1"></i> Sửa</span></div>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<script>
  // Logic điều khiển Sidebar đồng bộ
  const sidebar = document.getElementById('main-sidebar');
  const contentWrapper = document.getElementById('content-wrapper');
  const menuToggle = document.getElementById('menu-toggle');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('w-16'); sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('expanded'); contentWrapper.classList.toggle('ml-16');
    contentWrapper.classList.toggle('ml-64');
  });
</script>
</body>
</html>