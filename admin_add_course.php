<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Thêm mới khóa học</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif; color: #73879C; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: #172D44; border-radius: 2px; }
    
    .form-group {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        align-items: center;
        border-bottom: 1px solid #f3f3f3;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    .form-group:last-child { border-bottom: none; }
    .form-label {
        grid-column: span 3 / span 3;
        text-align: right;
        font-size: 12px;
        color: #4b5563;
    }
    .form-control {
        grid-column: span 9 / span 9;
    }
    .required:after { content: " *"; color: red; }
    
    /* Style cho Tab Active/Inactive */
    .tab-btn { transition: all 0.2s; cursor: pointer; }
    .tab-active { background-color: white; border-top: 2px solid #337AB7; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; color: #2A3F54; font-weight: bold; margin-bottom: -1px; }
    .tab-inactive { color: #6b7280; border-top: 2px solid transparent; border-left: 1px solid transparent; border-right: 1px solid transparent; }
    .tab-inactive:hover { color: #337AB7; }
  </style>
</head>
<body class="bg-[#F7F7F7] flex min-h-screen overflow-x-hidden text-[12px]">

  <?php include 'includes/admin_sidebar.php'; ?>

  <div class="flex-1 ml-[200px] flex flex-col min-h-screen">
    
    <header class="h-12 bg-white border-b border-[#D9DEE4] flex justify-between items-center px-4 z-40 sticky top-0">
      <div class="flex items-center gap-3 text-[#73879C]">
        <i class="fa-solid fa-bars cursor-pointer text-base hover:text-gray-900"></i>
        <span class="font-medium">Trang chủ</span>
      </div>
      <div class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded">
        <img src="https://i.pravatar.cc/150?img=11" class="w-7 h-7 rounded-full border border-gray-300">
        <span class="text-xs font-medium">Quỳnh Hoa</span>
        <i class="fa-solid fa-chevron-down text-[9px]"></i>
      </div>
    </header>

    <main class="p-4 flex-1">
      
      <div class="mb-4">
        <div class="flex justify-between items-center mb-1">
            <h1 class="text-lg font-normal text-[#2A3F54]">Khóa học <small class="text-xs text-gray-400 tracking-wide">Cập nhật khóa học</small></h1>
            <button onclick="location.href='admin.php'" class="bg-[#e2e2e2] text-gray-600 px-3 py-1.5 rounded flex items-center gap-1.5 border border-gray-300 text-[11px] hover:bg-gray-300 transition">
              <i class="fa-solid fa-reply"></i> Quay lại
            </button>
        </div>
        <div class="text-[11px] flex items-center gap-1.5 text-gray-500 bg-gray-100 py-0.5 px-2 rounded inline-flex">
            <i class="fa-solid fa-house"></i> Dashboard / Quản lý danh sách khóa học / Cập nhật khóa học
        </div>
      </div>

      <div class="bg-white rounded shadow-sm border border-[#E6E9ED]">
        
        <div class="px-4 py-3 border-b border-[#E6E9ED] flex items-center gap-2 text-[#2A3F54] font-bold text-[13px]">
            <i class="fa-solid fa-gift"></i> Cập nhật khóa học
        </div>

        <div class="flex border-b border-gray-200 px-4 pt-2 bg-gray-50">
            <button type="button" onclick="switchTab('tab-chung', this)" class="tab-btn tab-active px-4 py-2 text-[12px]">Thông tin chung</button>
            <button type="button" onclick="switchTab('tab-dkthamgia', this)" class="tab-btn tab-inactive px-4 py-2 text-[12px]">Điều kiện tham gia khóa học</button>
            <button type="button" onclick="switchTab('tab-dkhoanthanh', this)" class="tab-btn tab-inactive px-4 py-2 text-[12px]">Điều kiện hoàn thành khóa học</button>
        </div>

        <div class="p-6">
            <form action="api/add_course.php" method="POST">
                
                <div id="tab-chung" class="max-w-4xl block">
                    <div class="form-group">
                        <label class="form-label required">Danh mục khóa học:</label>
                        <div class="form-control">
                            <select name="category_id" id="course-category" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600" required>
                                <option value="">--- Chọn danh mục ---</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Loại khóa học:</label>
                        <div class="form-control">
                            <select name="course_type" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                                <option>Online</option>
                                <option>Offline</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Hình thức đăng ký:</label>
                        <div class="form-control">
                            <select name="registration_type" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                                <option value="Không cho đăng ký">Không cho đăng ký</option>
                                <option value="Kiểm duyệt">Kiểm duyệt</option>
                                <option value="Đăng ký tự do">Đăng ký tự do</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Mã khóa học:</label>
                        <div class="form-control">
                            <input type="text" name="course_code" value="Course-<?php echo date('d-m-Y-H-i-s'); ?>" required class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600 rounded-sm">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Tên khóa học:</label>
                        <div class="form-control">
                            <input type="text" name="title" placeholder="Tên khóa học" required class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                        </div>
                    </div>

                    <div class="form-group items-start">
                        <label class="form-label mt-2">Thông tin mô tả:</label>
                        <div class="form-control border border-gray-300">
                            <div class="bg-[#f0f0ee] border-b border-gray-300 p-1 flex flex-wrap gap-1">
                                <div class="flex gap-0.5 border-r border-gray-300 pr-1">
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent"><i class="fa-solid fa-cut text-gray-600"></i></button>
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent"><i class="fa-solid fa-copy text-gray-600"></i></button>
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent"><i class="fa-solid fa-paste text-gray-600"></i></button>
                                </div>
                                <div class="flex gap-0.5 border-r border-gray-300 pr-1">
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent font-bold font-serif">B</button>
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent italic font-serif">I</button>
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent underline font-serif">U</button>
                                    <button type="button" class="px-2 py-0.5 hover:bg-gray-200 border border-transparent line-through font-serif">S</button>
                                </div>
                            </div>
                            <textarea name="description" rows="5" class="w-full p-3 outline-none text-gray-600 resize-y" placeholder="Nhập mô tả khóa học..."></textarea>
                        </div>
                    </div>

                    <div class="form-group items-start">
                        <label class="form-label mt-2">Ảnh đại diện (Link):</label>
                        <div class="form-control flex gap-4">
                            <div class="w-48 h-32 border border-gray-300 bg-white p-1 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image text-4xl"></i>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="text" name="thumbnail_url" placeholder="Dán link ảnh (https://...)" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                                <div class="flex gap-2">
                                    <button type="button" class="bg-[#1ABB9C] text-white px-3 py-1.5 rounded flex items-center gap-1 text-[11px] hover:bg-[#159a80]">
                                        <i class="fa-solid fa-plus"></i> Chọn ảnh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group items-start">
                        <label class="form-label mt-2">Video giới thiệu:</label>
                        <div class="form-control flex gap-4">
                            <div class="w-64 h-36 bg-[#222] relative flex items-center justify-center">
                                <div class="absolute bottom-2 left-2 text-white text-xs flex gap-2 items-center">
                                    <i class="fa-solid fa-play"></i> 0:00
                                </div>
                            </div>
                            <div class="flex gap-2 items-start mt-2">
                                <button type="button" class="bg-[#1ABB9C] text-white px-3 py-1.5 rounded flex items-center gap-1 text-[11px] hover:bg-[#159a80]">
                                    <i class="fa-solid fa-plus"></i> Chọn video
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số lượng học viên:</label>
                        <div class="form-control flex items-center gap-4">
                            <input type="number" name="students" value="0" class="w-32 border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                            <span class="text-gray-500">Note *0: Không giới hạn số lượng học viên</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Chi phí giáo viên:</label>
                        <div class="form-control flex items-center gap-3">
                            <input type="text" name="teacher_cost" class="w-64 border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                            <span class="text-gray-500">VNĐ</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Chi phí tổ chức:</label>
                        <div class="form-control flex items-center gap-3">
                            <input type="text" name="org_cost" class="w-64 border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                            <span class="text-gray-500">VNĐ</span>
                        </div>
                    </div>
                    <!-- KHỐI THỜI GIAN HỌC -->
<div class="form-group items-start">
    <label class="form-label mt-1 required">Thời gian học:</label>
    <div class="form-control">
        <div class="flex gap-6 mb-3">
            <label class="flex items-center gap-1.5 text-[12px] text-gray-600 cursor-pointer">
                <input type="radio" name="time_type" id="time_type_days" value="days" checked onchange="toggleTimeInput()" class="accent-[#1ABB9C]"> 
                Số ngày học (tính từ lúc tham gia)
            </label>
            <label class="flex items-center gap-1.5 text-[12px] text-gray-600 cursor-pointer">
                <input type="radio" name="time_type" id="time_type_range" value="range" onchange="toggleTimeInput()" class="accent-[#1ABB9C]"> 
                Khoảng thời gian cố định
            </label>
        </div>

        <!-- Khối 1: Số ngày học -->
        <div id="input-days" class="w-full transition-all block">
            <div class="flex items-center gap-2">
                <input type="number" name="duration_days" id="course-duration" min="1" placeholder="VD: 30" class="border border-gray-300 px-3 py-1.5 w-[200px] outline-none focus:border-[#337AB7] rounded-sm text-xs text-gray-600">
                <span class="text-[12px] text-gray-500 italic">ngày</span>
            </div>
        </div>

        <!-- Khối 2: Khoảng thời gian -->
        <div id="input-range" class="hidden w-full transition-all">
            <div class="flex items-center gap-4 w-[450px]">
                <input type="date" name="start_date" id="course-start" class="border border-gray-300 px-3 py-1.5 outline-none focus:border-[#337AB7] rounded-sm text-xs flex-1 text-gray-600">
                <span class="text-gray-400"><i class="fa-solid fa-arrow-right"></i></span>
                <input type="date" name="end_date" id="course-end" class="border border-gray-300 px-3 py-1.5 outline-none focus:border-[#337AB7] rounded-sm text-xs flex-1 text-gray-600">
            </div>
        </div>
    </div>
</div>


                   
                    <div class="form-group">
                        <label class="form-label">Duyệt khóa học:</label>
                        <div class="form-control">
                            <input type="checkbox" name="status" id="course-status" value="1" checked class="mt-1 accent-[#1ABB9C] cursor-pointer"> 
                        </div>
                    </div>
                </div>

                <div id="tab-dkthamgia" class="max-w-4xl hidden">
                    <div class="form-group">
                        <label class="form-label">Qua bài thi :</label>
                        <div class="form-control">
                            <select name="condition_exam_id" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                                <option>Chọn bài thi.....</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hoàn thành khóa học :</label>
                        <div class="form-control">
                            <select name="condition_course_id" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                                <option>Chọn khóa học.....</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hoàn thành bài khảo sát :</label>
                        <div class="form-control">
                            <select name="condition_survey_id" class="w-full border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                                <option>Chọn khảo sát.....</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="tab-dkhoanthanh" class="max-w-4xl hidden">
                    <div class="form-group border-b-0">
                        <label class="form-label">Thời gian hoàn thành :</label>
                        <div class="form-control flex items-center gap-3">
                            <input type="number" name="required_learning_time" value="0" class="w-32 border border-gray-300 px-3 py-1.5 focus:border-[#337AB7] outline-none text-gray-600">
                            <span class="text-gray-500">(Phút)</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-4 flex gap-2">
                    <div class="w-3/12"></div>
                    <div class="w-9/12 flex gap-2">
                        <button type="submit" class="bg-[#1ABB9C] text-white px-5 py-1.5 rounded flex items-center gap-1.5 hover:bg-[#159a80] border border-[#169F85]">
                            <i class="fa-solid fa-check"></i> Lưu
                        </button>
                        <button type="button" onclick="location.href='admin.php'" class="bg-[#e2e2e2] text-gray-600 px-5 py-1.5 rounded border border-gray-300 hover:bg-gray-300 transition">
                            Hủy bỏ
                        </button>
                    </div>
                </div>

            </form>
        </div>

      </div>
    </main>
  </div>

  <script>
    async function loadCategoriesForForm() {
        try {
            const res = await fetch('api/get_categories.php');
            const categories = await res.json();
            const select = document.getElementById("course-category");
            categories.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });
        } catch (error) { console.error("Chưa có dữ liệu danh mục"); }
    }
    loadCategoriesForForm();
    
    function switchTab(tabId, btnElement) {
        document.getElementById('tab-chung').classList.add('hidden');
        document.getElementById('tab-dkthamgia').classList.add('hidden');
        document.getElementById('tab-dkhoanthanh').classList.add('hidden');
        
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.classList.remove('tab-active');
            tab.classList.add('tab-inactive');
        });

        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('block');
        
        btnElement.classList.remove('tab-inactive');
        btnElement.classList.add('tab-active');
    }
    function toggleTimeInput() {
    // Lấy giá trị của radio button đang được chọn
    const selectedType = document.querySelector('input[name="time_type"]:checked').value;
    
    // Lấy 2 khối HTML
    const inputDays = document.getElementById('input-days');
    const inputRange = document.getElementById('input-range');

    // Chuyển đổi trạng thái hiển thị
    if (selectedType === 'days') {
        inputDays.classList.remove('hidden');
        inputRange.classList.add('hidden');
        
        // Reset lại giá trị ngày nếu người dùng đổi ý
        document.querySelector('input[name="start_date"]').value = '';
        document.querySelector('input[name="end_date"]').value = '';
    } else {
        inputDays.classList.add('hidden');
        inputRange.classList.remove('hidden');
        
        // Reset lại ô số ngày nếu người dùng đổi ý
        document.querySelector('input[name="duration_days"]').value = '';
    }
}
  </script>
</body>
</html>