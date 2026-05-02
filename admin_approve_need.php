<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Duyệt nhu cầu đào tạo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #444; }
    .form-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        border-bottom: 1px solid #f3f3f3;
        padding: 16px 16px;
        align-items: start;
    }
    .form-label {
        text-align: right;
        padding-right: 20px;
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }
    .form-value {
        font-size: 13px;
        color: #333;
    }
  </style>
</head>
<body class="bg-white flex min-h-screen text-[13px]">

  <?php include 'includes/admin_sidebar.php'; ?>

  <div class="flex-1 ml-[200px] flex flex-col min-h-screen">
    
    <main class="p-6">
      <div class="mb-4">
        <h1 class="text-[22px] font-normal text-[#333] mb-2 flex items-end gap-2">
            Quản lý nhu cầu đào tạo 
            <span class="text-[14px] text-gray-500 mb-[2px]">Duyệt nhu cầu đào tạo</span>
        </h1>
        <div class="text-[12px] flex items-center gap-2 text-[#777] bg-[#f9f9f9] py-1.5 px-3 rounded inline-flex">
            <i class="fa-solid fa-house"></i> Dashboard <span class="text-gray-300">/</span> 
            <a href="admin_training_needs.php" class="hover:text-[#337AB7]">Danh sách nhu cầu đào tạo</a> <span class="text-gray-300">/</span> 
            <span class="text-gray-500">Duyệt nhu cầu đào tạo</span>
        </div>
      </div>

      <div class="h-[2px] bg-[#3c8dbc] w-full mb-6 mt-2"></div>

      <!-- Form hiển thị thông tin khớp 100% với Học viên -->
      <div class="max-w-[800px] border border-gray-100 shadow-sm rounded-sm pb-6 bg-[#fafafa]">
          <form id="approve-form">
              <input type="hidden" id="need-id" name="id">

              <div class="form-row bg-white">
                  <div class="form-label">Người đề xuất</div>
                  <div class="form-value font-bold text-[#3c8dbc]" id="val-proposer">Đang tải...</div>
              </div>

              <div class="form-row">
                  <div class="form-label">Nhu cầu đào tạo<br><span class="text-[11px] text-gray-400 font-normal">(Tên khóa học)</span></div>
                  <div class="form-value font-bold text-[#2A3F54] mt-1" id="val-course">Đang tải...</div>
              </div>

              <div class="form-row bg-white">
                  <div class="form-label">Đơn vị đào tạo</div>
                  <div class="form-value" id="val-unit">Đang tải...</div>
              </div>

              <div class="form-row">
                  <div class="form-label">Giảng viên</div>
                  <div class="form-value" id="val-teacher">Đang tải...</div>
              </div>

              <div class="form-row bg-white">
                  <div class="form-label">Năng lực</div>
                  <div class="form-value">
                      <span id="val-competency" class="bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-100">Đang tải...</span>
                  </div>
              </div>

              <div class="form-row">
                  <div class="form-label">Ghi chú của học viên</div>
                  <div class="form-value italic text-gray-500" id="val-note">Không có ghi chú</div>
              </div>

              <div class="form-row border-b-0 bg-white">
                  <div class="form-label mt-2">Hành động của Admin</div>
                  <div class="form-value">
                      <div class="flex gap-2 mt-1">
                          <button type="button" onclick="approveNeed()" class="bg-[#5CB85C] text-white px-5 py-2 rounded shadow-sm hover:bg-[#4cae4c] transition flex items-center gap-1.5 font-medium">
                              <i class="fa-solid fa-check"></i> Phê duyệt
                          </button>
                          <button type="button" onclick="rejectNeed()" class="bg-[#D9534F] text-white px-5 py-2 rounded shadow-sm hover:bg-[#c9302c] transition flex items-center gap-1.5 font-medium">
                              <i class="fa-solid fa-xmark"></i> Bỏ duyệt
                          </button>
                      </div>
                  </div>
              </div>
          </form>
      </div>
    </main>
  </div>

  <script>
      const urlParams = new URLSearchParams(window.location.search);
      const needId = urlParams.get('id');

      if (needId) {
          document.getElementById('need-id').value = needId;
          loadNeedDetail(needId);
      } else {
          alert("Không tìm thấy ID!");
          window.location.href = "admin_training_needs.php";
      }

      async function loadNeedDetail(id) {
          try {
              const res = await fetch(`api/get_training_needs.php`);
              const data = await res.json();
              const record = data.find(item => item.id == id);

              if(record) {
                  document.getElementById('val-proposer').innerText = record.proposer || 'Chưa xác định';
                  document.getElementById('val-course').innerText = record.name || 'Không có tên';
                  document.getElementById('val-unit').innerText = record.unit || 'Không xác định';
                  document.getElementById('val-teacher').innerText = record.teacher || 'Không có';
                  document.getElementById('val-competency').innerText = record.competency || 'Chưa phân loại';
                  if(record.note) document.getElementById('val-note').innerText = record.note;
              }
          } catch(e) { console.error(e); }
      }

      async function approveNeed() {
          try {
              const res = await fetch(`api/update_need_status.php?id=${needId}&status=Đã duyệt NCĐT`);
              const result = await res.json();
              if(result.success) {
                  alert("Đã duyệt thành công!");
                  window.location.href = "admin_training_needs.php";
              } else { alert("Lỗi: " + result.message); }
          } catch(e) { alert("Lỗi mạng!"); }
      }

      async function rejectNeed() {
          if(confirm("Bạn có chắc chắn muốn BỎ DUYỆT nhu cầu này không?")) {
              try {
                  const res = await fetch(`api/update_need_status.php?id=${needId}&status=Bỏ duyệt`);
                  const result = await res.json();
                  if(result.success) {
                      alert("Đã bỏ duyệt!");
                      window.location.href = "admin_training_needs.php";
                  } else { alert("Lỗi: " + result.message); }
              } catch(e) { alert("Lỗi mạng!"); }
          }
      }
  </script>
</body>
</html>