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

// 3. TẠO AVATAR TỪ CHỮ CÁI ĐẦU TÊN
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&background=115293&color=fff";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>FPT IS E-Learning Dashboard</title>
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
            fptyellow: "#facc15"
          }
        }
      }
    }
  </script>
  <style>
    .transition-all-300 { transition: all 0.3s ease-in-out; }
    .sidebar-text { white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.2s; }
    .expanded .sidebar-text { opacity: 1; visibility: visible; }
  </style>
</head>

<body class="bg-gray-100 overflow-x-hidden">

<div class="flex min-h-screen relative">
<?php include 'includes/sidebar.php'; ?>
  
  <!-- CONTENT -->
  <div id="content-wrapper" class="flex-1 flex flex-col ml-16 transition-all-300 w-full">
    
    <header class="bg-white h-16 flex justify-between items-center px-6 border-b z-40 sticky top-0">
      <div class="flex items-center gap-3 cursor-pointer" onclick="location.href='index.php'">
        <img src="images/logo.png" class="h-10 w-auto object-contain" alt="Logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg'"/>
        <div>
          <p class="text-xs text-gray-500">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-lg uppercase leading-tight">Elearning</p>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <input type="text" id="search-input" oninput="handleSearch()" placeholder="Tìm kiếm..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64"/>
        
        <div class="relative cursor-pointer">
          <i class="far fa-bell text-gray-600 text-xl mt-1"></i>
          <span class="absolute -top-1 -right-2 bg-fptorange text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">1</span>
        </div>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 shadow-sm" alt="Avatar"/>
      </div>
    </header>

    <!-- BLUE STATS SECTION -->
    <section class="bg-[#0b4279] py-8 border-b border-blue-800 shadow-inner">
      <div class="flex justify-center gap-10 text-white">
        <div onclick="switchTab('courses')" class="text-center group cursor-pointer">
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-fptorange flex items-center justify-center text-3xl font-bold transition-transform group-hover:scale-105">4</div>
          <p class="mt-3 text-[10px] tracking-wider font-semibold uppercase">KHÓA HỌC</p>
        </div>
        <div onclick="switchTab('exams')" class="text-center group cursor-pointer">
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-fptyellow flex items-center justify-center text-3xl font-bold transition-transform group-hover:scale-105">1</div>
          <p class="mt-3 text-[10px] tracking-wider font-semibold uppercase">BÀI THI</p>
        </div>
        <div class="text-center group cursor-pointer opacity-80">
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-teal-400 flex items-center justify-center text-3xl font-bold transition-transform group-hover:scale-105">1</div>
          <p class="mt-3 text-[10px] tracking-wider font-semibold uppercase">GIỜ HỌC</p>
        </div>
      </div>
    </section>

    <div class="flex flex-col lg:flex-row flex-1 p-6 gap-6">
      <main class="flex-1">
        <!-- TABS NAVIGATION -->
        <div class="flex gap-8 border-b border-gray-200 mb-6">
         <button onclick="switchTab('courses')" id="tab-btn-courses" class="pb-3 text-sm font-bold transition-all border-b-2 border-fptorange text-fptorange">KHÓA HỌC</button>
         <button onclick="switchTab('exams')" id="tab-btn-exams" class="pb-3 text-sm font-medium transition-all text-gray-500 hover:text-fptorange relative">
           BÀI THI <span class="absolute top-0 -right-2 w-1.5 h-1.5 bg-fptorange rounded-full"></span>
         </button>
         <button onclick="location.href='surveys.php'" class="pb-3 text-gray-500 hover:text-fptorange font-medium text-sm transition-all">
           KHẢO SÁT
         </button>
