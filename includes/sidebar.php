<?php
// Lấy tên file hiện tại để xử lý trạng thái Active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside id="main-sidebar" class="w-16 bg-fptdark flex flex-col py-6 text-white fixed top-0 left-0 h-screen z-50 transition-all-300 overflow-hidden shadow-xl">
    <div class="flex flex-col w-full h-full">
        <!-- Nút Toggle Menu -->
        <div id="menu-toggle" class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-colors">
            <i class="fas fa-bars w-6 text-center text-xl"></i>
            <span class="sidebar-text ml-4 font-bold text-lg uppercase tracking-wider text-fptorange">Menu</span>
        </div>

        <div class="mt-6 space-y-2 flex-1">
            <!-- TRANG CÁ NHÂN -->
            <div onclick="location.href='index.php'" 
                 class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-all <?= ($current_page == 'index.php') ? 'bg-fptblue' : '' ?>">
                <i class="fas fa-gauge-high w-6 text-center"></i>
                <span class="sidebar-text ml-4 text-sm font-medium">TRANG CÁ NHÂN</span>
            </div>

            <!-- NHU CẦU ĐÀO TẠO -->
            <div onclick="location.href='training_needs.php'" 
                 class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-all <?= ($current_page == 'courses.php') ? 'bg-fptblue' : '' ?>">
                <i class="fas fa-users w-6 text-center"></i>
                <span class="sidebar-text ml-4 text-sm font-medium">NHU CẦU ĐÀO TẠO</span>
            </div>

            <!-- TÀI LIỆU -->
            <div onclick="location.href='documents.php'" 
                 class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-all <?= ($current_page == 'documents.php') ? 'bg-fptblue border-l-4 border-fptorange' : '' ?>">
                <i class="fas fa-folder-open w-6 text-center"></i>
                <span class="sidebar-text ml-4 text-sm font-medium">TÀI LIỆU</span>
            </div>

            <!-- HÒM THƯ -->
            <div onclick="location.href='messages.php'" 
                class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-all">
                <i class="fas fa-envelope w-6 text-center"></i>
                <span class="sidebar-text ml-4 text-sm font-medium">HÒM THƯ</span>
            </div>

            <!-- THÔNG TIN CÁ NHÂN -->
            <div onclick="location.href='profile.php'" 
                class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-all <?= ($current_page == 'profile.php') ? 'bg-fptblue border-l-4 border-fptorange' : '' ?>">
               <i class="fas fa-user w-6 text-center"></i>
               <span class="sidebar-text ml-4 text-sm font-medium">THÔNG TIN CÁ NHÂN</span>
           </div>              
        </div>

        <div class="space-y-2 pb-4 border-t border-white/10 pt-4">
            <?php if (isset($role) && $role === 'admin'): ?>
            <div onclick="location.href='admin.php'" class="flex items-center px-5 py-3 cursor-pointer hover:bg-fptblue transition-all">
                <i class="fas fa-laptop-code w-6 text-center"></i>
                <span class="sidebar-text ml-4 text-sm font-medium">TRANG QUẢN LÝ</span>
            </div>
            <?php endif; ?>
            
            <div onclick="location.href='logout.php'" class="flex items-center px-5 py-3 cursor-pointer hover:bg-red-600 transition-all">
                <i class="fas fa-power-off w-6 text-center"></i>
                <span class="sidebar-text ml-4 text-sm font-medium">ĐĂNG XUẤT</span>
            </div>
        </div>
    </div>
</aside>