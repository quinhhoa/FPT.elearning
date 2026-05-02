<?php
session_start();

// KIỂM TRA ĐĂNG NHẬP
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
  <title>FPT IS – Danh sách khóa học</title>
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
    };
  </script>
  <style>
    .transition-all-300 { transition: all 0.3s ease-in-out; }
    .sidebar-text { white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.2s; }
    .expanded .sidebar-text { opacity: 1; visibility: visible; }
  </style>
</head>

<body class="bg-gray-100 min-h-screen overflow-x-hidden">

<div class="flex min-h-screen relative">
<?php include 'includes/sidebar.php'; ?>

  <div id="content-wrapper" class="flex-1 flex flex-col ml-16 transition-all-300 w-full">
    
    <header class="bg-white h-16 flex justify-between items-center px-6 border-b z-40 sticky top-0">
      <div class="flex items-center gap-3 cursor-pointer" onclick="location.href='index.php'">
        <img src="images/logo.png" class="h-10 w-auto object-contain" alt="Logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg'"/>
        <div>
          <p class="text-xs text-gray-500">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-lg">ELEARNING</p>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <input type="text" id="search-catalog" oninput="applyFilters()" placeholder="Tìm kiếm khóa học..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64 bg-gray-50"/>
        <div class="relative cursor-pointer">
          <i class="far fa-bell text-gray-600 text-lg"></i>
          <span class="absolute -top-1 -right-2 bg-fptorange text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">1</span>
        </div>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 cursor-pointer" alt="Avatar"/>
      </div>
    </header>

    <div class="bg-white px-6 py-3 border-b border-gray-200 flex justify-between items-center">
      <h1 class="font-bold text-fptblue text-xl uppercase tracking-wide">DANH SÁCH KHÓA HỌC</h1>
      <a href="index.php" class="text-sm text-gray-500 hover:text-fptblue transition-colors"><i class="fas fa-arrow-left"></i> Quay lại Dashboard</a>
    </div>

    <div class="flex flex-col md:flex-row flex-1 p-6 gap-6">

      <aside class="w-full md:w-1/4 lg:w-1/5 bg-white rounded-lg shadow-sm border border-gray-100 p-5 self-start">
        <h2 class="font-bold mb-4 text-gray-800 border-b pb-2">DANH MỤC</h2>
        
        <!-- BỘ LỌC DANH MỤC -->
        <ul id="category-list" class="space-y-1 text-sm text-gray-700">
          <li onclick="filterByCategory('all', this)" class="flex justify-between items-center p-2 rounded cursor-pointer bg-blue-50 text-fptblue font-bold transition-all">
            <span>Tất cả khóa học</span>
            <i class="fas fa-chevron-right text-[10px] text-fptblue"></i>
          </li>
          <li onclick="filterByCategory(1, this)" class="flex justify-between items-center p-2 rounded cursor-pointer hover:bg-blue-50 hover:text-fptblue transition-all font-medium">
            <span>Chuyên môn IT</span>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
          </li>
          <li onclick="filterByCategory(2, this)" class="flex justify-between items-center p-2 rounded cursor-pointer hover:bg-blue-50 hover:text-fptblue transition-all font-medium">
            <span>Kỹ năng mềm</span>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
          </li>
          <li onclick="filterByCategory(3, this)" class="flex justify-between items-center p-2 rounded cursor-pointer hover:bg-blue-50 hover:text-fptblue transition-all font-medium">
            <span>Quản lý dự án</span>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
          </li>
          <li onclick="filterByCategory(4, this)" class="flex justify-between items-center p-2 rounded cursor-pointer hover:bg-blue-50 hover:text-fptblue transition-all font-medium">
            <span>Hội nhập nhân viên</span>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
          </li>
        </ul>
      </aside>

      <main class="w-full md:w-3/4 lg:w-4/5">
        <div id="course-list-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 pb-10">
            <p class="text-gray-500 italic">Đang tải danh sách khóa học...</p>
        </div>
      </main>

    </div>
  </div>
</div>