</div>       

        <!-- SECTION: KHÓA HỌC -->
        <div id="section-courses">
          <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-800 uppercase tracking-tight text-lg">KHÓA HỌC CẦN HOÀN THÀNH</h2>
            <button onclick="location.href='courses.php'" class="border border-gray-300 px-4 py-2 rounded text-sm text-gray-600 hover:bg-gray-50 hover:shadow-sm transition-all">KHÓA HỌC KHÁC</button>
          </div>
          <div id="incomplete-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
              <p class="text-gray-500 italic text-sm">Đang tải khóa học...</p>
          </div>
          <div class="flex justify-between items-center mb-4 border-t border-gray-200 pt-6">
            <h2 class="font-bold text-green-700 uppercase tracking-tight text-lg">(<span id="complete-count">0</span>) KHÓA HỌC ĐÃ HOÀN THÀNH</h2>
          </div>
          <div id="complete-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </div>

        <!-- SECTION: BÀI THI (Mặc định ẩn) -->
        <div id="section-exams" class="hidden">
          <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-800 uppercase tracking-tight text-lg">(1) BÀI THI CẦN HOÀN THÀNH</h2>
            <button class="border border-gray-300 px-4 py-2 rounded text-sm text-gray-600 hover:bg-gray-50 transition-all uppercase text-[11px] font-bold">Bài thi khác</button>
          </div>

          <!-- Exam Card -->
          <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 hover:shadow-md transition-shadow">
            <div class="flex gap-4">
              <div class="text-blue-400 text-4xl mt-1"><i class="fa-regular fa-calendar-check"></i></div>
              <div class="space-y-1">
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-400">
                  <span><i class="fa-regular fa-clock mr-1"></i>Thời gian bắt đầu: 03/03/2026 00:00:00</span>
                  <span><i class="fa-regular fa-clock mr-1"></i>Thời gian kết thúc: 09/12/2027 00:00:00</span>
                </div>
                <h3 class="font-bold text-gray-800 text-base py-1">Kiểm tra học thuộc</h3>
                <div class="flex flex-wrap gap-2 mt-2">
                  <span class="bg-orange-50 text-fptorange px-2 py-0.5 rounded text-[10px] font-bold border border-orange-100"><i class="fa-regular fa-circle-question mr-1"></i>1 câu hỏi</span>
                  <span class="bg-blue-50 text-blue-500 px-2 py-0.5 rounded text-[10px] font-bold border border-blue-100"><i class="fa-solid fa-rotate mr-1"></i>Không giới hạn Lượt thi</span>
                  <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold border border-green-100"><i class="fa-solid fa-check mr-1"></i>Điểm đạt: 0%</span>
                </div>
              </div>
            </div>
            
            <div class="flex flex-col gap-2 w-full md:w-44">
              <div class="bg-[#337ab7] text-white py-2 text-center rounded font-bold text-sm tracking-widest shadow-inner">630d:12h:21:53</div>
              <button class="bg-fptorange text-white py-2 rounded font-bold text-sm hover:bg-orange-600 shadow-md transition-all uppercase">Thi lại</button>
            </div>
          </div>

          <div class="flex justify-between items-center mb-4 border-t border-gray-200 pt-8 mt-10">
            <h2 class="font-bold text-gray-800 uppercase tracking-tight text-lg">(0) BÀI THI ĐÃ HOÀN THÀNH</h2>
          </div>
        </div>
      </main>

      <!-- CALENDAR SIDEBAR -->
      <aside class="w-full lg:w-[320px] bg-white p-5 rounded-lg shadow-sm border border-gray-100 self-start">
        <h3 class="font-bold text-gray-800 uppercase mb-4 text-sm border-b pb-2">LỊCH LÀM VIỆC</h3>
        <p class="text-center mb-4 text-sm font-bold text-gray-800">Tháng 4, 2026</p>
        <table class="w-full text-[11px] text-center border-collapse">
          <thead>
            <tr class="bg-gray-50 text-gray-400">
              <th class="p-2 border">CN</th><th class="p-2 border">T2</th><th class="p-2 border">T3</th><th class="p-2 border">T4</th><th class="p-2 border">T5</th><th class="p-2 border">T6</th><th class="p-2 border">T7</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <tr><td class="p-2 border"></td><td class="p-2 border"></td><td class="p-2 border"></td><td class="p-2 border">1</td><td class="p-2 border">2</td><td class="p-2 border">3</td><td class="p-2 border">4</td></tr>
            <tr><td class="p-2 border">5</td><td class="p-2 border">6</td><td class="p-2 border">7</td><td class="p-2 border">8</td><td class="p-2 border">9</td><td class="p-2 border">10</td><td class="p-2 border text-red-500 font-bold">11</td></tr>
            <tr><td class="p-2 border text-red-500 font-bold">12</td><td class="p-2 border">13</td><td class="p-2 border">14</td><td class="p-2 border bg-blue-50 text-blue-600 font-bold">15</td><td class="p-2 border">16</td><td class="p-2 border">17</td><td class="p-2 border text-red-500 font-bold">18</td></tr>
            <tr><td class="p-2 border text-red-500 font-bold">19</td><td class="p-2 border">20</td><td class="p-2 border">21</td><td class="p-2 border">22</td><td class="p-2 border">23</td><td class="p-2 border">24</td><td class="p-2 border text-red-500 font-bold">25</td></tr>
            <tr><td class="p-2 border text-red-500 font-bold">26</td><td class="p-2 border">27</td><td class="p-2 border">28</td><td class="p-2 border">29</td><td class="p-2 border font-bold">30</td><td class="p-2 border"></td><td class="p-2 border"></td></tr>
          </tbody>
        </table>
      </aside>
    </div>
  </div>
</div>

