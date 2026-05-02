<?php
session_start();

// KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// LẤY THÔNG TIN TỪ SESSION ĐỂ PHÂN QUYỀN SIDEBAR
$fullname = $_SESSION['fullname'] ?? 'Học viên';
$role = $_SESSION['role'] ?? 'student'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Course Detail - FPT Elearning</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            fptblue: "#115293",
            fptdark: "#003366",
            fptorange: "#f97316"
          }
        }
      }
    }
  </script>
  <style>
    .transition-all-300 { transition: all 0.3s ease-in-out; }
    .sidebar-text {
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.2s;
    }
    .expanded .sidebar-text {
      opacity: 1;
      visibility: visible;
    }
  </style>
</head>

<body class="bg-gray-100 overflow-x-hidden">
<div class="flex min-h-screen relative">
<?php include 'includes/sidebar.php'; ?>

  <div id="content-wrapper" class="flex-1 flex flex-col ml-16 transition-all-300 w-full">

    <header class="bg-white h-16 flex justify-between items-center px-6 border-b z-40 sticky top-0">
      <a href="index.php" class="flex items-center gap-3 hover:opacity-80">
        <img src="images/logo.png" class="h-10 w-auto object-contain" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg'"/>
        <div>
          <p class="text-xs text-gray-500">Hệ thống đào tạo trực tuyến</p>
          <p class="font-bold text-fptblue text-lg">ELEARNING</p>
        </div>
      </a>
      <div class="flex items-center gap-6">
        <input type="text" placeholder="Tìm kiếm..." class="border rounded-full px-4 py-1.5 text-sm outline-none focus:border-fptblue w-64 bg-gray-50"/>
        <div class="relative cursor-pointer">
          <i class="far fa-bell text-gray-600 text-lg"></i>
        </div>
        <img src="https://i.pravatar.cc/40" class="rounded-full w-8 h-8 border border-gray-200 cursor-pointer"/>
      </div>
    </header>

    <main class="p-6 space-y-6">

      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 space-y-4">
        <div class="text-sm text-gray-500 flex items-center gap-2">
          <a href="courses.php" class="hover:text-fptblue transition-colors">Danh sách khóa học</a> / 
          <span id="breadcrumb-title" class="text-fptdark font-medium">Đang tải...</span>
        </div>

        <h1 id="course-title" class="text-2xl font-bold text-fptdark uppercase">...</h1>

        <div class="text-gray-500 flex items-center gap-2 text-sm">
          <i class="fa-solid fa-calendar-days"></i>
          <span id="course-time">...</span>
        </div>

        <div class="flex gap-4 flex-wrap mt-4">
          <div class="bg-blue-50 px-4 py-2 rounded border border-blue-100 flex items-center gap-2 text-sm">
            <i class="fa-solid fa-layer-group text-blue-600"></i>
            <span id="lesson-count" class="font-medium text-gray-700">0 bài học</span>
          </div>
          <div class="bg-purple-50 px-4 py-2 rounded border border-purple-100 flex items-center gap-2 text-sm">
            <i class="fa-solid fa-users text-purple-600"></i>
            <span id="student-count" class="font-medium text-gray-700">0 học viên</span>
          </div>
          <div class="bg-green-50 px-4 py-2 rounded border border-green-100 flex items-center gap-2 text-sm">
            <i class="fa-solid fa-list-check text-green-600"></i>
            <span id="survey-status" class="font-medium text-gray-700">Không có khảo sát</span>
          </div>
        </div>
        <div id="action-button-container"></div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <div class="lg:col-span-9 space-y-6">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h2 class="font-bold mb-3 text-fptdark border-b pb-2">GIỚI THIỆU</h2>
            <div id="course-description" class="text-gray-600 text-sm leading-relaxed"></div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h2 class="font-bold mb-3 text-fptdark border-b pb-2">GIÁO TRÌNH</h2>
            <div id="course-syllabus" class="space-y-1">
                <p class="text-gray-500 italic text-sm">Đang tải danh sách bài giảng...</p>
            </div>
          </div>
        </div>

        <div class="lg:col-span-3">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h2 class="font-bold mb-3 text-fptdark border-b pb-2">BÌNH LUẬN</h2>
            <div class="flex gap-2">
              <input id="comment-input" class="w-full border border-gray-300 p-2 text-sm rounded outline-none focus:border-fptblue" placeholder="Nhập bình luận..." />
              <button onclick="addComment()" class="bg-fptdark text-white px-4 rounded text-sm hover:bg-fptblue transition-colors">Gửi</button>
            </div>
            <div id="comment-list" class="mt-4 space-y-2 text-sm"></div>
          </div>
        </div>

      </div>

    </main>
  </div>
