<?php
session_start();

// GỌI KẾT NỐI DATABASE (Bạn nhớ chỉnh lại đường dẫn 'api/db.php' hoặc 'includes/db.php' cho đúng với project của bạn nhé)
require_once 'api/db.php'; 

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$fullname = $_SESSION['fullname'] ?? 'Học viên';
$role = $_SESSION['role'] ?? 'student';
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&background=115293&color=fff";


// --- LẤY DỮ LIỆU THỐNG KÊ TỪ DATABASE ---
$count_courses = 0;
$count_exams = 152; // Tạm mock dữ liệu (Sẽ thay bằng SELECT COUNT FROM exams)
$count_certs = 25;  // Tạm mock dữ liệu
$total_hours = 937; // Tạm mock dữ liệu

if (isset($conn)) {
    // Đếm số lượng Khóa học đang hoạt động (Đã duyệt)
    $sql_courses = "SELECT COUNT(id) AS total FROM courses WHERE status = 1";
    $res_courses = $conn->query($sql_courses);
    if ($res_courses && $row = $res_courses->fetch_assoc()) {
        $count_courses = $row['total'];
    }
    
    // GỢI Ý: Sau này bạn tạo bảng Bài thi, chỉ cần bỏ comment đoạn dưới là số sẽ tự nhảy
    /*
    $sql_exams = "SELECT COUNT(id) AS total FROM exams";
    $res_exams = $conn->query($sql_exams);
    if ($res_exams && $row = $res_exams->fetch_assoc()) {
        $count_exams = $row['total'];
    }
    */
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Nhu cầu đào tạo - FPT IS E-Learning</title>
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
          }
        }
      }
    }
  </script>
  <style>
    /* Chuyển động mượt mà cho Sidebar */
    .sidebar-transition { transition: width 0.3s ease-in-out, margin-left 0.3s ease-in-out; }
    .sidebar-text { white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.2s; }
    .expanded .sidebar-text { opacity: 1; visibility: visible; transition-delay: 0.1s; }
    
    /* Bảng dữ liệu */
    .table-custom th, .table-custom td { border: 1px solid #e2e8f0; vertical-align: middle; }
    .table-custom th { background-color: #115293; color: white; text-transform: uppercase; font-size: 11px; padding: 10px 8px; text-align: center; }
    .table-custom td { padding: 8px; font-size: 12px; color: #4b5563; }
    
    /* Vòng tròn thống kê */
    .stat-circle { position: relative; display: flex; justify-content: center; align-items: center; border-radius: 50%; background: transparent; }
    .stat-inner { background: #0b4279; border-radius: 50%; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; }
  </style>
</head>

<body class="bg-gray-100 overflow-x-hidden">

<div class="flex min-h-screen relative">

  <!-- SIDEBAR HỌC VIÊN -->
  <!-- Sử dụng w-[260px] cho trạng thái mở và có class "expanded" -->
  <aside id="main-sidebar" class="w-[70px] bg-fptdark flex flex-col py-6 text-white fixed top-0 left-0 h-screen z-50 sidebar-transition overflow-hidden shadow-xl ">
    <div class="flex flex-col w-full h-full">
      <div id="menu-toggle" class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-colors border-b border-white/10 mb-2">
        <i class="fas fa-bars w-6 text-center text-xl"></i>
        <span class="sidebar-text ml-4 font-bold text-lg uppercase tracking-wider text-fptorange">Menu</span>
      </div>

      <div class="mt-2 space-y-1 flex-1">
        <div class="flex items-center px-5 py-3.5 cursor-pointer hover:bg-fptblue transition-all" onclick="location.href='index.php'">
          <i class="fas fa-gauge-high w-6 text-center text-white/70"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">TRANG CÁ NHÂN</span>
        </div>
        
        <div class="flex items-center px-5 py-3.5 cursor-pointer bg-[#2469a5] transition-all border-l-4 border-fptorange" onclick="location.href='training_needs.php'">
          <i class="fas fa-users w-6 text-center text-white"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">NHU CẦU ĐÀO TẠO</span>
        </div>

        <div class="flex items-center px-5 py-3.5 cursor-pointer hover:bg-fptblue transition-all" onclick="location.href='documents.php'">
          <i class="fas fa-folder-open w-6 text-center text-white/70"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">TÀI LIỆU</span>
        </div>
        
        <div class="flex items-center px-5 py-3.5 cursor-pointer hover:bg-fptblue transition-all" onclick="location.href='messages.php'">
          <i class="fas fa-envelope w-6 text-center text-white/70"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">HÒM THƯ</span>
        </div>
        
        <div class="flex items-center px-5 py-3.5 cursor-pointer hover:bg-fptblue transition-all" onclick="location.href='profile.php'">
          <i class="fas fa-user w-6 text-center text-white/70"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">THÔNG TIN CÁ NHÂN</span>
        </div>
      </div>

      <div class="space-y-1 pb-4 border-t border-white/10 pt-4">
        <?php if ($role === 'admin'): ?>
        <div onclick="location.href='admin.php'" class="flex items-center px-5 py-3.5 cursor-pointer hover:bg-fptblue transition-all">
          <i class="fas fa-laptop-code w-6 text-center text-white/70"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">TRANG QUẢN LÝ</span>
        </div>
        <?php endif; ?>
        
        <div onclick="location.href='logout.php'" class="flex items-center px-5 py-3.5 cursor-pointer hover:bg-red-600 transition-all">
          <i class="fas fa-power-off w-6 text-center text-white/70"></i>
          <span class="sidebar-text ml-4 text-[13px] font-medium tracking-wide">ĐĂNG XUẤT</span>
        </div>
      </div>
    </div>
  </aside>

  <!-- CONTENT -->
  <!-- Margin trái = độ rộng Sidebar (260px) -->
  <div id="content-wrapper" class="flex-1 flex flex-col sidebar-transition w-full ml-[70px]">
    
    <!-- HEADER -->
    <header class="bg-white h-16 flex justify-between items-center px-6 border-b z-40 sticky top-0">
      <div class="flex items-center gap-3">
        <div class="flex skew-x-[-15deg]">
            <span class="bg-[#175aa3] text-white font-bold text-[22px] px-2.5 py-0.5 leading-none">F</span>
            <span class="bg-[#f26f21] text-white font-bold text-[22px] px-2.5 py-0.5 leading-none">P</span>
            <span class="bg-[#00a950] text-white font-bold text-[22px] px-2.5 py-0.5 leading-none">T</span>
        </div>
        <div class="flex flex-col justify-center border-l border-gray-300 pl-4">
          <p class="text-[12px] text-gray-500 leading-tight">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-[15px] leading-tight">ELEARNING</p>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <input type="text" placeholder="Tìm kiếm..." class="border border-gray-300 rounded-full px-4 py-1.5 text-[13px] outline-none focus:border-fptblue w-48"/>
        <div class="relative cursor-pointer hover:text-fptblue transition">
          <i class="far fa-bell text-gray-400 text-xl"></i>
        </div>
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 shadow-sm cursor-pointer hover:opacity-80 transition" alt="Avatar"/>
      </div>
    </header>

    <!-- STATS (THỐNG KÊ ĐẦU TRANG - DỮ LIỆU ĐỘNG TỪ PHP) -->
    <section class="bg-[#0b4279] py-8 border-b border-blue-900 relative overflow-hidden" style="background-image: radial-gradient(circle at 50% 0%, #1a5c96 0%, #0b4279 70%);">
      <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')]"></div>

      <div class="flex justify-center gap-16 text-white relative z-10">
        <!-- Khóa học (Đã nối DB) -->
        <div class="text-center group">
          <div class="stat-circle w-28 h-28 border-[6px] border-[#f97316] shadow-[0_0_15px_rgba(249,115,22,0.4)]">
             <div class="stat-inner w-full h-full">
                <!-- Đổ biến $count_courses từ PHP ra đây -->
                <span class="text-4xl font-bold leading-none mb-1"><?php echo $count_courses; ?></span>
                <span class="text-[10px] uppercase font-medium">Khóa học</span>
             </div>
          </div>
        </div>
        <!-- Bài thi -->
        <div class="text-center group">
          <div class="stat-circle w-28 h-28 border-[6px] border-white shadow-[0_0_15px_rgba(255,255,255,0.2)]">
             <div class="stat-inner w-full h-full">
                <span class="text-4xl font-bold leading-none mb-1"><?php echo $count_exams; ?></span>
                <span class="text-[10px] uppercase font-medium">Bài thi</span>
             </div>
          </div>
        </div>
        <!-- Giờ học -->
        <div class="text-center group">
          <div class="stat-circle w-28 h-28 border-[6px] border-[#10b981] shadow-[0_0_15px_rgba(16,185,129,0.4)]">
             <div class="stat-inner w-full h-full">
                <span class="text-4xl font-bold leading-none mb-1"><?php echo $total_hours; ?></span>
                <span class="text-[10px] uppercase font-medium">Giờ học</span>
             </div>
          </div>
        </div>
      </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="flex flex-col lg:flex-row flex-1 p-6 gap-6 bg-white">

      <main class="flex-1">
        <h2 class="font-bold text-fptblue uppercase tracking-tight text-lg mb-4 flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap"></i> NHU CẦU ĐÀO TẠO
        </h2>

        <div class="flex gap-2 mb-4 bg-white border border-gray-200 p-2 rounded-sm shadow-sm">
            <input type="text" id="search-input" placeholder="Chọn nhu cầu đào tạo/Khóa học" class="flex-1 border border-gray-300 px-3 py-1.5 text-[13px] outline-none focus:border-fptblue rounded-sm">
            <button onclick="searchNeeds()" class="bg-[#337AB7] text-white px-4 py-1.5 text-[13px] hover:bg-[#286090] transition rounded-sm shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-search"></i> Tìm kiếm
            </button>
            <button onclick="location.href='student_need_form.php'" class="bg-[#337AB7] text-white px-4 py-1.5 text-[13px] hover:bg-[#286090] transition rounded-sm shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i> Thêm mới
            </button>
            <div class="flex-1"></div>
            <button onclick="deleteMultipleNeeds()" class="bg-[#ff6b6b] text-white px-4 py-1.5 text-[13px] hover:bg-[#fa5252] transition rounded-sm shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-trash-can"></i> Xóa
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-custom">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" id="check-all" onclick="toggleAllCheckboxes(this)" class="accent-[#1ABB9C] cursor-pointer"></th>
                        <th class="w-12">STT</th>
                        <th class="text-left px-3">Nhu cầu đào tạo</th>
                        <th class="text-left px-3">Đơn vị đào tạo</th>
                        <th>Giảng viên</th>
                        <th>Năng lực</th>
                        <th>Trạng thái</th>
                        <th class="w-32">Hành động</th>
                    </tr>
                </thead>
                <tbody id="student-needs-list">
                    <tr><td colspan="8" class="text-center py-6 text-gray-500 italic">Đang tải dữ liệu...</td></tr>
                </tbody>
            </table>
        </div>
      </main>

      <!-- SIDEBAR LỊCH LÀM VIỆC -->
      <aside class="w-full lg:w-[280px] flex-shrink-0 self-start">
        <h3 class="font-bold text-gray-800 uppercase mb-3 text-[13px]">LỊCH LÀM VIỆC</h3>
        
        <div class="flex justify-between items-center mb-2">
            <span class="text-[13px] font-bold text-gray-800">Tháng 4, 2026</span>
            <div class="flex gap-1">
                <button class="bg-blue-300 text-white text-[10px] px-2 py-0.5 rounded-sm">Hôm nay</button>
                <button class="border border-gray-300 text-gray-400 px-1 rounded-sm"><i class="fa-solid fa-caret-left text-[10px]"></i></button>
                <button class="border border-gray-300 text-gray-400 px-1 rounded-sm"><i class="fa-solid fa-caret-right text-[10px]"></i></button>
            </div>
        </div>

        <div class="border border-gray-200">
            <table class="w-full text-[12px] text-center border-collapse">
            <thead>
                <tr class="text-gray-800 bg-gray-50 border-b border-gray-200">
                <th class="p-1.5 font-bold border-r">CN</th><th class="p-1.5 font-bold border-r">T2</th><th class="p-1.5 font-bold border-r">T3</th><th class="p-1.5 font-bold border-r">T4</th><th class="p-1.5 font-bold border-r">T5</th><th class="p-1.5 font-bold border-r">T6</th><th class="p-1.5 font-bold">T7</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 bg-white">
                <tr>
                    <td class="p-1.5 border-r border-b"></td><td class="p-1.5 border-r border-b"></td><td class="p-1.5 border-r border-b"></td>
                    <td class="p-1.5 border-r border-b">1</td><td class="p-1.5 border-r border-b">2</td><td class="p-1.5 border-r border-b">3</td><td class="p-1.5 border-b">4</td>
                </tr>
                <tr>
                    <td colspan="3" class="border-r border-b"></td>
                    <td colspan="4" class="p-0.5 border-b bg-[#337AB7] text-white text-[10px]">ôn thi</td>
                </tr>
                <tr>
                    <td class="p-1.5 border-r border-b">5</td><td class="p-1.5 border-r border-b">6</td><td class="p-1.5 border-r border-b">7</td>
                    <td class="p-1.5 border-r border-b">8</td><td class="p-1.5 border-r border-b">9</td><td class="p-1.5 border-r border-b">10</td><td class="p-1.5 border-b">11</td>
                </tr>
                <tr>
                    <td class="p-1.5 border-r border-b">12</td><td class="p-1.5 border-r border-b">13</td><td class="p-1.5 border-r border-b">14</td>
                    <td class="p-1.5 border-r border-b bg-blue-50 text-[#337AB7] font-bold">15</td><td class="p-1.5 border-r border-b">16</td><td class="p-1.5 border-r border-b">17</td><td class="p-1.5 border-b">18</td>
                </tr>
                <tr>
                    <td class="p-1.5 border-r border-b">19</td><td class="p-1.5 border-r border-b">20</td><td class="p-1.5 border-r border-b">21</td>
                    <td class="p-1.5 border-r border-b">22</td><td class="p-1.5 border-r border-b">23</td><td class="p-1.5 border-r border-b">24</td><td class="p-1.5 border-b">25</td>
                </tr>
                <tr>
                    <td class="p-1.5 border-r border-b">26</td><td class="p-1.5 border-r border-b">27</td><td class="p-1.5 border-r border-b">28</td>
                    <td class="p-1.5 border-r border-b">29</td><td class="p-1.5 border-r border-b bg-blue-50 text-[#337AB7] font-bold">30</td><td class="p-1.5 border-r border-b text-gray-300">1</td><td class="p-1.5 border-b text-gray-300">2</td>
                </tr>
            </tbody>
            </table>
        </div>
      </aside>

    </div>
  </div>
</div>

<script>
  const sidebar = document.getElementById('main-sidebar');
  const contentWrapper = document.getElementById('content-wrapper');
  const menuToggle = document.getElementById('menu-toggle');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('w-[260px]'); sidebar.classList.toggle('w-[70px]');
    sidebar.classList.toggle('expanded');
    contentWrapper.classList.toggle('ml-[260px]'); contentWrapper.classList.toggle('ml-[70px]');
  });

  async function loadStudentNeeds(keyword = '') {
      try {
          const res = await fetch('api/get_training_needs.php?view=student&search=' + encodeURIComponent(keyword));
          const data = await res.json();
          const tbody = document.getElementById('student-needs-list');

          if(data.error) { tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-red-500">${data.message}</td></tr>`; return; }
          if(data.length === 0) { tbody.innerHTML = `<tr><td colspan="8" class="text-center py-6 text-gray-500">Không tìm thấy dữ liệu.</td></tr>`; return; }

          let html = '';
          data.forEach((item, index) => {
              let isEditable = (item.status === 'Chưa gửi' || item.status === 'Bỏ duyệt');
              
              // LOGIC CHECKBOX: Chỉ cho phép tích nếu được phép sửa (isEditable)
              const checkboxHTML = isEditable 
                  ? `<input type="checkbox" class="row-checkbox w-3.5 h-3.5 accent-[#1ABB9C] cursor-pointer" value="${item.id}">`
                  : `<input type="checkbox" disabled class="w-3.5 h-3.5 opacity-40 cursor-not-allowed" title="Không thể chọn đơn đã gửi">`;

              const actionBtns = isEditable ? `
                  <div class="flex justify-center gap-1">
                      <button onclick="location.href='student_need_form.php?id=${item.id}'" class="bg-[#5bc0de] text-white w-6 h-6 rounded flex items-center justify-center hover:bg-[#31b0d5]" title="Cập nhật"><i class="fa-solid fa-pen text-[10px]"></i></button>
                      <button onclick="deleteNeed(${item.id})" class="bg-[#ff6b6b] text-white w-6 h-6 rounded flex items-center justify-center hover:bg-[#fa5252]" title="Xóa"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                      <button onclick="sendNeed(${item.id})" class="bg-[#5CB85C] text-white w-6 h-6 rounded flex items-center justify-center hover:bg-[#4cae4c]" title="Gửi duyệt"><i class="fa-solid fa-paper-plane text-[10px]"></i></button>
                  </div>
              ` : `<span class="text-gray-400 italic text-[11px]">Đã khóa</span>`;

              html += `
                  <tr class="hover:bg-blue-50 transition-colors bg-white">
                      <td class="text-center">${checkboxHTML}</td>
                      <td class="text-center text-gray-500">${index + 1}</td>
                      <td class="px-3">${item.name || ''}</td>
                      <td class="px-3 text-gray-600">${item.unit || ''}</td>
                      <td class="text-center text-gray-600">${item.teacher || ''}</td>
                      <td class="text-center text-gray-600">${item.competency || ''}</td>
                      <td class="text-center font-medium ${item.status === 'Đã duyệt NCĐT' ? 'text-green-600' : 'text-gray-600'}">${item.status}</td>
                      <td class="text-center">${actionBtns}</td>
                  </tr>
              `;
          });
          tbody.innerHTML = html;
          // Reset lại nút check-all mỗi khi tải lại bảng
          if(document.getElementById('check-all')) document.getElementById('check-all').checked = false;
      } catch (err) { console.error(err); }
  }

  function searchNeeds() {
      const keyword = document.getElementById('search-input').value;
      loadStudentNeeds(keyword);
  }

  // Chọn / Bỏ chọn tất cả checkbox hợp lệ
  function toggleAllCheckboxes(source) {
      const checkboxes = document.querySelectorAll('.row-checkbox');
      checkboxes.forEach(cb => {
          if (!cb.disabled) {
              cb.checked = source.checked;
          }
      });
  }

  // Tính năng Xóa hàng loạt
  async function deleteMultipleNeeds() {
      const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
      const ids = Array.from(checkedBoxes).map(cb => cb.value);

      if (ids.length === 0) {
          alert('Vui lòng chọn ít nhất một nhu cầu đào tạo (chưa gửi) để xóa!');
          return;
      }

      if (confirm(`Bạn có chắc chắn muốn xóa vĩnh viễn ${ids.length} nhu cầu đào tạo đã chọn?`)) {
          try {
              const res = await fetch('api/delete_multiple_needs.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ ids: ids })
              });
              const result = await res.json();
              if (result.success) {
                  alert('Xóa thành công!');
                  loadStudentNeeds(); // Tải lại bảng sau khi xóa
              } else {
                  alert('Lỗi: ' + result.message);
              }
          } catch (err) {
              alert('Lỗi kết nối máy chủ!');
          }
      }
  }

  // Xóa đơn lẻ (Nút thùng rác ở từng dòng)
  async function deleteNeed(id) {
      if(confirm('Bạn có chắc chắn muốn xóa nhu cầu đào tạo này?')) {
          await fetch(`api/delete_need.php?id=${id}`);
          loadStudentNeeds();
      }
  }

  // Gửi duyệt đơn lẻ
  async function sendNeed(id) {
      if(confirm('Xác nhận GỬI nhu cầu đào tạo này cho Admin xét duyệt?')) {
          const res = await fetch(`api/update_need_status.php?id=${id}&status=Đã gửi`);
          const result = await res.json();
          if(result.success) {
              alert("Đã gửi thành công!");
              loadStudentNeeds();
          }
      }
  }

  document.addEventListener('DOMContentLoaded', () => loadStudentNeeds(''));
</script>

</body>
</html>