<script>
  // SIDEBAR CONTROLS
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

  // TAB SWITCHING LOGIC
  function switchTab(target) {
      const btnCourses = document.getElementById('tab-btn-courses');
      const btnExams = document.getElementById('tab-btn-exams');
      const secCourses = document.getElementById('section-courses');
      const secExams = document.getElementById('section-exams');

      if(target === 'exams') {
          secCourses.classList.add('hidden');
          secExams.classList.remove('hidden');
          btnExams.className = "pb-3 text-sm font-bold transition-all border-b-2 border-fptorange text-fptorange relative";
          btnCourses.className = "pb-3 text-sm font-medium transition-all text-gray-500 hover:text-fptorange";
      } else {
          secExams.classList.add('hidden');
          secCourses.classList.remove('hidden');
          btnCourses.className = "pb-3 text-sm font-bold transition-all border-b-2 border-fptorange text-fptorange";
          btnExams.className = "pb-3 text-sm font-medium transition-all text-gray-500 hover:text-fptorange relative";
      }
  }

  // Handle Search Input (Decide which list to search)
  function handleSearch() {
      const activeSection = document.getElementById('section-exams').classList.contains('hidden') ? 'courses' : 'exams';
      if(activeSection === 'courses') searchDashboardCourses();
  }

  // CHECK URL ON LOAD
  window.addEventListener('DOMContentLoaded', () => {
      const params = new URLSearchParams(window.location.search);
      if (params.get('tab') === 'exams') switchTab('exams');
  });

  // --- EXISTING COURSE LOGIC ---
  let allCoursesData = [];

  function calculateRemainingTime(timeRange) {
      if (!timeRange || timeRange === 'Không giới hạn') return 'Không giới hạn';
      if (timeRange.includes('-')) {
          let parts = timeRange.split(' - ');
          if (parts.length === 2) {
              let endParts = parts[1].split('/');
              if (endParts.length === 3) {
                  let endDate = new Date(`${endParts[2]}-${endParts[1]}-${endParts[0]}T23:59:59`);
                  let today = new Date();
                  let diffTime = endDate - today;
                  let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                  if (diffDays < 0) return 'Đã hết hạn';
                  if (diffDays === 0) return 'Hết hạn hôm nay';
                  return `Còn ${diffDays} ngày`;
              }
          }
      }
      return timeRange; 
  }

  function generateCourseHTML(course) {
      const remainingText = calculateRemainingTime(course.time_range);
      const isExpired = remainingText === 'Đã hết hạn';
      const progress = parseInt(course.progress_percentage) || 0;
      const isCompleted = progress >= 100;

      return `
        <div onclick="location.href='course_detail.php?id=${course.id}'"
          class="bg-white rounded-lg border border-gray-100 shadow-sm cursor-pointer transition-all duration-300 hover:shadow-md hover:-translate-y-1 overflow-hidden flex flex-col h-full relative">
          <div class="h-36 overflow-hidden relative">
              <img src="${course.thumbnail_url}" onerror="this.src='https://placehold.co/600x400/115293/FFFFFF?text=ELEARNING+FPT'" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
              ${isCompleted ? `<div class="absolute top-2 right-2 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm"><i class="fa-solid fa-check"></i> Hoàn thành</div>` : ''}
          </div>
          <div class="p-4 flex flex-col flex-1">
            <h3 class="font-bold text-gray-800 mb-1 text-[15px] line-clamp-2">${course.title}</h3>
            <p class="text-xs ${isExpired ? 'text-red-500' : 'text-blue-500'} mb-4 mt-auto font-medium">
              <i class="fa-regular fa-clock mr-1"></i> ${remainingText}
            </p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
              <div class="${progress > 0 ? (isCompleted ? 'bg-green-500' : 'bg-fptblue') : 'bg-transparent'} h-1.5 rounded-full transition-all duration-1000" style="width:${progress}%"></div>
            </div>
            <p class="text-right text-[10px] text-gray-500 font-medium">${progress}%</p>
          </div>
        </div>
      `;
  }

  async function fetchCourses() {
    try {
      const response = await fetch('api/get_courses.php?view=enrolled');
      allCoursesData = await response.json();
      renderCourses(allCoursesData);
    } catch (error) {
      console.error("Lỗi:", error);
      document.getElementById("incomplete-container").innerHTML = "<p class='text-red-500 col-span-full'>Lỗi kết nối máy chủ.</p>";
    }
  }

  function searchDashboardCourses() {
      const keyword = document.getElementById('search-input').value.toLowerCase();
      const filteredCourses = allCoursesData.filter(course => {
          return course.title && course.title.toLowerCase().includes(keyword);
      });
      renderCourses(filteredCourses);
  }

  function renderCourses(coursesData) {
      const incompleteCourses = coursesData.filter(c => c.enroll_status === '1' || c.enroll_status === 'Đang học');
      const completeCourses = coursesData.filter(c => c.enroll_status === 'Đã hoàn thành');
      document.getElementById('complete-count').innerText = completeCourses.length;
      const incompleteToDisplay = incompleteCourses.slice(0, 6);
      
      document.getElementById("incomplete-container").innerHTML = incompleteToDisplay.length 
          ? incompleteToDisplay.map(generateCourseHTML).join('') 
          : "<p class='text-gray-500 col-span-full italic text-sm'>Hiện tại bạn chưa có khóa học nào cần hoàn thành.</p>";
      
      document.getElementById("complete-container").innerHTML = completeCourses.length 
          ? completeCourses.map(generateCourseHTML).join('') 
          : "<p class='text-gray-500 col-span-full italic text-sm'>Bạn chưa hoàn thành khóa học nào.</p>";
  }

  fetchCourses();
</script>
</body>
</html>