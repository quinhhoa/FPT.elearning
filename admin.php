<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Quản lý danh sách khóa học</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #73879C; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: #172D44; border-radius: 2px; }
  </style>
</head>
<body class="bg-[#F7F7F7] flex min-h-screen overflow-x-hidden text-[12px]">

  <?php include 'includes/admin_sidebar.php'; ?>

  <div class="flex-1 ml-[200px] flex flex-col min-h-screen">
    <header class="h-12 bg-white border-b border-[#D9DEE4] flex justify-between items-center px-4 z-40 sticky top-0">
      <div class="flex items-center gap-3 text-[#73879C]"><i class="fa-solid fa-bars cursor-pointer text-base"></i><span class="font-medium">Trang chủ</span></div>
      <div class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded">
        <img src="https://i.pravatar.cc/150?img=11" class="w-7 h-7 rounded-full border border-gray-300">
        <span class="text-xs font-medium">Quỳnh Hoa</span><i class="fa-solid fa-chevron-down text-[9px]"></i>
      </div>
    </header>

    <main class="p-4 flex-1">
      <div class="bg-white rounded shadow-sm border border-[#E6E9ED] p-3">
        <div class="flex justify-between items-center border-b border-[#E6E9ED] pb-2 mb-3">
          <div>
            <h1 class="text-lg font-normal text-[#2A3F54] mb-0.5">Khóa học <small class="text-xs text-gray-400">Quản lý danh sách khóa học</small></h1>
            <div class="text-[11px] flex items-center gap-1.5 text-gray-500 bg-gray-100 py-0.5 px-2 rounded inline-flex"><i class="fa-solid fa-house"></i> Dashboard / Quản lý khóa học</div>
          </div>
          <button onclick="location.href='admin_add_course.php'" class="bg-[#1ABB9C] text-white px-3 py-1 rounded flex items-center gap-1.5 hover:bg-[#159a80] border border-[#169F85] text-[11px]">
            <i class="fa-solid fa-plus"></i> Thêm mới khóa học
          </button>
        </div>

        <div class="flex gap-1.5 mb-3">
          <input type="text" id="filter-keyword" placeholder="--- Tên khóa học ---" class="border border-gray-300 px-2 py-1 rounded w-1/4 outline-none focus:border-[#1ABB9C] text-[11px]">
          <select id="filter-category" class="border border-gray-300 px-2 py-1 rounded w-1/5 outline-none text-gray-500 text-[11px]">
            <option value="">Chọn danh mục</option>
          </select>
          <select id="filter-reg-type" class="border border-gray-300 px-2 py-1 rounded w-1/5 outline-none text-gray-500 text-[11px]">
            <option value="">Hình thức đăng ký</option>
            <option value="Không cho đăng ký">Không cho đăng ký</option>
            <option value="Kiểm duyệt">Kiểm duyệt</option>
            <option value="Đăng ký tự do">Đăng ký tự do</option>
          </select>
          <button type="button" onclick="loadAdminCourses()" class="bg-[#1ABB9C] text-white px-4 py-1 rounded flex items-center gap-1.5 hover:bg-[#159a80] border border-[#169F85] text-[11px]">
            <i class="fa-solid fa-search"></i> Tìm kiếm
          </button>
        </div>

        <div class="overflow-visible pb-20"> 
          <table class="w-full text-left border border-gray-200">
            <thead class="bg-[#f9f9f9] border-b border-gray-200 text-[#73879C]">
              <tr>
                <th class="px-1.5 py-1 border-r text-center w-8"><input type="checkbox"></th>
                <th class="px-1.5 py-1 border-r text-center w-10 font-bold">STT</th>
                <th class="px-1.5 py-1 border-r font-bold">Mã khóa học</th>
                <th class="px-1.5 py-1 border-r font-bold w-14">Ảnh</th>
                <th class="px-1.5 py-1 border-r font-bold w-40">Tên khóa học</th>
                <th class="px-1.5 py-1 border-r font-bold">Loại khóa học</th>
                <th class="px-1.5 py-1 border-r font-bold text-center">Học viên</th>
                <th class="px-1.5 py-1 border-r font-bold">Hình thức đăng ký</th>
                <th class="px-1.5 py-1 border-r font-bold">Thời lượng</th>
                <th class="px-1.5 py-1 border-r font-bold text-center">Trạng thái</th>
                <th class="px-1.5 py-1 font-bold text-center w-36">Hành động</th>
              </tr>
            </thead>
            <tbody id="admin-course-list" class="text-gray-600">
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
  
