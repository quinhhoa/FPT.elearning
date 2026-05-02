<?php
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'video';

$type_labels = [
    'video' => 'VIDEO',
    'audio' => 'AUDIO',
    'embed' => 'DẠNG LINK NHÚNG',
    'scorm' => 'SCORM',
    'document' => 'TÀI LIỆU HỌC',
    'online' => 'LỚP HỌC/ HỘI THẢO ONLINE',
    'test' => 'DẠNG TEST'
];
$label = isset($type_labels[$type]) ? $type_labels[$type] : strtoupper($type);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Thêm bài giảng mới</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <style>
    body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; color: #333; }
    .form-row { display: flex; border-bottom: 1px solid #f3f3f3; padding: 15px 0; align-items: flex-start; }
    .form-label { width: 25%; text-align: right; padding-right: 20px; font-size: 12px; color: #666; padding-top: 5px; }
    .form-input { width: 75%; padding-right: 20px; }
    .required:after { content: " *"; color: #D9534F; }
    /* Chèn thêm style này để đảm bảo màu sắc nút chuẩn FPT */
    .btn-save { background-color: #5cb85c !important; color: white !important; }
    .btn-save:hover { background-color: #4cae4c !important; }
    .btn-back { background-color: #e2e2e2 !important; color: #333 !important; border: 1px solid #ccc !important; }
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
            <i class="fa-solid fa-house mr-1"></i> Dashboard / Giáo trình học tập / Cập nhật bài học
        </div>
      </div>

      <div class="bg-white rounded shadow-sm border border-[#E6E9ED]">
        <div class="px-4 py-3 border-b border-[#E6E9ED] text-[#D9534F] font-bold text-[13px] flex items-center gap-2">
            <i class="fa-solid fa-gift"></i> THÊM / CẬP NHẬT BÀI GIẢNG <?php echo $label; ?>
        </div>

        <form action="api/add_lesson.php" method="POST" enctype="multipart/form-data" class="pb-6" id="lessonForm">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
            <input type="hidden" name="lesson_type" value="<?php echo $type; ?>">

            <div class="form-row">
                <div class="form-label required">Tên bài học:</div>
                <div class="form-input">
                    <input type="text" name="title" required class="w-full border border-gray-300 px-3 py-1.5 outline-none focus:border-[#1ABB9C]">
                </div>
            </div>
            
            <?php if (in_array($type, ['video', 'audio', 'scorm', 'document'])): ?>
                <div class="form-row">
                    <div class="form-label required">File <?php echo ucfirst($type); ?>:</div>
                    <div class="form-input space-y-2">
                        
                        <input type="text" id="file_display" readonly placeholder="/File/Curriculum/..." class="w-full border border-gray-300 px-3 py-1.5 outline-none bg-gray-50 text-[11px]">
                        
                        <input type="file" name="lesson_file" id="hidden_file_input" class="hidden" required
                            <?php 
                                switch($type) {
                                    case 'video': echo 'accept="video/mp4,video/x-m4v" data-ext="mp4,m4v"'; break;
                                    case 'audio': echo 'accept="audio/mpeg,audio/mp3" data-ext="mp3"'; break;
                                    case 'document': echo 'accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" data-ext="pdf,doc,docx,xls,xlsx,ppt,pptx"'; break;
                                    case 'scorm': echo 'accept=".zip" data-ext="zip"'; break;
                                }
                            ?>
                        >
                        
                        <div class="flex gap-1.5">
                            <button type="button" onclick="document.getElementById('hidden_file_input').click()" class="bg-white text-gray-700 px-3 py-1.5 rounded text-xs border border-gray-400 hover:bg-gray-100 font-medium flex items-center gap-1">
                                <i class="fa-solid fa-plus"></i> Add files...
                            </button>
                        </div>
                        <p id="file_error" class="text-red-500 text-xs hidden">Định dạng file không hợp lệ!</p>
                    </div>
                </div>

                <?php if ($type != 'document'): ?>
                    <div class="form-row">
                        <div class="form-label">Thời lượng:</div>
                        <div class="form-input">
                            <input type="text" name="duration" class="w-32 border border-gray-300 px-3 py-1.5 outline-none bg-yellow-50" placeholder="Phút">
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-row">
                        <div class="form-label">Dung lượng:</div>
                        <div class="form-input">
                            <input type="text" name="file_size" class="w-32 border border-gray-300 px-3 py-1.5 outline-none" placeholder="MB">
                        </div>
                    </div>
                <?php endif; ?>

            <?php elseif ($type == 'embed'): ?>
                <div class="form-row">
                    <div class="form-label required">Mã nhúng (ví dụ:Youtube ...):</div>
                    <div class="form-input space-y-3">
                        <div class="flex gap-2">
                            <input type="text" name="embed_code" required class="flex-1 border border-gray-300 px-3 py-1.5 outline-none" placeholder="<iframe src='...'></iframe>">
                            <button type="button" class="bg-[#5cb85c] text-white px-3 py-1.5 rounded text-xs">View hiển thị</button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-row">
                <div class="form-label">Mô tả bài học:</div>
                <div class="form-input">
                    <div class="border border-gray-300 bg-[#f5f5f5]">
                        <div class="border-b border-gray-300 p-1 flex flex-wrap gap-1">
                            <button type="button" class="px-2 border hover:bg-gray-200 bg-white text-xs py-1"><i class="fa-solid fa-code"></i> Source</button>
                            <button type="button" class="px-2 border hover:bg-gray-200 bg-white font-bold text-xs py-1">B</button>
                            <button type="button" class="px-2 border hover:bg-gray-200 bg-white italic text-xs py-1">I</button>
                            <button type="button" class="px-2 border hover:bg-gray-200 bg-white underline text-xs py-1">U</button>
                        </div>
                        <textarea name="description" rows="5" class="w-full p-2 outline-none resize-y"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-row border-b-0 pb-0">
                <div class="form-label"></div>
                <div class="form-input flex items-center gap-2 font-bold text-gray-700">
                    <input type="checkbox" name="is_required" value="1" checked> Bài học phải hoàn thành
                </div>
            </div>

            <div class="form-row border-b-0 mt-8">
    <div class="form-label"></div>
    <div class="form-input flex gap-3">
        <button type="submit" class="btn-save px-6 py-2 rounded-sm text-xs font-bold shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-check"></i> LƯU BÀI GIẢNG
        </button>
        
        <button type="button" onclick="window.history.back()" class="btn-back px-6 py-2 rounded-sm text-xs font-medium shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-reply"></i> QUAY LẠI
        </button>
    </div>
</div>

        </form>
      </div>
    </main>
  </div>

  <script>
    const fileInput = document.getElementById('hidden_file_input');
    const fileDisplay = document.getElementById('file_display');
    const fileError = document.getElementById('file_error');
    const form = document.getElementById('lessonForm');

    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                fileDisplay.value = '';
                return;
            }

            const allowedExts = this.getAttribute('data-ext').split(',');
            const fileName = file.name;
            const fileExt = fileName.split('.').pop().toLowerCase();

            if (allowedExts.includes(fileExt)) {
                fileDisplay.value = fileName;
                fileError.classList.add('hidden');
            } else {
                fileInput.value = '';
                fileDisplay.value = '';
                fileError.innerText = `Lỗi: Chỉ cho phép tải lên định dạng: ${allowedExts.join(', ').toUpperCase()}`;
                fileError.classList.remove('hidden');
            }
        });

        form.addEventListener('submit', function(e) {
            if (fileInput.value === '') {
                e.preventDefault();
                alert('Vui lòng chọn tệp bài giảng và đảm bảo đúng định dạng trước khi Lưu!');
            }
        });
    }
  </script>
</body>
</html>