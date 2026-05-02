<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Lesson Player - FPT Elearning</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { fptblue: "#115293", fptdark: "#003366", fptorange: "#f97316", fptyellow: "#facc15", activeblue: "#7ac0e8" }
        }
      }
    }
  </script>
  <style>
    .transition-all-300 { transition: all 0.3s ease-in-out; }
    .sidebar-text { white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.2s; }
    .expanded .sidebar-text { opacity: 1; visibility: visible; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 2px; }
  </style>
</head>

<body class="h-screen overflow-hidden bg-white font-sans text-[12px]">

<div class="flex min-h-screen relative">
<?php include 'includes/sidebar.php'; ?>

  <div id="content-wrapper" class="flex flex-col ml-16 h-screen overflow-hidden transition-all-300 w-full">
    
    <header class="bg-white h-16 flex justify-between items-center px-6 border-b flex-shrink-0 z-30">
      <a href="index.php" class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer">
        <img src="images/logo.png" alt="FPT Logo" class="h-10 w-auto object-contain" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg'"/>
        <div>
          <p class="text-xs text-gray-500 leading-tight">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-lg leading-tight uppercase">ELEARNING</p>
        </div>
      </a>

      <div class="flex items-center gap-4">
        <div class="relative">
            <input type="text" placeholder="Tìm kiếm..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-blue-500 w-64 bg-gray-50"/>
            <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        </div>
        <div class="relative cursor-pointer">
          <i class="far fa-bell text-gray-400 text-xl"></i>
        </div>
        <img src="https://i.pravatar.cc/40" class="rounded-full w-8 h-8 cursor-pointer border border-gray-200" alt="Avatar"/>
      </div>
    </header>

    <section class="bg-white px-6 py-3 border-b flex items-center justify-between flex-shrink-0 z-20 relative">
      <h1 id="course-header-title" class="font-bold text-lg text-fptblue whitespace-nowrap uppercase">ĐANG TẢI...</h1>
      <div class="flex-1 max-w-xl ml-12 flex items-center gap-4">
        <div class="flex-1 relative flex items-center">
          <div class="w-[70%] h-2.5 bg-[#2563eb] rounded-l-full relative">
            <span class="absolute -top-6 right-0 text-[#2563eb] font-bold text-xs">70%</span>
            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-4 border-[#2563eb] rounded-full translate-x-1/2 shadow-sm"></div>
          </div>
          <div class="w-[30%] h-2.5 bg-gray-200 rounded-r-full"></div>
        </div>
        <div class="w-1/4 relative flex items-center">
          <div class="w-full h-2.5 bg-[#ea580c] rounded-full relative">
            <span class="absolute -top-6 right-0 text-[#ea580c] font-bold text-xs">100%</span>
            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-4 border-[#ea580c] rounded-full translate-x-1/2 shadow-sm"></div>
          </div>
        </div>
      </div>
    </section>

    <main class="flex flex-1 overflow-hidden bg-gray-50">

      <aside class="w-[280px] bg-white border-r h-full flex flex-col flex-shrink-0">
        <div class="p-3 border-b border-gray-100">
          <h2 class="font-bold text-sm text-fptblue uppercase tracking-tight">GIÁO TRÌNH KHÓA HỌC</h2>
        </div>
        
        <div class="overflow-y-auto flex-1 p-1">
          <div id="syllabus-container" class="flex flex-col gap-px">
              <p class="text-gray-500 italic p-4 text-xs">Đang tải giáo trình...</p>
          </div>
        </div>
      </aside>

      <section class="flex-1 h-full overflow-y-auto p-5 flex flex-col">
        <div id="player-container" class="w-full bg-gray-900 rounded shadow-lg flex items-center justify-center relative overflow-hidden min-h-[400px]">
            <div class="text-center text-gray-500 py-20">
                <i class="fa-solid fa-circle-notch fa-spin text-4xl mb-4"></i>
                <p>Đang tải nội dung bài học...</p>
            </div>
        </div>

        <div class="flex justify-between items-start mt-5 bg-white p-4 rounded border border-gray-100 shadow-sm">
          <div>
            <h2 id="current-lesson-title" class="font-bold text-gray-800 text-base uppercase tracking-tight">...</h2>
            <p id="current-lesson-meta" class="text-xs text-gray-500 mt-1">Loại bài học: ...</p>
          </div>
          
          <div class="flex gap-2">
            <button id="btn-prev" class="px-4 py-1.5 border border-gray-300 bg-white rounded-sm font-medium text-gray-600 hover:bg-gray-50 transition shadow-sm flex items-center gap-1.5 text-xs disabled:opacity-40 disabled:cursor-not-allowed">
              <i class="fa-solid fa-chevron-left"></i> BÀI TRƯỚC
            </button>
            <button id="btn-next" class="px-4 py-1.5 border border-gray-300 bg-white rounded-sm font-medium text-gray-600 hover:bg-gray-50 transition shadow-sm flex items-center gap-1.5 text-xs disabled:opacity-40 disabled:cursor-not-allowed">
              BÀI TIẾP THEO <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </div>

        <div class="bg-white p-5 rounded border border-gray-100 shadow-sm mt-4 mb-10">
            <h3 class="font-bold text-gray-700 mb-3 border-b pb-2 text-xs uppercase"><i class="fa-solid fa-info-circle mr-1.5"></i> Mô tả bài học</h3>
            <div id="current_lesson_desc" class="text-gray-600 leading-relaxed text-xs prose max-w-none"></div>
        </div>
      </section>

      <aside class="w-16 bg-white flex-shrink-0 h-full flex flex-col items-center py-5 space-y-3.5 border-l border-gray-100 z-10">
        <a href="index.php" class="w-11 h-11 bg-[#3b82f6] text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-600 transition shadow-md">
          <i class="fa-solid fa-house"></i>
        </a>
        <div class="w-11 h-11 bg-[#22c55e] text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
          <i class="fa-solid fa-comments"></i>
        </div>
        <div class="w-11 h-11 bg-[#f9a8d4] text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
          <i class="fa-solid fa-file-lines"></i>
        </div>
        <div class="w-11 h-11 bg-[#f43f5e] text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="w-11 h-11 bg-[#4b5563] text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
          <i class="fa-solid fa-lightbulb"></i>
        </div>
        <div class="w-11 h-11 bg-[#14b8a6] text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
          <i class="fa-solid fa-expand"></i>
        </div>
        <button onclick="history.back()" class="w-11 h-11 bg-[#eab308] text-white rounded-full flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
          <i class="fa-solid fa-arrow-left"></i>
        </button>
      </aside>

    </main>
  </div>
</div>

<script>
  // --- Xử lý Menu Sidebar ---
  const sidebar = document.getElementById('main-sidebar');
  const contentWrapper = document.getElementById('content-wrapper');
  const menuToggle = document.getElementById('menu-toggle');
  
  if(menuToggle) {
    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('w-16'); sidebar.classList.toggle('w-64');
      sidebar.classList.toggle('expanded');
      contentWrapper.classList.toggle('ml-16'); contentWrapper.classList.toggle('ml-64');
    });
  }

  // --- LOGIC XỬ LÝ DỮ LIỆU ĐỘNG ---
  let lessonData = [];
  let moduleData = []; // Mảng chứa dữ liệu Học phần
  let currentActiveLessonId = null;

  const urlParams = new URLSearchParams(window.location.search);
  const courseId = urlParams.get('id');

  const syllabusContainer = document.getElementById("syllabus-container");
  const currentTitleLabel = document.getElementById("current-lesson-title");
  const currentMetaLabel = document.getElementById("current-lesson-meta");
  const currentDescContainer = document.getElementById("current_lesson_desc");
  const playerContainer = document.getElementById("player-container");
  const btnPrev = document.getElementById("btn-prev");
  const btnNext = document.getElementById("btn-next");

  const typeLabels = {
    'video': 'Video Bài Giảng', 'document': 'Tài liệu học (PDF)', 'embed': 'Nội dung tích hợp',
    'scorm': 'Bài học SCORM', 'audio': 'Audio Bài giảng', 'test': 'Bài kiểm tra'
  };

  const iconMap = {
    'video': 'fa-file-video text-blue-500', 'document': 'fa-file-pdf text-red-500',
    'embed': 'fa-code text-gray-500', 'test': 'fa-graduation-cap text-green-500', 'default': 'fa-file-lines text-gray-400'
  };

  async function loadLearnData() {
    if (!courseId) {
      alert("Không tìm thấy khóa học!"); window.location.href = "courses.php"; return;
    }

    try {
      // 1. Lấy tên khóa học
      const resCourse = await fetch(`api/get_course_detail.php?id=${courseId}`);
      const course = await resCourse.json();
      if (course && !course.error) {
        document.getElementById("course-header-title").innerText = course.title;
      }

      // 2. Lấy danh sách Học phần
      const resModules = await fetch(`api/get_modules.php?course_id=${courseId}`);
      moduleData = await resModules.json();

      // 3. Lấy danh sách Bài giảng
      const resLessons = await fetch(`api/get_lessons.php?course_id=${courseId}`);
      lessonData = await resLessons.json();

      if (moduleData.length === 0 && lessonData.length === 0) {
        syllabusContainer.innerHTML = "<p class='p-4 text-gray-500 text-xs'>Khóa học này chưa có giáo trình.</p>";
        playerContainer.innerHTML = "<div class='text-white p-10'>Khóa học chưa có nội dung.</div>";
        return;
      }

      // 4. Render Mục lục phân cấp
      renderSyllabus();

      // 5. Chọn bài học đầu tiên nếu có
      if (lessonData.length > 0) {
          selectLesson(lessonData[0].id);
      }

    } catch (error) {
      console.error("Lỗi:", error);
      syllabusContainer.innerHTML = "<p class='p-4 text-red-500 text-xs'>Lỗi kết nối cơ sở dữ liệu.</p>";
    }
  }

  function renderSyllabus() {
    syllabusContainer.innerHTML = "";
    
    // Lặp qua từng Học phần
    moduleData.forEach((module, mIndex) => {
      // Khối tiêu đề Học phần
      const moduleHeader = document.createElement("div");
      moduleHeader.className = "px-4 py-2 bg-gray-100 border-y border-gray-200 font-bold text-[#2A3F54] text-[11px] uppercase mt-2 first:mt-0";
      moduleHeader.innerHTML = `Học phần ${mIndex + 1}: ${module.title}`;
      syllabusContainer.appendChild(moduleHeader);

      // Lọc bài giảng thuộc Học phần hiện tại
      const lessonsInModule = lessonData.filter(l => l.module_id == module.id);

      if (lessonsInModule.length === 0) {
        const noLesson = document.createElement("div");
        noLesson.className = "p-3 text-[10px] text-gray-400 italic bg-white text-center";
        noLesson.innerText = "Chưa có bài giảng trong phần này.";
        syllabusContainer.appendChild(noLesson);
      } else {
        // Lặp qua bài giảng trong Học phần
        lessonsInModule.forEach((lesson) => {
          const item = document.createElement("div");
          const isActive = lesson.id === currentActiveLessonId;
          const iconClass = iconMap[lesson.lesson_type] || iconMap['default'];
          
          item.className = `flex justify-between items-start p-3 cursor-pointer transition-all border-b border-gray-50 ${isActive ? "bg-[#e2e8f0]" : "bg-white hover:bg-gray-50"}`;

          item.innerHTML = `
            <div class="flex gap-3 pl-2">
              <div class="w-5 h-5 rounded-full ${isActive ? "bg-fptblue" : "bg-gray-200"} flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fa-solid ${isActive ? "fa-play" : "fa-check"} text-white text-[9px]"></i>
              </div>
              <div class="flex flex-col">
                <span class="text-[12px] font-medium ${isActive ? "text-fptblue" : "text-gray-700"}">${lesson.title}</span>
                <div class="flex items-center gap-2 mt-1.5 text-gray-400 text-[10px]">
                    <i class="fa-solid ${iconClass}"></i>
                    <span>${typeLabels[lesson.lesson_type] || 'Bài học'}</span>
                    ${lesson.duration ? `<span>| ${lesson.duration} phút</span>` : ''}
                </div>
                ${lesson.is_required == 1 ? `<span class="text-red-500 text-[9px] mt-2 font-medium"><i class="fa-solid fa-star text-[7px]"></i> Bắt buộc hoàn thành</span>` : ''}
              </div>
            </div>
          `;

          item.onclick = () => selectLesson(lesson.id);
          syllabusContainer.appendChild(item);
        });
      }
    });
  }

  function renderPlayer(lesson) {
    playerContainer.innerHTML = "";
    playerContainer.classList.remove('aspect-video');
    
    switch(lesson.lesson_type) {
        case 'video':
            if(lesson.file_url) {
                playerContainer.innerHTML = `<video controls class="w-full max-h-[80vh] bg-black"><source src="${lesson.file_url}" type="video/mp4">Trình duyệt không hỗ trợ.</video>`;
            } else {
                playerContainer.innerHTML = "<div class='text-white p-10'>Lỗi: Không tìm thấy tệp video.</div>";
            }
            break;
            
        case 'document':
            if(lesson.file_url) {
                playerContainer.innerHTML = `<iframe src="${lesson.file_url}" class="w-full h-[70vh] border-0"></iframe>`;
            } else {
                playerContainer.innerHTML = "<div class='text-white p-10'>Lỗi: Không tìm thấy tệp tài liệu.</div>";
            }
            break;
            
        case 'embed':
            if(lesson.embed_code) {
                playerContainer.innerHTML = `<div class="w-full h-full relative">${lesson.embed_code}</div>`;
                const iframe = playerContainer.querySelector('iframe');
                if(iframe) {
                    iframe.classList.add('w-full', 'h-full', 'absolute', 'top-0', 'left-0');
                    playerContainer.classList.add('aspect-video');
                }
            } else {
                playerContainer.innerHTML = "<div class='text-white p-10'>Lỗi: Không tìm thấy mã nhúng.</div>";
            }
            break;
            
        default:
            playerContainer.innerHTML = `
                <div class="text-center text-gray-400 py-20 px-10">
                    <i class="fa-solid ${iconMap[lesson.lesson_type] || iconMap['default']} text-6xl mb-5"></i>
                    <p class="text-base font-bold">Bài học dạng ${typeLabels[lesson.lesson_type] || 'Khác'}</p>
                </div>
            `;
    }
  }

  function selectLesson(id) {
    currentActiveLessonId = id;
    const lesson = lessonData.find(l => l.id == id);
    
    if (lesson) {
        currentTitleLabel.textContent = lesson.title;
        currentMetaLabel.textContent = `Loại bài học: ${typeLabels[lesson.lesson_type] || 'Bài học'}`;
        currentDescContainer.innerHTML = lesson.description ? lesson.description.replace(/\n/g, '<br>') : 'Không có mô tả cho bài học này.';
        renderPlayer(lesson);
    }
    
    // Render lại toàn bộ syllabus để làm nổi bật (highlight) bài đang học
    renderSyllabus();
    updateNavigationButtons(id);
  }

  function updateNavigationButtons(currentId) {
    const currentIndex = lessonData.findIndex(l => l.id == currentId);
    
    btnPrev.disabled = currentIndex === 0;
    btnPrev.onclick = currentIndex > 0 ? () => selectLesson(lessonData[currentIndex - 1].id) : null;
    
    btnNext.disabled = currentIndex === lessonData.length - 1;
    btnNext.onclick = currentIndex < lessonData.length - 1 ? () => selectLesson(lessonData[currentIndex + 1].id) : null;
  }

  // Khởi chạy
  loadLearnData();
</script>
</body>
</html>