</div>

<script>
// --- TOGGLE SIDEBAR ---
const sidebar = document.getElementById('main-sidebar');
const contentWrapper = document.getElementById('content-wrapper');
const menuToggle = document.getElementById('menu-toggle');

menuToggle.addEventListener('click', () => {
  sidebar.classList.toggle('w-16');
  sidebar.classList.toggle('w-64');
  sidebar.classList.toggle('expanded');
  contentWrapper.classList.toggle('ml-16');
  contentWrapper.classList.toggle('ml-64');
});

// --- LẤY DỮ LIỆU TỪ DATABASE ---
const urlParams = new URLSearchParams(window.location.search);
const courseId = urlParams.get("id");

async function loadCourseDetail() {
  if (!courseId) {
    alert("Không tìm thấy ID khóa học!");
    window.location.href = "courses.php";
    return;
  }

  try {
    const resCourse = await fetch('api/get_courses.php?view=enrolled');
    const courses = await resCourse.json();
    const course = courses.find(c => c.id == courseId);

    if (!course) {
      document.getElementById("course-title").innerText = "KHÓA HỌC KHÔNG TỒN TẠI";
      return;
    }

    // Hiển thị thông tin Khóa học
    document.getElementById("course-title").innerText = course.title;
    document.getElementById("breadcrumb-title").innerText = course.title;
    document.getElementById("course-description").innerText = course.description || "Chưa có mô tả cho khóa học này.";
    document.getElementById("course-time").innerText = course.time_range || "Không giới hạn";
    document.getElementById("student-count").innerText = (course.students || 0) + " học viên";
    
    // --- XỬ LÝ NÚT BẤM CHUẨN NGHIỆP VỤ ---
    let btnHtml = '';

    // 1. Đã đăng ký và được duyệt
    if (course.enroll_status === 'Đang học' || course.enroll_status === 'Đã hoàn thành') {
        btnHtml = `<button onclick="goToLearn(${course.id})" class="mt-6 px-8 py-2.5 bg-[#115293] text-white font-medium rounded hover:bg-[#003366] transition-colors shadow-sm"><i class="fa-solid fa-play mr-2"></i> Vào học</button>`;
    } 
    // 2. Chờ duyệt
    else if (course.enroll_status === 'Chờ duyệt') {
        btnHtml = `<button disabled class="mt-6 px-8 py-2.5 bg-gray-400 text-white font-medium rounded cursor-not-allowed shadow-sm"><i class="fa-solid fa-hourglass-half mr-2"></i> Đang chờ duyệt...</button>`;
    } 
    // 3. Chưa đăng ký
    else {
        if (course.registration_type === 'Không cho đăng ký') {
            btnHtml = `<button disabled class="mt-6 px-8 py-2.5 bg-[#e2e8f0] text-[#64748b] font-medium rounded cursor-not-allowed shadow-sm border border-[#cbd5e1]"><i class="fa-solid fa-lock mr-2"></i> Chỉ định nội bộ</button>`;
        } 
        else if (course.registration_type === 'Kiểm duyệt') {
            btnHtml = `<button onclick="registerCourseAction(${course.id})" class="mt-6 px-8 py-2.5 bg-fptorange text-white font-medium rounded hover:bg-orange-600 transition-colors shadow-sm"><i class="fa-solid fa-pen-to-square mr-2"></i> Đăng ký ngay</button>`;
        } 
        else if (course.registration_type === 'Đăng ký tự do') {
            btnHtml = `<button onclick="autoRegisterAndLearn(${course.id})" class="mt-6 px-8 py-2.5 bg-[#115293] text-white font-medium rounded hover:bg-[#003366] transition-colors shadow-sm"><i class="fa-solid fa-play mr-2"></i> Vào học</button>`;
        }
    }
    document.getElementById("action-button-container").innerHTML = btnHtml;

    // Fetch danh sách Bài giảng
    const resLessons = await fetch(`api/get_lessons.php?course_id=${courseId}`);
    const lessons = await resLessons.json();

    document.getElementById("lesson-count").innerText = lessons.length + " bài học";

    const syllabusContainer = document.getElementById("course-syllabus");
    if (lessons.length > 0) {
      syllabusContainer.innerHTML = lessons.map((l, index) => `
        <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors px-2 rounded">
          <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex justify-center items-center text-xs font-bold">${index + 1}</div>
          <i class="${l.file_icon || 'fa-solid fa-file'} text-gray-400 w-4 text-center"></i>
          <span class="text-sm font-medium text-gray-700">${l.title}</span>
          <span class="ml-auto text-xs text-gray-400 border px-2 py-1 rounded bg-white">${l.type}</span>
        </div>
      `).join("");
    } else {
      syllabusContainer.innerHTML = "<p class='text-gray-500 italic text-sm'>Khóa học này chưa có bài giảng nào được cập nhật.</p>";
    }

  } catch (error) {
    console.error("Lỗi:", error);
    alert("Không thể tải dữ liệu từ máy chủ!");
  }
}

