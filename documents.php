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
  <title>Tài liệu - FPT IS E-Learning</title>
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
    .category-active { background-color: #f0f7ff; color: #115293; font-weight: 700; border-left: 4px solid #115293; }
  </style>
</head>
<body class="bg-gray-100 overflow-x-hidden">

<div class="flex min-h-screen relative">
  <!-- GỌI SIDEBAR DÙNG CHUNG -->
  <?php include 'includes/sidebar.php'; ?>

  <div id="content-wrapper" class="flex-1 flex flex-col ml-16 transition-all-300 w-full">
    <!-- HEADER -->
    <header class="bg-white h-16 flex justify-between items-center px-6 border-b z-40 sticky top-0">
      <div class="flex items-center gap-3 cursor-pointer" onclick="location.href='index.php'">
        <img src="https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg" class="h-8 w-auto" alt="Logo"/>
        <div class="border-l pl-3 border-gray-200">
          <p class="text-[10px] text-gray-500 italic leading-tight">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-base uppercase leading-tight">Elearning</p>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <input type="text" placeholder="Tìm kiếm tài liệu..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64 shadow-sm"/>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 shadow-sm" alt="Avatar"/>
      </div>
    </header>

    <main class="p-6 flex-1">
      <div class="flex flex-col lg:flex-row gap-6 h-full">
        <!-- DANH MỤC -->
        <aside class="w-full lg:w-1/4">
          <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden text-[13px]">
            <div class="bg-gray-50 px-4 py-3 border-b font-bold text-fptblue uppercase">DANH MỤC TÀI LIỆU</div>
            <ul id="category-list">
              <li onclick="filterDocs('all', this)" class="category-active border-b px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                <span>Tất cả tài liệu</span> <i class="fa-solid fa-layer-group text-gray-300"></i>
              </li>
              <li onclick="filterDocs('quydinh', this)" class="border-b px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                <span>Quy định học tập FPT</span> <i class="fa-solid fa-square-plus text-gray-300"></i>
              </li>
              <li onclick="filterDocs('cttv', this)" class="border-b px-4 py-3 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                <span>CTTV</span> <i class="fa-solid fa-square-plus text-gray-300"></i>
              </li>
            </ul>
          </div>
        </aside>

        <!-- BẢNG TÀI LIỆU -->
        <section class="flex-1">
          <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b font-bold text-fptblue text-sm uppercase flex justify-between items-center">
              <span>Danh sách tài liệu</span>
              <span id="current-cat-name" class="text-[11px] text-gray-400 font-normal italic uppercase">Tất cả tài liệu</span>
            </div>
            <table class="w-full text-sm text-left">
              <thead class="bg-fptblue text-white text-[11px] uppercase">
                <tr>
                  <th class="px-4 py-3 w-16 text-center border-r border-white/20">STT</th>
                  <th class="px-4 py-3 border-r border-white/20">Tên tài liệu</th>
                  <th class="px-4 py-3 border-r border-white/20 text-center">Dung lượng</th>
                  <th class="px-4 py-3 border-r border-white/20 text-center">Số lượt xem</th>
                  <th class="px-4 py-3 text-center">Tải về</th>
                </tr>
              </thead>
              <tbody id="doc-body"></tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>
</div>

<script>
  // Logic Sidebar dùng chung
  const sidebar = document.getElementById('main-sidebar');
  const menuToggle = document.getElementById('menu-toggle');
  const contentWrapper = document.getElementById('content-wrapper');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('w-16'); sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('expanded'); contentWrapper.classList.toggle('ml-16');
    contentWrapper.classList.toggle('ml-64');
  });

  const allDocs = [
    { stt: 1, name: 'Hướng dẫn bảo mật FPT', size: '1.2 Mb', views: 15, category: 'quydinh' },
    { stt: 2, name: 'Test 1', size: '38,48 Kb', views: 8, category: 'cttv' },
    { stt: 3, name: 'Tài liệu con', size: '93,42 Kb', views: 4, category: 'cttv' }
  ];

  function filterDocs(cat, el) {
    document.querySelectorAll('#category-list li').forEach(li => li.classList.remove('category-active'));
    el.classList.add('category-active');
    document.getElementById('current-cat-name').innerText = el.querySelector('span').innerText;

    const filtered = cat === 'all' ? allDocs : allDocs.filter(d => d.category === cat);
    document.getElementById('doc-body').innerHTML = filtered.map((d, i) => `
      <tr class="border-b hover:bg-gray-50 text-[13px]">
        <td class="px-4 py-4 text-center border-r">${i+1}</td>
        <td class="px-4 py-4 border-r font-medium text-fptblue hover:underline cursor-pointer">${d.name}</td>
        <td class="px-4 py-4 text-center border-r text-gray-500 italic">${d.size}</td>
        <td class="px-4 py-4 text-center border-r">${d.views}</td>
        <td class="px-4 py-4 text-center"><button class="bg-fptorange text-white p-1.5 rounded shadow-sm"><i class="fa-solid fa-download text-xs"></i></button></td>
      </tr>
    `).join('');
  }

  document.addEventListener('DOMContentLoaded', () => filterDocs('all', document.querySelector('#category-list li')));
</script>
</body>
</html>