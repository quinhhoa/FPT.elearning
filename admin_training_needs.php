<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Quản lý nhu cầu đào tạo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #73879C; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #1ABB9C; border-radius: 3px; }
    .table-bordered th, .table-bordered td { border: 1px solid #E6E9ED; padding: 6px 8px; vertical-align: middle; }
    .table-bordered th { background-color: #F7F7F7; color: #73879C; font-weight: bold; text-align: center; }
  </style>
</head>
<body class="bg-[#F7F7F7] flex min-h-screen overflow-x-hidden text-[12px]">

  <?php include 'includes/admin_sidebar.php'; ?>

  <div class="flex-1 ml-[200px] flex flex-col min-h-screen w-[calc(100%-200px)]">
    <header class="h-12 bg-white border-b border-[#D9DEE4] flex justify-between items-center px-4 z-40 sticky top-0">
      <div class="flex items-center gap-3 text-[#73879C]">
        <i class="fa-solid fa-bars cursor-pointer text-base hover:text-gray-900"></i>
      </div>
      <div class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded">
        <img src="https://i.pravatar.cc/150?img=11" class="w-7 h-7 rounded-full border border-gray-300">
        <span class="text-xs font-medium text-[#2A3F54]">Quỳnh Hoa</span>
      </div>
    </header>

    <main class="p-4 flex-1 overflow-hidden flex flex-col">
      <div class="bg-white rounded shadow-sm border border-[#E6E9ED] p-3 flex-1 flex flex-col">
        
        <div class="flex justify-between items-end border-b border-[#E6E9ED] pb-3 mb-3">
          <div>
            <h1 class="text-xl font-normal text-[#2A3F54] mb-1">Quản lý nhu cầu đào tạo <small class="text-xs text-gray-400 tracking-wide">Quản lý nhu cầu đào tạo</small></h1>
            <div class="text-[11px] flex items-center gap-1.5 text-gray-500 bg-gray-100 py-1 px-2 rounded inline-flex">
              <i class="fa-solid fa-house"></i> Dashboard <span class="mx-1">/</span> Quản lý nhu cầu đào tạo
            </div>
          </div>
          <div class="flex gap-2">
            <button class="bg-[#d2d6de] text-[#444] px-3 py-1.5 rounded flex items-center gap-1 hover:bg-[#c3c8d1] border border-[#d2d6de] text-[12px] shadow-sm">
              Hành động <i class="fa-solid fa-chevron-down text-[9px] mt-0.5 ml-1"></i>
            </button>
          </div>
        </div>

        <!-- BỘ LỌC TÌM KIẾM ĐÃ ĐƯỢC GẮN ID -->
        <div class="flex gap-2 mb-3">
          <input type="text" id="search-keyword" placeholder="Tên/Mục tiêu đào tạo..." class="border border-gray-300 px-3 py-1.5 rounded outline-none focus:border-[#1ABB9C] text-[12px] flex-1">
          
          <select id="search-unit" class="border border-gray-300 px-3 py-1.5 rounded outline-none text-gray-600 text-[12px] w-48">
            <option value="">Tất cả đơn vị</option>
            <option value="FSOFT">FSOFT</option>
            <option value="FPT IS">FPT IS</option>
            <option value="FTG">FTG</option>
          </select>
          
          <div class="relative w-32">
              <input type="text" placeholder="2026" class="border border-gray-300 px-3 py-1.5 rounded outline-none focus:border-[#1ABB9C] text-[12px] w-full bg-gray-50">
              <i class="fa-regular fa-calendar absolute right-3 top-2 text-gray-400"></i>
          </div>
          
          <select id="search-status" class="border border-gray-300 px-3 py-1.5 rounded outline-none text-gray-600 text-[12px] w-40">
            <option value="">Tất cả trạng thái</option>
            <option value="Đã gửi">Đã gửi (Chờ duyệt)</option>
            <option value="Đã duyệt NCĐT">Đã duyệt NCĐT</option>
            <option value="Bỏ duyệt">Bỏ duyệt</option>
          </select>
          
          <button onclick="searchAdminNeeds()" class="bg-[#5CB85C] text-white px-4 py-1.5 rounded flex items-center gap-1.5 hover:bg-[#4cae4c] border border-[#4cae4c] text-[12px] font-medium shadow-sm transition">
            <i class="fa-solid fa-search"></i> Tìm kiếm
          </button>
        </div>

        <div class="overflow-x-auto border border-gray-200 flex-1"> 
          <table class="w-full text-left table-bordered text-[11px] whitespace-nowrap min-w-[1200px]">
            <thead>
              <tr>
                <th class="w-8"><input type="checkbox" class="accent-[#1ABB9C]"></th>
                <th class="w-32">Tên mục tiêu<br>đào tạo</th>
                <th class="w-20">Thời lượng<br>đào tạo(giờ)</th>
                <th class="w-20">Số lượng<br>học viên<br>dự kiến</th>
                <th class="w-56">Đơn vị đào tạo</th>
                <th class="w-32">Giảng viên</th>
                <th class="w-24">Phòng ban</th>
                <th class="w-24">Người đề<br>xuất</th>
                <th class="w-24">Người quản<br>lý trực tiếp</th>
                <th class="w-20">Thời<br>điểm<br>đào tạo</th>
                <th class="w-24">Trạng thái</th>
                <th class="w-36">Hành động</th>
              </tr>
            </thead>
            <tbody id="training-needs-list" class="text-[#73879C]">
              <tr><td colspan="12" class="text-center py-4 italic text-gray-500">Đang tải dữ liệu...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
  
<script>
    // Hàm gọi dữ liệu có kèm các tham số tìm kiếm
    async function fetchTrainingNeeds(keyword = '', status = '', unit = '') {
        try {
            // Tạo chuỗi query string
            const queryParams = new URLSearchParams({
                view: 'admin',
                search: keyword,
                status: status,
                unit: unit
            }).toString();

            const res = await fetch(`api/get_training_needs.php?${queryParams}`);
            const data = await res.json();
            const tbody = document.getElementById('training-needs-list');
            
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="12" class="text-center text-red-500 py-4">${data.message}</td></tr>`;
                return;
            }
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="12" class="text-center text-gray-500 py-6">Không tìm thấy dữ liệu phù hợp.</td></tr>`;
                return;
            }

            let html = '';
            data.forEach(item => {
                let actionButtons = '';
                
                // Chỉ hiện nút Duyệt/Bỏ duyệt nếu đơn đang ở trạng thái "Đã gửi"
                if (item.status === 'Đã gửi') {
                    actionButtons = `
                        <button onclick="location.href='admin_approve_need.php?id=${item.id}'" class="bg-[#337AB7] text-white px-2.5 py-1 rounded hover:bg-[#286090] mx-0.5 transition shadow-sm">Duyệt</button>
                        <button onclick="rejectNeed(${item.id})" class="bg-[#D9534F] text-white px-2.5 py-1 rounded hover:bg-[#c9302c] mx-0.5 transition shadow-sm">Bỏ duyệt</button>
                    `;
                } else {
                    actionButtons = `<span class="text-gray-400 italic text-[11px]">Đã xử lý</span>`;
                }

                // Đổi màu text trạng thái cho dễ nhìn
                let statusColor = 'text-[#2A3F54]';
                if (item.status === 'Đã duyệt NCĐT') statusColor = 'text-green-600';
                if (item.status === 'Bỏ duyệt') statusColor = 'text-red-500';

                html += `
                    <tr class="hover:bg-[#f5f7fa] transition-colors">
                        <td class="text-center"><input type="checkbox" class="accent-[#1ABB9C]"></td>
                        <td>${item.name || ''}</td>
                        <td class="text-center font-medium">${item.duration || '0,00'}</td>
                        <td class="text-center">${item.students || 0}</td>
                        <td>${item.unit || ''}</td>
                        <td>${item.teacher || ''}</td>
                        <td>${item.department || ''}</td>
                        <td>${item.proposer || ''}</td>
                        <td>${item.manager || ''}</td>
                        <td class="text-center">${item.time || ''}</td>
                        <td class="text-center font-medium ${statusColor}">${item.status}</td>
                        <td class="text-center">${actionButtons}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } catch (err) { console.error(err); }
    }

    // Hàm được gọi khi bấm nút "Tìm kiếm"
    function searchAdminNeeds() {
        const keyword = document.getElementById('search-keyword').value;
        const status = document.getElementById('search-status').value;
        const unit = document.getElementById('search-unit').value;
        
        fetchTrainingNeeds(keyword, status, unit);
    }

    async function rejectNeed(id) {
        if (confirm("Xác nhận: Bạn muốn BỎ DUYỆT nhu cầu đào tạo này?")) {
            try {
                const res = await fetch(`api/update_need_status.php?id=${id}&status=Bỏ duyệt`);
                const result = await res.json();
                if(result.success) {
                    searchAdminNeeds(); // Tải lại bảng theo filter hiện tại
                } else {
                    alert("Lỗi: " + result.message);
                }
            } catch (err) { alert("Lỗi kết nối mạng!"); }
        }
    }

    // Load dữ liệu lần đầu khi vào trang
    document.addEventListener("DOMContentLoaded", () => fetchTrainingNeeds());
</script>
</body>
</html>