<script>
    // 1. Tải danh mục an toàn
    async function loadFilterCategories() {
        try {
            const res = await fetch('api/get_courses.php?view=admin');
            const categories = await res.json();
            const select = document.getElementById("filter-category");
            categories.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });
        } catch (error) { console.error("Lỗi lấy danh mục", error); }
    }

    // 2. Tải & Lọc Khóa học
    async function loadAdminCourses() {
        try {
            const keyword = document.getElementById("filter-keyword").value;
            const categoryId = document.getElementById("filter-category").value;
            const regType = document.getElementById("filter-reg-type").value;

            let url = `api/get_courses.php?view=admin&search=${encodeURIComponent(keyword)}&category_id=${encodeURIComponent(categoryId)}&reg_type=${encodeURIComponent(regType)}`;
            
            const res = await fetch(url);
            const courses = await res.json();
            const tbody = document.getElementById("admin-course-list");
            
            if (courses.error) {
                tbody.innerHTML = `<tr><td colspan="11" class="p-4 text-center text-red-500 font-bold"><i class="fa-solid fa-triangle-exclamation"></i> ${courses.message}</td></tr>`;
                return;
            }

            if (courses.length === 0) {
                tbody.innerHTML = `<tr><td colspan="11" class="p-6 text-center text-gray-500 italic"><i class="fa-solid fa-magnifying-glass mb-2 text-2xl"></i><br>Không tìm thấy khóa học nào phù hợp với bộ lọc.</td></tr>`;
                return;
            }

            // ĐÃ THÊM LOGIC BẢO VỆ ẢNH Ở ĐÂY (onerror)
            tbody.innerHTML = courses.map((c, index) => `
                <tr class="border-b border-gray-200 hover:bg-[#f5f7fa]">
                    <td class="px-1.5 py-1 border-r text-center"><input type="checkbox"></td>
                    <td class="px-1.5 py-1 border-r text-center">${index + 1}</td>
                    <td class="px-1.5 py-1 border-r text-[10px]">${c.course_code || 'Course-' + c.id}</td>
                    <td class="px-1.5 py-1 border-r">
                        <img src="${c.thumbnail_url || 'https://placehold.co/600x400/115293/FFFFFF?text=ELEARNING+FPT'}" 
                             onerror="this.src='https://placehold.co/600x400/115293/FFFFFF?text=ELEARNING+FPT'" 
                             class="w-10 h-6 object-cover border border-gray-300">
                    </td>
                    <td class="px-1.5 py-1 border-r font-medium text-[#73879C] leading-tight">${c.title}</td>
                    <td class="px-1.5 py-1 border-r text-[11px]">Online</td>
                    <td class="px-1.5 py-1 border-r text-center">
                        ${c.max_students == 0 || c.students == 0 ? '<span class="text-[#1ABB9C] font-bold italic">Không giới hạn</span>' : (c.max_students || c.students)}
                    </td>
                    <td class="px-1.5 py-1 border-r text-[11px]">${c.registration_type}</td>
                    <td class="px-1.5 py-1 border-r text-[11px]">${c.time_range || 'Không giới hạn'}</td>
                    
                    <td class="px-1.5 py-1 border-r text-center">
                        <input type="checkbox" ${c.status == 1 ? 'checked' : ''} onclick="return false;">
                    </td>
                    
                    <td class="px-1.5 py-1 text-center flex justify-center items-center gap-1 relative">
                        <button onclick="location.href='admin_edit_course.php?id=${c.id}'" class="bg-[#337AB7] text-white text-[10px] px-1.5 py-0.5 rounded hover:opacity-80"><i class="fa-solid fa-edit"></i> Sửa</button>
                        
                        <button onclick="deleteCourse(${c.id})" class="bg-[#D9534F] text-white text-[10px] px-1.5 py-0.5 rounded hover:opacity-80"><i class="fa-solid fa-trash"></i> Xóa</button>
                        
                        <div class="relative group cursor-pointer">
                            <button class="bg-[#e2e2e2] text-gray-600 text-[10px] px-1.5 py-0.5 rounded border border-gray-300 flex items-center gap-1 hover:bg-gray-300">
                                Hành động <i class="fa-solid fa-chevron-down text-[7px]"></i>
                            </button>
                            <div class="absolute right-0 top-full mt-0.5 w-44 bg-white border border-gray-200 shadow-xl rounded z-50 hidden group-hover:block text-left py-1 text-[#2A3F54]">
                                <a href="javascript:void(0)" onclick="toggleCourseStatus(${c.id}, ${c.status})" class="block px-3 py-1.5 hover:bg-gray-100 font-bold ${c.status == 1 ? 'text-[#D9534F]' : 'text-[#1ABB9C]'}">
                                    <i class="fa-solid ${c.status == 1 ? 'fa-ban' : 'fa-check'} w-4"></i> ${c.status == 1 ? 'Bỏ duyệt' : 'Duyệt khóa học'}
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="admin_lessons.php?course_id=${c.id}" class="block px-3 py-1.5 hover:bg-gray-100 transition">Bài giảng</a>
                                <a href="admin_students.php?course_id=${c.id}" class="block px-3 py-1.5 text-gray-700 hover:bg-gray-100 transition">Danh sách học viên</a>
                                <a href="#" class="block px-3 py-1.5 hover:bg-gray-100 transition">Sao chép khóa học</a>
                                <a href="#" class="block px-3 py-1.5 hover:bg-gray-100 transition">Quản lý bình luận</a>
                                <a href="#" class="block px-3 py-1.5 hover:bg-gray-100 transition">Thông báo</a>
                                <a href="#" class="block px-3 py-1.5 hover:bg-gray-100 transition">Cấu hình khóa học</a>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');
        } catch (error) { 
            console.error("Lỗi:", error); 
        }
    }

    async function toggleCourseStatus(id, currentStatus) {
        const newStatus = currentStatus == 1 ? 0 : 1;
        if(confirm(`Xác nhận thay đổi trạng thái duyệt?`)) {
            const res = await fetch(`api/toggle_status.php?id=${id}&status=${newStatus}`);
            const result = await res.json();
            if(result.success) loadAdminCourses();
        }
    }

    async function deleteCourse(id) {
        if(confirm("Hành động này sẽ xóa vĩnh viễn khóa học. Bạn có chắc chắn muốn xóa không?")) {
            try {
                const res = await fetch(`api/delete_course.php?id=${id}`);
                const result = await res.json();
                
                if(result.success) {
                    alert(result.message);
                    loadAdminCourses(); 
                } else {
                    alert("Lỗi: " + result.message);
                }
            } catch (err) {
                alert("Không thể kết nối đến máy chủ để xóa khóa học.");
                console.error(err);
            }
        }
    }

    loadFilterCategories();
    loadAdminCourses();
</script>
 
</body>
</html>