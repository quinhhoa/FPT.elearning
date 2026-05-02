<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Quản lý giáo trình học tập</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #333; }
    .btn-action { @apply px-3 py-1 rounded text-[11px] flex items-center gap-1 transition shadow-sm border; }
    .lesson-type-item { transition: background-color 0.2s; color: #337AB7; font-size: 13px; }
    .lesson-type-item:hover { background-color: #f9f9f9; text-decoration: none; }
  </style>
</head>
<body class="bg-[#F7F7F7] flex min-h-screen overflow-x-hidden text-[12px]">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="flex-1 ml-[200px] flex flex-col min-h-screen">
    <header class="h-12 bg-white border-b border-[#D9DEE4] flex justify-between items-center px-4 z-40 sticky top-0">
      <div class="flex items-center gap-3 text-[#73879C]"><i class="fa-solid fa-bars"></i><span class="font-medium">Trang chủ</span></div>
      <div class="flex items-center gap-2"><img src="https://i.pravatar.cc/150?img=11" class="w-7 h-7 rounded-full border"><span>Quỳnh Hoa</span></div>
    </header>

    <main class="p-4 flex-1 relative">
      <div class="flex justify-between items-end mb-4">
        <div>
          <h1 class="text-xl font-normal text-[#2A3F54]">KHÓA HỌC <small class="text-xs text-gray-400">Giáo trình học tập</small></h1>
          <div class="text-[11px] text-gray-500 bg-gray-100 py-0.5 px-2 rounded inline-flex mt-1">
            <i class="fa-solid fa-house mr-1"></i> Dashboard / Quản lý khóa học / Giáo trình học tập
          </div>
        </div>
        <div class="flex gap-1.5">
          <button onclick="window.location.href='admin.php'" class="bg-[#e2e2e2] text-gray-600 px-3 py-1.5 rounded flex items-center gap-1.5 border border-gray-300">
            <i class="fa-solid fa-reply"></i> Quay lại
          </button>
          
          <button onclick="openAddModuleModal()" class="bg-[#1ABB9C] text-white px-3 py-1.5 rounded flex items-center gap-1.5 border border-[#169F85] hover:bg-[#159a80]">
            <i class="fa-solid fa-plus"></i> Thêm học phần
          </button>
          
          <button class="bg-[#e2e2e2] text-gray-600 px-3 py-1.5 rounded flex items-center gap-1.5 border border-gray-300">
            Hành động <i class="fa-solid fa-chevron-down text-[8px]"></i>
          </button>
        </div>
      </div>

      <div class="bg-white rounded shadow-sm border border-[#E6E9ED]">
        <table class="w-full text-left">
          <thead class="bg-[#f9f9f9] border-b border-gray-200 text-[#73879C] font-bold">
            <tr>
              <th class="px-4 py-2 w-10 text-center"><input type="checkbox"></th>
              <th class="px-4 py-2">Tiêu đề học phần</th>
              <th class="px-4 py-2 text-center w-24">Sắp xếp</th>
              <th class="px-4 py-2 text-center w-24">Trạng thái</th>
              <th class="px-4 py-2 text-center w-32">Hành động</th>
            </tr>
          </thead>
          <tbody id="modules-list" class="text-gray-600">
             <tr><td colspan="5" class="p-4 text-center">Đang tải dữ liệu...</td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <div id="addModuleModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center">
      <div class="bg-white w-[500px] rounded shadow-xl overflow-hidden">
          <div class="border-b border-gray-200 px-4 py-3 bg-white flex justify-between items-center">
              <h3 class="text-[14px] text-[#2A3F54] uppercase font-bold">THÊM MỚI HỌC PHẦN</h3>
              <button onclick="closeAddModuleModal()" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
          </div>
          
          <form action="api/add_module.php" method="POST">
              <input type="hidden" name="course_id" id="current_course_id">
              
              <div class="p-5 bg-white space-y-4">
                  <div>
                      <label class="block text-xs font-bold text-gray-600 mb-1">Tên học phần: <span class="text-red-500">*</span></label>
                      <input type="text" name="title" required class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-[#1ABB9C] text-gray-700 rounded-sm text-xs" placeholder="Nhập tên học phần...">
                  </div>
                  <div>
                      <label class="block text-xs font-bold text-gray-600 mb-1">Mô tả:</label>
                      <textarea name="description" rows="3" class="w-full border border-gray-300 px-3 py-2 outline-none focus:border-[#1ABB9C] text-gray-700 rounded-sm text-xs" placeholder="Nhập mô tả học phần..."></textarea>
                  </div>
              </div>

              <div class="border-t border-gray-200 px-4 py-3 bg-[#f9f9f9] flex justify-end gap-2">
                  <button type="button" onclick="closeAddModuleModal()" class="bg-[#e2e2e2] text-gray-700 px-4 py-1.5 border border-gray-300 rounded text-xs hover:bg-gray-300 transition">Hủy bỏ</button>
                  <button type="submit" class="bg-[#1ABB9C] text-white px-4 py-1.5 border border-[#169F85] rounded text-xs hover:bg-[#159a80] transition flex items-center gap-1.5">
                      <i class="fa-solid fa-check"></i> Lưu
                  </button>
              </div>
          </form>
      </div>
  </div>

  <div id="lessonTypeModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center">
      <div class="bg-white w-[500px] rounded shadow-xl overflow-hidden">
          <div class="border-b border-gray-200 px-4 py-3 bg-white">
              <h3 class="text-[14px] text-gray-600 uppercase">CHỌN LOẠI BÀI GIẢNG</h3>
          </div>
          
          <div class="p-4 bg-white max-h-[70vh] overflow-y-auto">
              <ul class="space-y-1">
                  <li><button onclick="navigateToAddLesson('video')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-file-video text-[#337AB7] w-4"></i> Thêm bài giảng Video</button></li>
                  <li><button onclick="navigateToAddLesson('audio')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-volume-high text-[#337AB7] w-4"></i> Thêm bài giảng Audio</button></li>
                  <li><button onclick="navigateToAddLesson('embed')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-code text-[#337AB7] w-4"></i> Thêm bài giảng Mã nhúng</button></li>
                  <li><button onclick="navigateToAddLesson('scorm')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-video text-[#337AB7] w-4"></i> Thêm bài giảng SCORM</button></li>
                  <li><button onclick="navigateToAddLesson('aicc')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-film text-[#337AB7] w-4"></i> Thêm bài giảng AICC</button></li>
                  <li><button onclick="navigateToAddLesson('document')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-regular fa-file-pdf text-[#337AB7] w-4"></i> Thêm tài liệu học</button></li>
                  <li><button onclick="navigateToAddLesson('html')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-brands fa-html5 text-[#337AB7] w-4"></i> Thêm bài giảng Html</button></li>
                  <li><button onclick="navigateToAddLesson('online')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-headset text-[#337AB7] w-4"></i> Thêm Lớp học/ Hội thảo Online</button></li>
                  <li><button onclick="navigateToAddLesson('test')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-graduation-cap text-[#337AB7] w-4"></i> Thêm bài thi test</button></li>
                  <li><button onclick="navigateToAddLesson('survey')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-square-poll-vertical text-[#337AB7] w-4"></i> Thêm phiếu đánh giá kết quả đào tạo</button></li>
                  <li><button onclick="navigateToAddLesson('quiz')" class="lesson-type-item w-full text-left px-4 py-2 flex items-center gap-3"><i class="fa-solid fa-graduation-cap text-[#337AB7] w-4"></i> Thêm bài quiz</button></li>
              </ul>
          </div>

          <div class="border-t border-gray-200 px-4 py-3 bg-[#f9f9f9] flex justify-end">
              <button onclick="closeLessonTypeModal()" class="bg-[#e2e2e2] text-gray-700 px-4 py-1.5 border border-gray-300 rounded text-xs hover:bg-gray-300 transition">Đóng</button>
          </div>
      </div>
  </div>

  <script>
    // Lấy ID khóa học từ URL hiện tại
    const urlParams = new URLSearchParams(window.location.search);
    const currentCourseId = urlParams.get('course_id') || 0;

    // --- LOGIC LOAD DỮ LIỆU THẬT TỪ DATABASE ---
    async function loadModules() {
        try {
            const resMod = await fetch(`api/get_modules.php?course_id=${currentCourseId}`);
            const modules = await resMod.json();
            
            const resLes = await fetch(`api/get_lessons.php?course_id=${currentCourseId}`);
            const allLessons = await resLes.json();

            const tbody = document.getElementById('modules-list');
            if (modules.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-gray-500 italic">Chưa có dữ liệu giáo trình.</td></tr>`;
                return;
            }

            let html = '';
            modules.forEach((mod, index) => {
                // --- DÒNG HỌC PHẦN ---
                html += `
                    <tr class="bg-gray-50 border-b border-gray-200 font-bold text-[#2A3F54]">
                      <td class="px-4 py-3 text-center"><input type="checkbox"></td>
                      <td class="px-4 py-3 text-[13px]">
                        <i class="fa-solid fa-folder-open text-orange-400 mr-2"></i> ${String(index + 1).padStart(2, '0')}- ${mod.title}
                      </td>
                      <td class="text-center">
                         <div class="flex flex-col items-center text-[#337AB7] cursor-pointer"><i class="fa-solid fa-caret-up"></i><i class="fa-solid fa-caret-down"></i></div>
                      </td>
                      <td class="text-center"><input type="checkbox" ${mod.status == 1 ? 'checked' : ''}></td>
                      
                      <td class="px-4 py-3 align-middle">
                        <div class="w-[140px] mx-auto flex justify-start gap-1.5">
                            <button class="bg-[#1ABB9C] text-white px-2.5 py-1 rounded text-[11px] hover:bg-[#159a80]">Sửa</button>
                            <button onclick="openLessonTypeModal(${mod.id})" class="bg-[#337AB7] text-white px-2.5 py-1 rounded text-[11px] flex items-center gap-1 hover:bg-[#286090]">
                              Thêm <i class="fa-solid fa-chevron-down text-[8px]"></i>
                            </button>
                        </div>
                      </td>
                    </tr>
                `;

                const lessonsInModule = allLessons.filter(l => l.module_id == mod.id);

                // --- DÒNG BÀI GIẢNG ---
                lessonsInModule.forEach((les, idx) => {
                    let icon = 'fa-file-lines text-gray-400'; 
                    if(les.lesson_type === 'video') icon = 'fa-file-video text-blue-500';
                    if(les.lesson_type === 'document') icon = 'fa-file-pdf text-red-500';
                    if(les.lesson_type === 'scorm') icon = 'fa-box-archive text-yellow-600';
                    if(les.lesson_type === 'test') icon = 'fa-graduation-cap text-green-500';
                    if(les.lesson_type === 'embed') icon = 'fa-code text-gray-500';
                    
                    html += `
                        <tr class="border-b border-gray-100 hover:bg-blue-50/30 transition-colors">
                          <td class="px-4 py-2 text-center text-gray-400">${idx + 1}</td>
                          <td class="px-4 py-2 pl-10 text-[12px] text-gray-700">
                            <i class="fa-solid ${icon} mr-2"></i> ${les.title}
                          </td>
                          <td class="text-center"><i class="fa-solid fa-arrow-down-long text-gray-300"></i></td>
                          <td class="text-center"><input type="checkbox" ${les.is_required == 1 ? 'checked' : ''}></td>
                          
                          <td class="px-4 py-2 align-middle overflow-visible">
                            <div class="w-[140px] mx-auto flex justify-start gap-1.5">
                                <button class="bg-[#1ABB9C] text-white px-2.5 py-1 rounded text-[11px] hover:bg-[#159a80]">Sửa</button>
                                <button onclick="deleteLesson(${les.id})" class="bg-[#d9534f] text-white px-2.5 py-1 rounded text-[11px] hover:bg-[#c9302c]">Xóa</button>
                                
                                <div class="relative">
                                    <button onclick="toggleDropdown('dropdown-lesson-${les.id}')" class="bg-[#e2e2e2] text-gray-600 px-2.5 py-1 rounded text-[11px] border shadow-sm flex items-center gap-1 hover:bg-[#d4d4d4] transition">
                                        Khác <i class="fa-solid fa-chevron-down text-[8px]"></i>
                                    </button>
                                    
                                    <div id="dropdown-lesson-${les.id}" class="hidden absolute right-0 mt-1.5 w-36 bg-white border border-gray-200 shadow-lg rounded z-50 text-left">
                                        <div class="absolute -top-1.5 right-4 w-3 h-3 bg-white border-l border-t border-gray-200 transform rotate-45"></div> 
                                        <ul class="py-1 relative z-10 bg-white rounded">
                                            <li><a href="#" class="block px-3 py-1.5 text-[11px] text-gray-700 hover:bg-gray-100 transition"><i class="fa-regular fa-bookmark mr-1.5 text-gray-400"></i> Điều kiện vào học</a></li>
                                            <li><a href="#" class="block px-3 py-1.5 text-[11px] text-gray-700 hover:bg-gray-100 transition"><i class="fa-regular fa-bookmark mr-1.5 text-gray-400"></i> Nội dung khảo sát</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                          </td>
                        </tr>
                    `;
                });
            });

            tbody.innerHTML = html;

        } catch (error) {
            console.error("Lỗi load dữ liệu:", error);
        }
    }

    // --- LOGIC POPUP THÊM HỌC PHẦN ---
    function openAddModuleModal() {
        document.getElementById('current_course_id').value = currentCourseId;
        document.getElementById('addModuleModal').classList.remove('hidden');
    }

    function closeAddModuleModal() {
        document.getElementById('addModuleModal').classList.add('hidden');
    }

    // --- LOGIC POPUP CHỌN LOẠI BÀI GIẢNG ---
    let currentModuleId = null;

    function openLessonTypeModal(moduleId) {
        currentModuleId = moduleId;
        document.getElementById('lessonTypeModal').classList.remove('hidden');
    }

    function closeLessonTypeModal() {
        document.getElementById('lessonTypeModal').classList.add('hidden');
        currentModuleId = null;
    }

    function navigateToAddLesson(type) {
        if (currentModuleId !== null) {
            window.location.href = `admin_add_lesson.php?course_id=${currentCourseId}&module_id=${currentModuleId}&type=${type}`;
        }
    }
    async function deleteLesson(id) {
        if (confirm("Bạn có chắc chắn muốn xóa bài giảng này không? Hành động này không thể hoàn tác.")) {
            try {
                const formData = new FormData();
                formData.append('id', id);

                const res = await fetch('api/delete_lesson.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await res.json();
                if (result.success) {
                    // Xóa thành công thì tự động load lại bảng mà không cần F5
                    loadModules(); 
                } else {
                    alert("Lỗi khi xóa: " + result.message);
                }
            } catch (error) {
                console.error("Lỗi:", error);
                alert("Không thể kết nối đến máy chủ!");
            }
        }
    }

    // --- LOGIC MỞ MENU DROP-DOWN "KHÁC" ---
    function toggleDropdown(id) {
        // Đóng tất cả các menu khác đang mở (nếu có)
        document.querySelectorAll('[id^="dropdown-lesson-"]').forEach(el => {
            if (el.id !== id) el.classList.add('hidden');
        });
        
        // Bật/tắt menu được click
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    // Tự động đóng Dropdown khi click ra ngoài màn hình
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="dropdown-lesson-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });

    // Khởi chạy hàm lấy dữ liệu khi trang vừa mở
    loadModules();
  </script>
</body>
</html>