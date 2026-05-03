<?php
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 1; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Danh sách học viên</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #333; }
    .circle-chart__circle {
      animation: circle-chart-fill 1s reverse; 
      transform: rotate(-90deg);
      transform-origin: center;
      transition: stroke-dasharray 0.5s ease;
    }
    @keyframes circle-chart-fill { to { stroke-dasharray: 0 100; } }
  </style>
</head>
<body class="bg-[#F7F7F7] flex min-h-screen overflow-x-hidden text-[12px]">

  <?php include 'includes/admin_sidebar.php'; ?>

  <div class="flex-1 ml-[200px] flex flex-col min-h-screen">
    <header class="h-12 bg-white border-b border-[#D9DEE4] flex justify-between items-center px-4 z-40 sticky top-0">
      <div class="flex items-center gap-3 text-[#73879C]"><i class="fa-solid fa-bars"></i><span class="font-medium">Trang chủ</span></div>
      <div class="flex items-center gap-2"><img src="https://i.pravatar.cc/150?img=11" class="w-7 h-7 rounded-full border"><span>Quỳnh Hoa</span></div>
    </header>

    <main class="p-4 flex-1">
      <div class="flex justify-between items-end mb-4">
        <div class="text-[11px] text-gray-500 bg-gray-100 py-0.5 px-2 rounded inline-flex mt-1">
            <i class="fa-solid fa-house mr-1"></i> Dashboard / Quản lý khóa học / Danh sách học viên
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="openAddModal()" class="bg-[#1ABB9C] text-white px-3 py-1.5 rounded text-[11px] font-bold hover:bg-[#159a80] transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-plus"></i> Thêm mới
            </button>
            
            <div class="relative">
                <button onclick="toggleDropdown('action-main-menu')" class="bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded text-[11px] font-bold hover:bg-gray-100 transition flex items-center gap-1.5 shadow-sm">
                    Hành động <i class="fa-solid fa-chevron-down text-[9px] mt-0.5"></i>
                </button>
                <div id="action-main-menu" class="hidden absolute right-0 mt-1 w-40 bg-white border border-gray-200 shadow-xl rounded z-50 text-left">
                    <ul class="py-1">
                        <li>
                            <a href="#" class="block px-4 py-2 text-[11px] text-gray-700 hover:bg-gray-100 transition">
                                <i class="fa-solid fa-file-import mr-1.5 text-green-600"></i> Import Excel
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-[11px] text-gray-700 hover:bg-gray-100 transition">
                                <i class="fa-solid fa-file-export mr-1.5 text-blue-600"></i> Export Excel
                            </a>
                        </li>
                        <li class="border-t border-gray-100 mt-1 pt-1">
                            <a href="admin.php" class="block px-4 py-2 text-[11px] text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay về
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
      </div>

      <div class="bg-white rounded shadow-sm border border-[#E6E9ED] mb-4">
        <div class="px-4 py-2 border-b border-[#E6E9ED] bg-[#5a738e] text-white font-bold text-[13px] flex justify-between items-center">
            <span><i class="fa-regular fa-calendar-days mr-1.5"></i> Thống kê</span>
        </div>
        
        <div class="p-6 grid grid-cols-5 gap-4 text-center">
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16" viewBox="0 0 33.83098862 33.83098862">
                  <circle class="stroke-gray-100" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <circle id="chart-chuaduyet" class="circle-chart__circle stroke-[#26B99A]" stroke-width="2" stroke-dasharray="0,100" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <text id="text-chuaduyet" x="16.91549431" y="18" alignment-baseline="middle" text-anchor="middle" font-size="6" fill="#666">0%</text>
                </svg>
                <span id="label-chuaduyet" class="mt-3 text-gray-500 font-medium text-[13px]">Chưa duyệt (0/0)</span>
            </div>
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16" viewBox="0 0 33.83098862 33.83098862">
                  <circle class="stroke-gray-100" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <circle id="chart-daduyet" class="circle-chart__circle stroke-[#d9534f]" stroke-width="2" stroke-dasharray="0,100" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <text id="text-daduyet" x="16.91549431" y="18" alignment-baseline="middle" text-anchor="middle" font-size="6" fill="#666">0%</text>
                </svg>
                <span id="label-daduyet" class="mt-3 text-gray-500 font-medium text-[13px]">Đã duyệt (0/0)</span>
            </div>
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16" viewBox="0 0 33.83098862 33.83098862">
                  <circle class="stroke-gray-100" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <circle id="chart-chuahoc" class="circle-chart__circle stroke-[#f0ad4e]" stroke-width="2" stroke-dasharray="0,100" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <text id="text-chuahoc" x="16.91549431" y="18" alignment-baseline="middle" text-anchor="middle" font-size="6" fill="#666">0%</text>
                </svg>
                <span id="label-chuahoc" class="mt-3 text-gray-500 font-medium text-[13px]">Chưa học (0/0)</span>
            </div>
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16" viewBox="0 0 33.83098862 33.83098862">
                  <circle class="stroke-gray-100" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <circle id="chart-danghoc" class="circle-chart__circle stroke-[#5bc0de]" stroke-width="2" stroke-dasharray="0,100" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <text id="text-danghoc" x="16.91549431" y="18" alignment-baseline="middle" text-anchor="middle" font-size="6" fill="#666">0%</text>
                </svg>
                <span id="label-danghoc" class="mt-3 text-gray-500 font-medium text-[13px]">Đang học (0/0)</span>
            </div>
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16" viewBox="0 0 33.83098862 33.83098862">
                  <circle class="stroke-gray-100" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <circle id="chart-hoanthanh" class="circle-chart__circle stroke-[#26B99A]" stroke-width="2" stroke-dasharray="0,100" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431"/>
                  <text id="text-hoanthanh" x="16.91549431" y="18" alignment-baseline="middle" text-anchor="middle" font-size="6" fill="#666">0%</text>
                </svg>
                <span id="label-hoanthanh" class="mt-3 text-gray-500 font-medium text-[13px]">Hoàn thành (0/0)</span>
            </div>
        </div>
      </div>

      <div class="bg-white rounded shadow-sm border border-[#E6E9ED]">
        <table class="w-full text-left border-collapse">
          <thead class="bg-[#f9f9f9] border-b border-gray-200 text-[#73879C] font-bold text-[12px]">
            <tr>
              <th class="px-3 py-2.5 w-10 text-center border-r border-gray-200"><input type="checkbox"></th>
              <th class="px-3 py-2.5 border-r border-gray-200">Tài khoản</th>
              <th class="px-3 py-2.5 border-r border-gray-200">Họ và tên</th>
              <th class="px-3 py-2.5 border-r border-gray-200">Email</th>
              <th class="px-3 py-2.5 border-r border-gray-200 w-24">Đơn vị</th>
              <th class="px-3 py-2.5 border-r border-gray-200 w-28 text-center">Ngày tham gia</th>
              <th class="px-3 py-2.5 border-r border-gray-200 w-24 text-center">Trạng thái</th>
              <th class="px-3 py-2.5 text-center w-36">Hành động</th>
            </tr>
          </thead>
          <tbody id="student-list" class="text-gray-600 text-[12px]">
              <tr><td colspan="8" class="text-center py-4">Đang tải dữ liệu...</td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <div id="addStudentModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center">
      <div class="bg-white w-[600px] rounded shadow-xl flex flex-col max-h-[80vh]">
          <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center bg-[#f9f9f9] rounded-t">
              <h3 class="text-[13px] text-[#2A3F54] uppercase font-bold"><i class="fa-solid fa-user-plus mr-1"></i> THÊM HỌC VIÊN VÀO KHÓA HỌC</h3>
              <button onclick="closeAddModal()" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
          </div>
          
          <div class="p-4 border-b border-gray-100 flex gap-2">
              <input type="text" id="searchInput" placeholder="Nhập tên, username hoặc email để tìm kiếm..." class="flex-1 border border-gray-300 px-3 py-2 outline-none focus:border-[#1ABB9C] rounded text-xs" onkeydown="if(event.key === 'Enter') searchUsers()">
              <button onclick="searchUsers()" class="bg-[#337AB7] text-white px-4 py-2 rounded text-xs font-bold hover:bg-[#286090] transition flex items-center gap-1">
                  <i class="fa-solid fa-search"></i> Tìm
              </button>
          </div>

          <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
              <table class="w-full text-left border-collapse bg-white border border-gray-200">
                  <thead class="bg-gray-100 text-[#73879C] text-[11px]">
                      <tr>
                          <th class="px-3 py-2 border-b">Tài khoản</th>
                          <th class="px-3 py-2 border-b">Họ tên & Email</th>
                          <th class="px-3 py-2 border-b text-center w-20">Hành động</th>
                      </tr>
                  </thead>
                  <tbody id="search-results" class="text-[11px] text-gray-600">
                      <tr><td colspan="3" class="text-center py-4 italic text-gray-400">Hãy nhập từ khóa để tìm kiếm...</td></tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>

  <script>
    const courseId = <?php echo $course_id; ?>;

    async function loadStudents() {
        try {
            const res = await fetch(`api/get_students.php?course_id=${courseId}`);
            const students = await res.json();
            
            // Xử lý thống kê (Bao quát cả dữ liệu dạng chữ và số)
            const total = students.length;
            const chuaDuyet = students.filter(s => s.status === 'Chờ duyệt' || s.status == 0).length;
            const daDuyet = students.filter(s => s.status === 'Đang học' || s.status === 'Đã hoàn thành' || s.status == 1).length;

            const calcPct = (count, total) => total === 0 ? "0.00" : ((count/total)*100).toFixed(2);
            
            document.getElementById('chart-chuaduyet').style.strokeDasharray = `${calcPct(chuaDuyet, total)},100`;
            document.getElementById('text-chuaduyet').textContent = `${calcPct(chuaDuyet, total)}%`;
            document.getElementById('label-chuaduyet').textContent = `Chưa duyệt (${chuaDuyet}/${total})`;

            document.getElementById('chart-daduyet').style.strokeDasharray = `${calcPct(daDuyet, total)},100`;
            document.getElementById('text-daduyet').textContent = `${calcPct(daDuyet, total)}%`;
            document.getElementById('label-daduyet').textContent = `Đã duyệt (${daDuyet}/${total})`;

            const tbody = document.getElementById('student-list');
            if(total === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 italic">Chưa có học viên nào.</td></tr>`;
                return;
            }

            let html = '';
            students.forEach((s) => {
                // NHẬN DIỆN TRẠNG THÁI CHUẨN XÁC
                const isApproved = (s.status === 'Đang học' || s.status === 'Đã hoàn thành' || s.status == 1);
                
                // CỘT HIỂN THỊ
                const statusText = isApproved ? 'Đã duyệt' : 'Chưa duyệt';
                const statusColor = isApproved ? 'text-[#26B99A]' : 'text-red-500';
                
                const studentId = s.user_id || s.id; 

                html += `
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                      <td class="px-3 py-2.5 text-center border-r border-gray-200"><input type="checkbox"></td>
                      <td class="px-3 py-2.5 border-r border-gray-200 text-[#337AB7]">${s.username || ''}</td>
                      <td class="px-3 py-2.5 border-r border-gray-200">${s.fullname || ''}</td>
                      <td class="px-3 py-2.5 border-r border-gray-200 text-[#337AB7]">${s.email || ''}</td>
                      <td class="px-3 py-2.5 border-r border-gray-200">${s.department || ''}</td>
                      <td class="px-3 py-2.5 border-r border-gray-200 text-center">${s.join_date || 'Vừa xong'}</td>
                      
                      <!-- Cột trạng thái -->
                      <td class="px-3 py-2.5 border-r border-gray-200 text-center font-bold ${statusColor}">${statusText}</td>
                      
                      <td class="px-3 py-2.5 flex justify-center items-center gap-1.5 overflow-visible">
                          
                          <!-- NÚT 1: LOẠI BỎ -->
                          <button onclick="removeStudent(${courseId}, ${studentId})" class="bg-[#d9534f] text-white px-2 py-1 rounded-sm text-[11px] shadow-sm hover:bg-[#c9302c] transition">
                              Loại bỏ
                          </button>

                          <!-- NÚT 2: KHÁC (Menu Dropdown) -->
                          <div class="relative">
                              <button onclick="toggleDropdown('action-${studentId}')" class="bg-[#b4bcc2] text-white px-2 py-1 rounded-sm text-[11px] shadow-sm hover:bg-gray-500 transition flex items-center">
                                  Khác <i class="fa-solid fa-chevron-down text-[8px] ml-1 mt-[1px]"></i>
                              </button>
                              
                              <div id="action-${studentId}" class="hidden absolute right-0 mt-1 w-36 bg-white border border-gray-200 shadow-xl z-50 text-left rounded">
                                  <ul class="py-1">
                                      <!-- LOGIC ĐẢO NGƯỢC NÚT DUYỆT / BỎ DUYỆT -->
                                      ${!isApproved 
                                          ? `<li><a href="javascript:void(0)" onclick="updateStudentStatus(${courseId}, ${studentId}, 'Đang học')" class="block px-4 py-1.5 text-green-600 hover:bg-green-50 font-bold"><i class="fa-solid fa-check w-4"></i> Duyệt vào học</a></li>` 
                                          : `<li><a href="javascript:void(0)" onclick="updateStudentStatus(${courseId}, ${studentId}, 'Chờ duyệt')" class="block px-4 py-1.5 text-orange-500 hover:bg-orange-50 font-bold"><i class="fa-solid fa-rotate-left w-4"></i> Bỏ duyệt</a></li>`
                                      }
                                      
                                      <li><a href="#" class="block px-4 py-1.5 text-gray-700 hover:bg-gray-100 border-t border-gray-100 mt-1"><i class="fa-solid fa-book-open w-4"></i> Học tập</a></li>
                                      <li><a href="#" class="block px-4 py-1.5 text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-clock-rotate-left w-4"></i> Lịch sử học tập</a></li>
                                      <li><a href="#" class="block px-4 py-1.5 text-gray-700 hover:bg-gray-100"><i class="fa-regular fa-comment w-4"></i> Nhận xét</a></li>
                                  </ul>
                              </div>
                          </div>
                      </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;

        } catch(e) {
            console.error(e);
            document.getElementById('student-list').innerHTML = `<tr><td colspan="8" class="text-center py-4 text-red-500">Lỗi kết nối cơ sở dữ liệu.</td></tr>`;
        }
    }

    function toggleDropdown(id) {
        document.querySelectorAll('[id^="action-"]').forEach(el => {
            if (el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }
    
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="action-"]').forEach(el => el.classList.add('hidden'));
        }
    });

    function openAddModal() {
        document.getElementById('addStudentModal').classList.remove('hidden');
        searchUsers(); 
    }

    function closeAddModal() {
        document.getElementById('addStudentModal').classList.add('hidden');
        document.getElementById('searchInput').value = ''; 
    }

    async function searchUsers() {
    const keyword = document.getElementById('searchInput').value;
    const tbody = document.getElementById('search-results');
    
    tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-gray-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Đang tìm kiếm học viên...</td></tr>`;
    
    try {
        const res = await fetch(`api/search_users.php?course_id=${courseId}&q=${encodeURIComponent(keyword)}`);
        
        // Nhận dữ liệu text trước để kiểm tra xem có phải là JSON không
        const textData = await res.text();
        
        let users;
        try {
            users = JSON.parse(textData);
        } catch (e) {
            console.error("Lỗi parse JSON. Server trả về:", textData);
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-red-600 font-bold">Lỗi Server (500). Vui lòng nhấn F12 -> Console để xem chi tiết.</td></tr>`;
            return;
        }

        // Nếu Backend báo lỗi đường dẫn db hoặc SQL
        if (users.error) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-red-600 font-bold"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Lỗi API: ${users.error}</td></tr>`;
            return;
        }
        
        if(users.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-red-500 italic"><i class="fa-solid fa-circle-exclamation mr-2"></i> Không tìm thấy nhân sự phù hợp hoặc nhân sự đã có trong khóa học.</td></tr>`;
            return;
        }

        let html = '';
        users.forEach(u => {
            html += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="px-3 py-3 text-[#337AB7] font-medium">
                        ${u.username}
                        <div class="text-gray-400 text-[10px] font-normal uppercase">${u.department || 'Chưa cập nhật'}</div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="font-bold text-gray-700">${u.fullname}</div>
                        <div class="text-gray-400 text-[10px]">${u.email}</div>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <button onclick="addStudent(${u.id})" class="bg-[#1ABB9C] text-white px-4 py-1.5 rounded text-[10px] font-bold hover:bg-[#159a80] transition shadow-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Chọn
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    } catch (error) {
        console.error("Lỗi Fetch:", error);
        tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-red-600 font-bold"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Lỗi kết nối API tìm kiếm. File api/search_users.php có thể bị sai tên hoặc sai vị trí.</td></tr>`;
    }
}

    async function addStudent(userId) {
        try {
            const response = await fetch('api/add_student.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ course_id: courseId, user_id: userId })
            });
            const result = await response.json();
            if(result.success) {
                await searchUsers(); 
                await loadStudents(); 
            } else { alert('Thông báo: ' + result.message); }
        } catch (error) { alert('Không thể kết nối tới máy chủ!'); }
    }

    // GỌI API DUYỆT VÀ ĐỔI TRẠNG THÁI VỀ CHỮ ĐỂ KHỚP VỚI DATABASE
    async function updateStudentStatus(cId, uId, newStatus) {
        if(confirm(`Xác nhận đổi trạng thái học viên thành: ${newStatus === 'Đang học' ? 'ĐÃ DUYỆT' : 'CHƯA DUYỆT'}?`)) {
            try {
                const res = await fetch('api/update_student_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ course_id: cId, user_id: uId, status: newStatus })
                });
                const data = await res.json();
                if(data.success) {
                    loadStudents(); // Tự động load lại bảng để đổi nút
                } else { alert("Lỗi DB: " + data.message); }
            } catch(err) { alert("Lỗi kết nối máy chủ!"); }
        }
    }

    async function removeStudent(cId, uId) {
        if(confirm(`Bạn có chắc chắn muốn xóa học viên này khỏi khóa học? Hành động này sẽ xóa toàn bộ tiến độ.`)) {
            try {
                const res = await fetch('api/remove_student.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ course_id: cId, user_id: uId })
                });
                const data = await res.json();
                if(data.success) { loadStudents(); } 
                else { alert("Lỗi: " + data.message); }
            } catch(err) { alert("Lỗi kết nối máy chủ API!"); }
        }
    }

    loadStudents();
  </script>
</body>
</html>