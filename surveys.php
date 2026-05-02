<?php
session_start();

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. LẤY THÔNG TIN TỪ SESSION
$fullname = $_SESSION['fullname'] ?? 'Học viên';
$role = $_SESSION['role'] ?? 'student'; 
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&background=115293&color=fff";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Khảo sát - FPT IS E-Learning</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            fptblue: "#115293",
            fptdark: "#003366",
            fptorange: "#f97316",
          },
        },
      },
    }
  </script>
  <style>
    .transition-all-300 { transition: all 0.3s ease-in-out; }
    .sidebar-text { white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.2s; }
    .expanded .sidebar-text { opacity: 1; visibility: visible; }
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; }
    .category-active { background-color: #f0f7ff; color: #115293; font-weight: 700; border-left: 4px solid #115293; }
  </style>
</head>

<body class="bg-gray-100 overflow-x-hidden">

<div class="flex min-h-screen relative">

  <!-- SIDEBAR DÙNG CHUNG -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- CONTENT -->
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
        <input type="text" placeholder="Tìm kiếm khảo sát..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64 shadow-sm"/>
        <div class="relative cursor-pointer">
          <i class="far fa-bell text-gray-600 text-xl mt-1"></i>
          <span class="absolute -top-1 -right-2 bg-fptorange text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">1</span>
        </div>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 shadow-sm" alt="Avatar"/>
      </div>
    </header>

    <!-- GIAO DIỆN KHẢO SÁT[cite: 3] -->
    <main class="p-6 flex-1">
      <div class="flex flex-col lg:flex-row gap-6 h-full">
        
        <!-- BÊN TRÁI: DANH MỤC TRẠNG THÁI -->
        <aside class="w-full lg:w-1/4">
          <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden text-[13px]">
            <div class="bg-gray-50 px-4 py-3 border-b flex items-center gap-2 font-bold text-fptblue uppercase">
              <i class="fa-solid fa-square-poll-vertical"></i> Trạng thái khảo sát
            </div>
            <ul id="category-list">
              <li onclick="filterSurveys('all', this)" class="category-active border-b px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer transition-colors">
                <span>Tất cả khảo sát</span> <i class="fa-solid fa-list-check text-gray-300"></i>
              </li>
              <li onclick="filterSurveys('pending', this)" class="border-b px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer transition-colors">
                <span>Chưa thực hiện</span> <i class="fa-solid fa-clock text-gray-300"></i>
              </li>
              <li onclick="filterSurveys('completed', this)" class="px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer transition-colors">
                <span>Đã hoàn thành</span> <i class="fa-solid fa-circle-check text-gray-300"></i>
              </li>
            </ul>
          </div>
        </aside>

        <!-- BÊN PHẢI: DANH SÁCH KHẢO SÁT[cite: 3] -->
        <section class="flex-1">
          <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b font-bold text-fptblue text-sm uppercase flex justify-between items-center">
              <span>Danh sách khảo sát</span>
              <span id="current-category" class="text-[11px] text-gray-400 font-normal italic uppercase">Tất cả khảo sát</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-fptblue text-white text-[11px] uppercase tracking-wider">
                  <tr>
                    <th class="px-4 py-3 w-16 text-center border-r border-white/20">STT</th>
                    <th class="px-4 py-3 border-r border-white/20">Tên cuộc khảo sát</th>
                    <th class="px-4 py-3 border-r border-white/20 text-center">Hạn chót</th>
                    <th class="px-4 py-3 border-r border-white/20 text-center">Trạng thái</th>
                    <th class="px-4 py-3 text-center">Thực hiện</th>
                  </tr>
                </thead>
                <tbody id="survey-body" class="text-gray-700 text-[13px]">
                  <!-- Dữ liệu mẫu -->
                </tbody>
              </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t text-[11px] text-gray-400 italic">
              Hiển thị <span id="record-count">0</span> khảo sát
            </div>
          </div>
        </section>
      </div>
    </main>

  </div>
</div>

<script>
  // Script điều khiển Sidebar
  const sidebar = document.getElementById('main-sidebar');
  const contentWrapper = document.getElementById('content-wrapper');
  const menuToggle = document.getElementById('menu-toggle');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('w-16');
    sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('expanded');
    contentWrapper.classList.toggle('ml-16');
    contentWrapper.classList.toggle('ml-64');
  });

  // Dữ liệu mẫu khảo sát
  const allSurveys = [
    { stt: 1, name: 'Khảo sát chất lượng khóa học React Native cơ bản', deadline: '30/05/2026', status: 'pending', statusText: 'Chưa thực hiện' },
    { stt: 2, name: 'Khảo sát nhu cầu đào tạo kỹ năng mềm quý II/2026', deadline: '15/06/2026', status: 'pending', statusText: 'Chưa thực hiện' },
    { stt: 3, name: 'Đánh giá môi trường làm việc tại FPT IS 2026', deadline: '01/05/2026', status: 'completed', statusText: 'Đã hoàn thành' }
  ];

  function filterSurveys(status, element) {
    // Cập nhật UI menu trái
    const items = document.querySelectorAll('#category-list li');
    items.forEach(item => item.classList.remove('category-active'));
    element.classList.add('category-active');

    // Cập nhật tiêu đề bảng
    document.getElementById('current-category').innerText = element.querySelector('span').innerText;

    // Lọc dữ liệu
    const filtered = status === 'all' ? allSurveys : allSurveys.filter(s => s.status === status);
    
    // Render bảng
    const tbody = document.getElementById('survey-body');
    tbody.innerHTML = filtered.map((survey, index) => `
      <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
        <td class="px-4 py-4 text-center border-r border-gray-100">${index + 1}</td>
        <td class="px-4 py-4 border-r border-gray-100 font-medium text-fptblue hover:underline cursor-pointer">${survey.name}</td>
        <td class="px-4 py-4 text-center border-r border-gray-100 text-gray-500">${survey.deadline}</td>
        <td class="px-4 py-4 text-center border-r border-gray-100">
          <span class="px-2 py-1 rounded text-[10px] font-bold ${survey.status === 'pending' ? 'bg-orange-100 text-fptorange' : 'bg-green-100 text-green-700'}">
            ${survey.statusText}
          </span>
        </td>
        <td class="px-4 py-4 text-center">
          ${survey.status === 'pending' ? `
            <button class="bg-fptorange text-white px-3 py-1 rounded text-[11px] hover:bg-orange-600 shadow-sm transition-all">Làm khảo sát</button>
          ` : `
            <button class="bg-gray-300 text-gray-600 px-3 py-1 rounded text-[11px] cursor-not-allowed">Xem lại</button>
          `}
        </td>
      </tr>
    `).join('');

    document.getElementById('record-count').innerText = filtered.length;
  }

  // Khởi động mặc định
  document.addEventListener('DOMContentLoaded', () => {
    const defaultItem = document.querySelector('#category-list li');
    filterSurveys('all', defaultItem);
  });
</script>

</body>
</html>