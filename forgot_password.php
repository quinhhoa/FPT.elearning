<?php
session_start();
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha_forgot'] = $num1 + $num2; 
$captcha_text = "$num1 + $num2 = ?";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELEARNING - Quên mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; }
        .glass-panel {
            background-color: #113352;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .captcha-bg { background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); }
    </style>
</head>
<body class="bg-[#0b253a] min-h-screen flex items-center justify-center text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 70% 50%, #ffffff 2px, transparent 2px); background-size: 40px 40px;"></div>

    <div class="max-w-5xl w-full mx-auto px-6 flex flex-col md:flex-row items-center justify-between relative z-10 gap-10">
        
        <!-- CỘT TRÁI (Giữ nguyên) -->
        <div class="w-full md:w-[55%]">
            <h2 class="text-[22px] font-light mb-1">Chào mừng tới</h2>
            <h1 class="text-[54px] font-bold tracking-wider mb-4 leading-tight uppercase">Elearning</h1>
            <h3 class="text-[22px] font-light mb-8">Hệ thống đào tạo trực tuyến</h3>
            <div class="mb-10 relative inline-block">
                <select class="bg-transparent border border-gray-400 text-sm rounded-full pl-4 pr-8 py-1.5 outline-none cursor-pointer hover:border-white transition appearance-none">
                    <option class="text-gray-800">Tiếng Việt</option>
                    <option class="text-gray-800">English</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-[10px] text-[10px] pointer-events-none"></i>
            </div>
            <div class="flex gap-4 mb-8">
                <button class="bg-black border border-gray-700 hover:border-gray-500 rounded px-3 py-1.5 flex items-center gap-2 transition w-36 shadow-lg">
                    <i class="fa-brands fa-apple text-2xl"></i>
                    <div class="text-left">
                        <div class="text-[9px] text-gray-300">Download on the</div>
                        <div class="font-semibold text-xs leading-tight">App Store</div>
                    </div>
                </button>
                <button class="bg-black border border-gray-700 hover:border-gray-500 rounded px-3 py-1.5 flex items-center gap-2 transition w-36 shadow-lg">
                    <i class="fa-brands fa-google-play text-xl"></i>
                    <div class="text-left">
                        <div class="text-[9px] text-gray-300">GET IT ON</div>
                        <div class="font-semibold text-xs leading-tight">Google Play</div>
                    </div>
                </button>
            </div>
            <p class="text-[13px] font-medium text-gray-300">Phần mềm hỗ trợ tốt nhất trên trình duyệt Chrome, Firefox</p>
        </div>

        <!-- CỘT PHẢI: FORM QUÊN MẬT KHẨU -->
        <div class="w-full md:w-[350px] glass-panel rounded-lg p-7 shadow-2xl">
            <div class="flex justify-center mb-4">
                <div class="flex skew-x-[-15deg]">
                    <span class="bg-[#175aa3] text-white font-bold text-3xl px-2.5 py-0.5">F</span>
                    <span class="bg-[#f26f21] text-white font-bold text-3xl px-2.5 py-0.5">P</span>
                    <span class="bg-[#00a950] text-white font-bold text-3xl px-2.5 py-0.5">T</span>
                </div>
            </div>

            <h3 class="text-center font-bold text-[15px] mb-2 tracking-wide">Khôi phục mật khẩu</h3>
            <p class="text-center text-[12px] text-gray-300 mb-5">Nhập Email hoặc Tên tài khoản để nhận hướng dẫn khôi phục.</p>

            <form action="forgot_password.php" method="POST" class="space-y-4">
                <div>
                    <input type="text" name="email_username" placeholder="Email hoặc Tài khoản" required 
                           class="w-full bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#f26f21] transition">
                </div>

                <div class="flex flex-row items-stretch gap-2 w-full">
                    <div class="bg-white text-gray-800 px-1 py-2 rounded font-mono font-bold text-[14px] tracking-wider whitespace-nowrap captcha-bg w-[110px] shrink-0 flex items-center justify-center border border-gray-300 select-none">
                        <?= $captcha_text ?>
                    </div>
                    <input type="text" name="captcha" placeholder="Mã bảo mật" required 
                           class="flex-1 min-w-0 bg-[#286ba6] text-white placeholder-white/80 px-3 py-2 rounded text-[13px] outline-none focus:bg-[#f26f21] transition">
                </div>

                <button type="submit" class="w-full bg-[#f26f21] hover:bg-[#d85e15] text-[13px] font-bold py-2 rounded transition shadow-sm mt-2">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Gửi yêu cầu
                </button>

                <div class="text-center pt-3 text-[12px] font-medium border-t border-white/10 mt-4">
                    <a href="login.php" class="text-gray-300 hover:text-white transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại Đăng nhập
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>