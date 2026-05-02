<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$fullname = $_SESSION['fullname'] ?? 'Học viên';
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($fullname) . "&background=115293&color=fff";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Cập nhật nhu cầu đào tạo - ELEARNING</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-size: 12px; color: #333; margin-bottom: 5px; font-weight: 500; }
    .form-input { border: 1px solid #d1d5db; padding: 8px 12px; font-size: 13px; outline: none; border-radius: 2px; width: 100%; background-color: white; }
    .form-input:focus { border-color: #337AB7; }
    .required { color: #D9534F; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-white h-14 flex justify-between items-center px-6 border-b z-40 shadow-sm relative">
    <div class="flex items-center gap-3">
        <div class="flex skew-x-[-15deg] cursor-pointer" onclick="location.href='index.php'">
            <span class="bg-[#175aa3] text-white font-bold text-[20px] px-2 py-0.5 leading-none">F</span>
            <span class="bg-[#f26f21] text-white font-bold text-[20px] px-2 py-0.5 leading-none">P</span>
            <span class="bg-[#00a950] text-white font-bold text-[20px] px-2 py-0.5 leading-none">T</span>
        </div>
        <div class="flex flex-col justify-center border-l border-gray-300 pl-4">
            <p class="text-[11px] text-gray-500 leading-tight">Hệ thống đào tạo trực tuyến</p>
            <p class="font-bold text-[#115293] text-[14px] leading-tight">ELEARNING</p>
        </div>
    </div>
    <div class="flex items-center gap-6">
        <img src="<?= $avatar_url ?>" class="rounded-full w-8 h-8 border border-gray-200 shadow-sm cursor-pointer hover:opacity-80 transition"/>
    </div>
</header>

<div class="p-6 w-full max-w-[900px] mx-auto mt-4">
  
  <main class="bg-white border border-gray-200 shadow-sm rounded-sm">
      <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
          <div class="flex items-center gap-2">
              <i class="fa-solid fa-folder-plus text-[#337AB7]"></i>
              <h2 class="font-bold text-[#115293] text-[14px] uppercase" id="form-title">THÊM MỚI NHU CẦU ĐÀO TẠO</h2>
          </div>
          <button type="button" onclick="location.href='training_needs.php'" class="text-gray-500 hover:text-gray-800 text-[12px] flex items-center gap-1">
              <i class="fa-solid fa-reply"></i> Quay lại
          </button>
      </div>

      <div class="p-8">
          <form id="need-form" action="api/save_student_need.php" method="POST">
              <input type="hidden" name="id" id="need-id">
              
              <div class="form-group">
                  <label class="form-label">Mục tiêu đào tạo (<span class="required">*</span>)</label>
                  <textarea name="target" id="need-target" rows="3" required class="form-input"></textarea>
              </div>

              <div class="form-group">
                  <label class="form-label">Nhu cầu đào tạo (<span class="required">*</span>)</label>
                  <div class="flex items-center gap-3">
                      <input type="text" name="name" id="need-name" placeholder="Nhập nhu cầu đào tạo/Khóa học" required class="form-input flex-1">
                      <span class="text-[11px] text-gray-400 w-64 italic">(Bạn có thể tự nhập khóa học ngoài danh sách)</span>
                  </div>
              </div>

              <div class="form-group">
                  <label class="form-label">Mô tả chi tiết</label>
                  <textarea name="note" id="need-note" rows="3" class="form-input"></textarea>
              </div>

              <div class="form-group">
                  <label class="form-label">Đơn vị đào tạo (<span class="required">*</span>)</label>
                  <div class="flex items-center gap-3">
                      <input type="text" name="unit" id="need-unit" placeholder="Chọn hoặc nhập đơn vị đào tạo" required class="form-input flex-1">
                      <span class="text-[11px] text-gray-400 w-64 italic">(Bạn có thể tự nhập đơn vị ngoài danh sách)</span>
                  </div>
              </div>

              <div class="form-group">
                  <label class="form-label">Giảng viên (<span class="required">*</span>)</label>
                  <div class="flex items-center gap-3">
                      <input type="text" name="teacher" id="need-teacher" placeholder="Chọn hoặc nhập giảng viên" required class="form-input flex-1">
                      <span class="text-[11px] text-gray-400 w-64 italic">(Bạn có thể tự nhập giảng viên ngoài danh sách)</span>
                  </div>
              </div>

              <div class="form-group">
                  <label class="form-label">Thời gian dự kiến (<span class="required">*</span>)</label>
                  <div class="flex items-center gap-4 w-2/3">
                      <input type="date" name="start_date" id="need-start" required class="form-input flex-1 text-gray-500">
                      <input type="date" name="end_date" id="need-end" required class="form-input flex-1 text-gray-500">
                  </div>
              </div>

              <div class="form-group">
                  <label class="form-label">Năng lực (<span class="required">*</span>)</label>
                  <select name="competency" id="need-comp" required class="form-input w-2/3 text-gray-600">
                      <option value="">Chọn năng lực</option>
                      <option value="Kiến thức chuyên môn">Kiến thức chuyên môn</option>
                      <option value="Kỹ năng mềm">Kỹ năng mềm</option>
                      <option value="Quy trình quy định">Quy trình quy định</option>
                      <option value="Ngoại ngữ">Ngoại ngữ</option>
                      <option value="Năng lực quản lý">Năng lực quản lý</option>
                      <option value="Khác">Khác</option>
                  </select>
              </div>

              <div class="mt-8 border-t border-gray-200 pt-4">
                  <button type="submit" class="bg-[#5CB85C] text-white px-6 py-2 rounded-sm shadow-sm hover:bg-[#4cae4c] transition flex items-center gap-2 font-medium text-[13px]">
                      <i class="fa-solid fa-check"></i> Lưu
                  </button>
              </div>
          </form>
      </div>
  </main>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const needId = urlParams.get('id');

    if (needId) {
        document.getElementById('form-title').innerText = "CẬP NHẬT NHU CẦU ĐÀO TẠO";
        document.getElementById('need-id').value = needId;
        
        fetch('api/get_training_needs.php')
            .then(res => res.json())
            .then(data => {
                const record = data.find(item => item.id == needId);
                if(record) {
                    document.getElementById('need-name').value = record.name || '';
                    document.getElementById('need-target').value = record.target || ''; // ĐÃ SỬA: Nạp dữ liệu mục tiêu
                    document.getElementById('need-note').value = record.note || '';
                    document.getElementById('need-unit').value = record.unit || '';
                    document.getElementById('need-teacher').value = record.teacher || '';
                    document.getElementById('need-comp').value = record.competency || '';
                    
                    if(record.time && record.time.includes(' - ')) {
                        let dates = record.time.split(' - ');
                        document.getElementById('need-start').value = dates[0].split('/').reverse().join('-');
                        document.getElementById('need-end').value = dates[1].split('/').reverse().join('-');
                    }
                }
            });
    }
</script>
</body>
</html>