// Khởi chạy khi load trang
loadCourseDetail();

// --- CHỨC NĂNG NÚT BẤM ---

// 1. Vào học (Dành cho khóa đã duyệt)
function goToLearn(id) {
    window.location.href = "learn.php?id=" + id;
}

// 2. Đăng ký (Dành cho khóa Kiểm duyệt)
async function registerCourseAction(cId) {
    if(confirm("Bạn có chắc chắn muốn đăng ký khóa học này? Hệ thống sẽ gửi yêu cầu chờ Admin phê duyệt.")) {
        try {
            const res = await fetch('api/register_course.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ course_id: cId })
            });
            const data = await res.json();
            alert(data.message);
            if(data.success) {
                location.reload(); 
            }
        } catch (error) {
            alert("Lỗi kết nối máy chủ!");
        }
    }
}

// 3. Đăng ký ngầm rồi Vào học ngay (Dành cho khóa Tự do)
async function autoRegisterAndLearn(cId) {
    try {
        await fetch('api/register_course.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ course_id: cId })
        });
        window.location.href = "learn.php?id=" + cId;
    } catch (error) {
        console.error("Lỗi đăng ký tự do:", error);
        window.location.href = "learn.php?id=" + cId; 
    }
}

// --- LOGIC BÌNH LUẬN ---
function addComment() {
  const input = document.getElementById("comment-input");
  const list = document.getElementById("comment-list");

  if (input.value.trim() === "") return;

  const div = document.createElement("div");
  div.className = "bg-gray-50 p-3 rounded border border-gray-100 flex gap-3";
  div.innerHTML = `
    <img src="https://i.pravatar.cc/40" class="rounded-full w-8 h-8"/>
    <div>
        <p class="font-bold text-fptdark text-xs mb-1">Bạn</p>
        <p class="text-gray-600">${input.value}</p>
    </div>
  `;

  list.prepend(div);
  input.value = "";
}

document.getElementById("comment-input").addEventListener("keypress", function(e){
  if(e.key === "Enter") addComment();
});
</script>

</body>
</html>