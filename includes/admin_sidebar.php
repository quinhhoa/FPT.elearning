<aside class="w-[200px] bg-[#2A3F54] text-white flex flex-col fixed h-screen z-50 overflow-y-auto transition-all duration-300 font-sans shadow-xl">
    <div class="h-12 flex items-center justify-center bg-[#2A3F54] border-b border-[#3b536a]/50">
        <img src="images/logonen.png" alt="FPT" class="h-6" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/1/11/FPT_logo_2010.svg'"/>
    </div>
    
    <div class="flex-1 mt-2">
        <ul class="space-y-0">
            <!-- TỔ CHỨC HỌC -->
            <li class="px-4 py-1.5 mt-2 text-[10px] uppercase font-bold text-[#5c7791] tracking-wider">TỔ CHỨC HỌC</li>
            <li>
                <a href="admin_training_needs.php" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]">
                    <i class="fa-solid fa-users w-4 text-center"></i>
                    <span>Quản lý nhu cầu đào tạo</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0)" onclick="toggleSubMenu(this)" class="flex items-center justify-between px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-desktop w-4 text-center"></i> 
                        <span>Quản lý khóa học</span>
                    </div>
                    <i class="submenu-icon fa-solid fa-chevron-left text-[9px] transition-transform"></i>
                </a>
                <ul class="bg-[#1e2d3d] py-1.5 hidden">
                    <li><a href="admin.php" class="flex items-center px-8 py-1.5 text-[11.5px] hover:text-white text-gray-400"><i class="fa-solid fa-circle text-[5px] mr-2.5 opacity-60"></i> Danh sách khóa học</a></li>
                    <li><a href="admin_add_course.php" class="flex items-center px-8 py-1.5 text-[11.5px] hover:text-white text-gray-400"><i class="fa-solid fa-circle text-[5px] mr-2.5 opacity-60"></i> Thêm mới khóa học</a></li>
                </ul>
            </li>

            <!-- TỔ CHỨC THI -->
            <li class="px-4 py-1.5 mt-2 text-[10px] uppercase font-bold text-[#5c7791] tracking-wider">TỔ CHỨC THI</li>
            <li>
                <a href="javascript:void(0)" onclick="toggleSubMenu(this)" class="flex items-center justify-between px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-graduation-cap w-4 text-center"></i> 
                        <span>Quản lý bài thi</span>
                    </div>
                    <i class="submenu-icon fa-solid fa-chevron-left text-[9px] transition-transform"></i>
                </a>
                <ul class="bg-[#1e2d3d] py-1.5 hidden">
                    <li><a href="admin_exams.php" class="flex items-center px-8 py-1.5 text-[11.5px] hover:text-white text-gray-400"><i class="fa-solid fa-circle text-[5px] mr-2.5 opacity-60"></i> Danh sách bài thi</a></li>
                    <li><a href="admin_add_exam.php" class="flex items-center px-8 py-1.5 text-[11.5px] hover:text-white text-gray-400"><i class="fa-solid fa-circle text-[5px] mr-2.5 opacity-60"></i> Thêm mới bài thi</a></li>
                </ul>
            </li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-pen-nib w-4 text-center"></i> <span>Chấm thi</span></a></li>

            <!-- TIỆN ÍCH -->
            <li class="px-4 py-1.5 mt-2 text-[10px] uppercase font-bold text-[#5c7791] tracking-wider">Tiện ích</li>
            <li><a href="admin_documents.php" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-file-lines w-4 text-center"></i> <span>Quản lý tài liệu</span></a></li>
            <!-- ĐÃ SỬA ĐIỀU HƯỚNG TẠI ĐÂY -->
            <li><a href="admin_surveys.php" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-square-poll-vertical w-4 text-center"></i> <span>Quản lý khảo sát</span></a></li>

            
            <!-- BÁO CÁO & THỐNG KÊ -->
            <li class="px-4 py-1.5 mt-2 text-[10px] uppercase font-bold text-[#5c7791] tracking-wider">BÁO CÁO & THỐNG KÊ</li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-chart-line w-4 text-center"></i> <span>Báo cáo đào tạo</span></a></li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-chart-pie w-4 text-center"></i> <span>Thống kê kết quả thi</span></a></li>

            <!-- HỆ THỐNG (Đã chuyển xuống dưới cùng theo yêu cầu) -->
            <li class="px-4 py-1.5 mt-2 text-[10px] uppercase font-bold text-[#5c7791] tracking-wider">HỆ THỐNG</li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-gear w-4 text-center"></i> <span>Cấu hình hệ thống</span></a></li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-layer-group w-4 text-center"></i> <span>Quản lý danh mục</span></a></li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-user-shield w-4 text-center"></i> <span>Quản lý vai trò</span></a></li>
            <li><a href="#" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-[#172D44] transition text-[12px]"><i class="fa-solid fa-user-gear w-4 text-center"></i> <span>Quản lý người dùng</span></a></li>
        </ul>
    </div>

    <div class="p-4 border-t border-[#3b536a]/50">
        <a href="index.php" class="flex items-center gap-2 text-[11.5px] text-gray-400 hover:text-white transition-colors">
            <i class="fa-solid fa-power-off"></i> Thoát trang Admin
        </a>
    </div>
</aside>

<script>
function toggleSubMenu(el) {
    const subMenu = el.nextElementSibling;
    const icon = el.querySelector('.submenu-icon');
    
    if (subMenu) {
        const isCollapsed = subMenu.classList.contains('hidden');
        if (isCollapsed) {
            subMenu.classList.remove('hidden');
            icon.classList.replace('fa-chevron-left', 'fa-chevron-down');
            el.classList.add('bg-[#172D44]', 'border-l-4', 'border-[#1ABB9C]');
        } else {
            subMenu.classList.add('hidden');
            icon.classList.replace('fa-chevron-down', 'fa-chevron-left');
            el.classList.remove('bg-[#172D44]', 'border-l-4', 'border-[#1ABB9C]');
        }
    }
}
</script>