<script>
  // --- LOGIC TOGGLE SIDEBAR ---
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

  // --- BIẾN TOÀN CỤC CHỨA DỮ LIỆU ---
  let catalogCourses = [];
  let currentCategory = 'all'; // Trạng thái bộ lọc danh mục hiện tại

  // --- LOGIC FETCH DỮ LIỆU TỪ API ---
  async function loadCourses() {
    try {
      const response = await fetch('api/get_courses.php?view=catalog');
      catalogCourses = await response.json();
      applyFilters(); // Khởi tạo đổ dữ liệu lần đầu
    } catch (error) {
      console.error("Lỗi:", error);
      document.getElementById("course-list-grid").innerHTML = "<p class='text-red-500 col-span-full'>Không thể tải dữ liệu. Vui lòng kiểm tra lại kết nối Database.</p>";
    }
  }

  // --- HÀM XỬ LÝ KHI CLICK VÀO DANH MỤC ---
  function filterByCategory(categoryId, element) {
      // 1. Reset màu sắc của tất cả các thẻ danh mục
      document.querySelectorAll('#category-list li').forEach(li => {
          li.classList.remove('bg-blue-50', 'text-fptblue', 'font-bold');
          li.classList.add('font-medium');
          // Reset màu icon
          li.querySelector('i').classList.remove('text-fptblue');
          li.querySelector('i').classList.add('text-gray-400');
      });
      
      // 2. Bôi đậm thẻ danh mục vừa click
      element.classList.add('bg-blue-50', 'text-fptblue', 'font-bold');
      element.classList.remove('font-medium');
      element.querySelector('i').classList.remove('text-gray-400');
      element.querySelector('i').classList.add('text-fptblue');

      // 3. Lưu lại ID danh mục đang chọn và kích hoạt bộ lọc
      currentCategory = categoryId;
      applyFilters();
  }

  // --- HÀM TỔNG HỢP: LỌC CẢ DANH MỤC LẪN TỪ KHÓA ---
  function applyFilters() {
      const keyword = document.getElementById('search-catalog').value.toLowerCase();
      let filtered = catalogCourses;

      // Bước 1: Lọc theo danh mục (nếu có chọn)
      if (currentCategory !== 'all') {
          filtered = filtered.filter(course => course.category_id == currentCategory);
      }

      // Bước 2: Lọc tiếp theo từ khóa tìm kiếm
      if (keyword) {
          filtered = filtered.filter(course => 
              course.title && course.title.toLowerCase().includes(keyword)
          );
      }

      // Đẩy mảng dữ liệu cuối cùng ra màn hình
      renderCatalog(filtered);
  }

  // --- HÀM RENDER KHÓA HỌC RA LƯỚI ---
  function renderCatalog(courses) {
      const grid = document.getElementById("course-list-grid");
      grid.innerHTML = "";

      if(courses.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center text-gray-500 py-10">
                <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
                <p>Không tìm thấy khóa học nào phù hợp với bộ lọc của bạn.</p>
            </div>
        `;
        return;
      }

      courses.forEach((course) => {
        grid.innerHTML += `
          <div onclick="location.href='course_detail.php?id=${course.id}'" 
               class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex flex-col h-full cursor-pointer relative">
            
            <div class="relative h-32 overflow-hidden rounded mb-3">
              <img src="${course.thumbnail_url}" 
                   onerror="this.src='https://placehold.co/600x400/115293/FFFFFF?text=ELEARNING+FPT'"
                   class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
            </div>
            
            <h3 class="font-bold text-fptdark text-[15px] mb-2 line-clamp-2">${course.title}</h3>
            
            <p class="text-[11px] text-gray-500 mb-3 mt-auto flex items-center gap-1">
               <i class="fa-solid fa-users"></i> ${course.students || 0} Học viên
            </p>

            <div class="flex justify-between items-center border-t pt-3 mt-1">
              <span class="text-[11px] font-medium ${course.registration_type === 'Kiểm duyệt' ? 'text-fptorange' : 'text-green-600'}">
                <i class="fa-solid fa-clock"></i> ${course.registration_type}
              </span>
              
              <button class="bg-fptblue text-white text-xs px-3 py-1.5 rounded transition-all duration-300 hover:bg-[#0d3f73] z-10">
                Xem chi tiết
              </button>
            </div>

          </div>
        `;
      });
  }

  // Chạy hàm khi load trang
  loadCourses();
</script>

</body>
</html>