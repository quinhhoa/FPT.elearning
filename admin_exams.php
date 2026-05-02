<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Quản lý bài thi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #444; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #1ABB9C; border-radius: 3px; }
    
    .table-bordered th, .table-bordered td { border: 1px solid #E6E9ED; padding: 8px 10px; vertical-align: middle; }
    .table-bordered th { background-color: #F7F7F7; color: #333; font-weight: bold; text-align: left; }
    
    /* Input style riêng cho form tìm kiếm màn này */
    .filter-input {
        width: 100%; border: 1px solid #d2d6de; padding: 6px 12px; font-size: 13px; outline: none; color: #555;
    }
    .filter-input:focus { border-color: #3c8dbc; }
  </style>
</head>
<body class="bg-[#ecf0f5] flex min-h-screen overflow-x-hidden text-[13px]">

  <!-- SIDEBAR -->
  <?php include 'includes/admin_sidebar.php'; ?>

  <div class="flex-1 ml-[200px] flex flex-col min-h-screen w-[calc(100%-200px)]">
    
    <!-- HEADER -->
    <header class="h-12 bg-white border-b border-gray-200 flex justify-between items-center px-4 z-40 sticky top-0">
      <div class="flex items-center gap-3 text-gray-600">
        <i class="fa-solid fa-bars cursor-pointer hover:text-gray-900"></i>
      </div>
      <div class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded">
        <img src="https://ui-avatars.com/api/?name=Admin&background=115293&color=fff" class="w-7 h-7 rounded-full border border-gray-300">
        <span class="text-xs font-medium text-gray-700">Quỳnh Hoa</span>
      </div>
    </header>

    <main class="p-4 flex-1 flex flex-col">
      <!-- Title & Breadcrumb -->
      <div class="flex justify-between items-end mb-4">
        <div>
          <h1 class="text-[22px] font-normal text-[#333] mb-1">Bài thi <small class="text-[14px] text-gray-500 font-light">Quản lý bài thi</small></h1>
          <div class="text-[12px] flex items-center gap-1.5 text-[#777] bg-[#f9f9f9] py-1 px-2.5 rounded inline-flex border border-gray-100">
            <i class="fa-solid fa-house"></i> Dashboard <span class="text-gray-300 mx-0.5">/</span> Quản lý bài thi
          </div>
        </div>
        <div class="flex gap-1.5">
          <button class="bg-[#5CB85C] text-white px-3 py-1.5 rounded text-[13px] hover:bg-[#4cae4c] transition flex items-center gap-1 border border-[#4cae4c] shadow-sm">
            <i class="fa-solid fa-plus"></i> Thêm mới
          </button>
          <button class="bg-[#d2d6de] text-[#444] px-3 py-1.5 rounded text-[13px] hover:bg-[#c3c8d1] transition flex items-center gap-1 border border-[#d2d6de] shadow-sm">
            Hành động <i class="fa-solid fa-chevron-down text-[10px] mt-0.5"></i>
          </button>
        </div>
      </div>

      <div class="bg-white border-t-[3px] border-[#3c8dbc] shadow-sm flex-1 flex flex-col">
        
        <!-- BỘ LỌC TÌM KIẾM -->
        <div class="p-3 border-b border-gray-100 bg-white">
            <div class="grid grid-cols-4 gap-3 mb-3">
                <input type="text" id="search-title" placeholder="-- Tên bài thi --" class="filter-input">
                <select class="filter-input"><option>Danh mục: tất cả</option></select>
                <select class="filter-input"><option>Hình thức thi: tất cả</option></select>
                <select class="filter-input"><option>Trạng thái: tất cả</option></select>
            </div>
            <div class="grid grid-cols-4 gap-3">
                <div class="flex">
                    <input type="text" placeholder="Từ ngày?" class="filter-input border-r-0 rounded-l" onfocus="(this.type='date')" onblur="(this.type='text')">
                    <span class="bg-gray-100 border border-gray-300 border-l-0 px-3 py-1.5 flex items-center text-gray-500"><i class="fa-regular fa-calendar"></i></span>
                </div>
                <div class="flex">
                    <input type="text" placeholder="Đến ngày?" class="filter-input border-r-0 rounded-l" onfocus="(this.type='date')" onblur="(this.type='text')">
                    <span class="bg-gray-100 border border-gray-300 border-l-0 px-3 py-1.5 flex items-center text-gray-500"><i class="fa-regular fa-calendar"></i></span>
                </div>
                <div>
                    <button onclick="searchExams()" class="bg-[#5CB85C] text-white px-4 py-1.5 rounded text-[13px] hover:bg-[#4cae4c] transition flex items-center gap-1.5 border border-[#4cae4c] shadow-sm h-full">
                        <i class="fa-solid fa-search"></i> Tìm kiếm
                    </button>
                </div>
                <div></div>
            </div>
        </div>

        <!-- BẢNG DỮ LIỆU -->
        <div class="overflow-x-auto p-3"> 
          <table class="w-full text-left table-bordered text-[13px] whitespace-nowrap min-w-[1000px]">
            <thead>
              <tr>
                <th class="w-8 text-center"><input type="checkbox"></th>
                <th class="w-12 text-center">STT</th>
                <th>Tên bài thi</th>
                <th class="w-40">Thời gian bắt đầu</th>
                <th class="w-40">Thời gian kết thúc</th>
                <th class="w-48">Loại hình thi</th>
                <th class="w-20 text-center">Trạng thái</th>
                <th class="w-40">Hành động</th>
              </tr>
            </thead>
            <tbody id="exams-list" class="text-gray-700">
              <tr><td colspan="8" class="text-center py-6 text-gray-500 italic">Đang tải dữ liệu...</td></tr>
            </tbody>
          </table>
          <div class="mt-3 text-[#777] text-[13px]" id="record-count">
              Hiển thị 0 - 0 / 0 bản ghi
          </div>
        </div>

      </div>
    </main>
  </div>
  
<script>
    async function loadExams(keyword = '') {
        try {
            const res = await fetch(`api/get_exams.php?search=${encodeURIComponent(keyword)}`);
            const data = await res.json();
            const tbody = document.getElementById('exams-list');
            
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500 py-4">${data.message}</td></tr>`;
                return;
            }
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-gray-500 py-4">Không tìm thấy bài thi.</td></tr>`;
                document.getElementById('record-count').innerText = `Hiển thị 0 - 0 / 0 bản ghi`;
                return;
            }

            let html = '';
            data.forEach((item, index) => {
                // Biểu tượng link ngoài cho Loại hình "Bài thi cho khóa học"
                let examTypeHtml = item.exam_type;
                if (item.exam_type === 'Bài thi cho khóa học') {
                    examTypeHtml += ` <i class="fa-solid fa-arrow-up-right-from-square text-[#337AB7] text-[10px] ml-1"></i>`;
                }

                // Checkbox trạng thái
                let statusIcon = item.status == 1 ? '<i class="fa-regular fa-square-check text-gray-700 text-base"></i>' : '<i class="fa-regular fa-square text-gray-400 text-base"></i>';

                // Nút hành động
                let actionBtns = `
                    <button class="bg-[#337AB7] text-white px-2 py-0.5 text-[12px] rounded hover:opacity-90 transition">Sửa</button>
                    <button class="bg-[#D9534F] text-white px-2 py-0.5 text-[12px] rounded hover:opacity-90 transition ml-0.5">Xóa</button>
                    <button class="bg-[#d2d6de] text-[#444] px-2 py-0.5 text-[12px] rounded hover:bg-[#c3c8d1] transition ml-0.5">Khác <i class="fa-solid fa-chevron-down text-[8px]"></i></button>
                `;

                html += `
                    <tr class="hover:bg-[#f5f5f5] transition-colors">
                        <td class="text-center"><input type="checkbox"></td>
                        <td class="text-center">${index + 1}</td>
                        <td class="text-[#337AB7] cursor-pointer hover:underline">${item.title}</td>
                        <td>${item.start_time_formatted}</td>
                        <td>${item.end_time_formatted}</td>
                        <td>${examTypeHtml}</td>
                        <td class="text-center">${statusIcon}</td>
                        <td>${actionBtns}</td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
            document.getElementById('record-count').innerText = `Hiển thị 1 - ${data.length} / ${data.length} bản ghi`;

        } catch (err) { console.error(err); }
    }

    function searchExams() {
        const keyword = document.getElementById('search-title').value;
        loadExams(keyword);
    }

    // Tự động tải dữ liệu khi mở trang
    document.addEventListener("DOMContentLoaded", () => loadExams());
</script>
</body>
</html>