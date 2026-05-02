<?php
session_start();
// Đảm bảo bạn đã có file db.php để kết nối cơ sở dữ liệu
require_once 'api/db.php'; 

$error_message = '';

// 1. XỬ LÝ KHI NGƯỜI DÙNG BẤM "ĐĂNG KÝ NGAY"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_captcha = trim($_POST['captcha'] ?? '');

    // Bước 1: Kiểm tra mã bảo mật
    if (!isset($_SESSION['captcha_register']) || (int)$user_captcha !== $_SESSION['captcha_register']) {
        $error_message = 'Mã bảo mật không chính xác!';
    } 
    // Bước 2: Kiểm tra mật khẩu nhập lại
    else if ($password !== $confirm_password) {
        $error_message = 'Mật khẩu nhập lại không khớp!';
    } 
    // Bước 3: Kiểm tra xem Username hoặc Email đã bị người khác đăng ký chưa
    else {
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows > 0) {
            $error_message = 'Tên tài khoản hoặc Email này đã được sử dụng!';
        } else {
            // Bước 4: Mã hóa mật khẩu (Tuyệt đối không lưu mật khẩu thô vào CSDL)
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Bước 5: Phân quyền mặc định và lưu vào CSDL
            $role = 'student'; // Mặc định người tự đăng ký sẽ là Học viên
            $status = 1; // 1 = Hoạt động luôn (hoặc để 0 nếu cần Admin duyệt)
            $department = 'Chưa cập nhật'; // Có thể thêm trường này nếu DB bắt buộc

            $insert_sql = "INSERT INTO users (username, password, fullname, email, department, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssssssi", $username, $hashed_password, $fullname, $email, $department, $role, $status);
            
            if ($insert_stmt->execute()) {
                // BƯỚC 6: CHUYỂN HƯỚNG VỀ MÀN LOGIN SAU KHI LƯU THÀNH CÔNG
                // Truyền thêm tham số ?registered=1 để màn login biết là vừa đăng ký xong
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error_message = 'Lỗi hệ thống! Không thể lưu dữ liệu: ' . $conn->error;
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}

// TẠO MÃ BẢO MẬT CHO LẦN TẢI TRANG
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha_register'] = $num1 + $num2; 
$captcha_text = "$num1 + $num2 = ?";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELEARNING - Đăng ký tài khoản</title>
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

    <div class="max-w-5xl w-full mx-auto px-6 flex flex-col md:flex-row items-center justify-between relative z-10 gap-10 py-8">
        
        <!-- CỘT TRÁI (Giới thiệu) -->
        <div class="w-full md:w-[55%] hidden md:block">
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

        <!-- CỘT PHẢI: FORM ĐĂNG KÝ -->
        <div class="w-full md:w-[380px] glass-panel rounded-lg p-6 shadow-2xl">
            <div class="flex justify-center mb-4">
                <div class="flex skew-x-[-15deg]">
                    <span class="bg-[#175aa3] text-white font-bold text-2xl px-2 py-0.5">F</span>
                    <span class="bg-[#f26f21] text-white font-bold text-2xl px-2 py-0.5">P</span>
                    <span class="bg-[#00a950] text-white font-bold text-2xl px-2 py-0.5">T</span>
                </div>
            </div>

            <h3 class="text-center font-bold text-[15px] mb-4 tracking-wide">Đăng ký tài khoản</h3>

            <!-- Khu vực hiển thị thông báo lỗi PHP -->
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-500/80 border border-red-500 text-white text-xs px-3 py-2 rounded mb-4 text-center">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-3">
                <!-- Sử dụng value htmlspecialchars để giữ lại dữ liệu khi người dùng nhập sai form -->
                <div>
                    <input type="text" name="fullname" placeholder="Họ và tên" required value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
                           class="w-full bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="w-full bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition">
                </div>
                <div>
                    <input type="text" name="username" placeholder="Tên tài khoản đăng nhập" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           class="w-full bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition">
                </div>
                <div class="flex gap-2">
                    <input type="password" name="password" placeholder="Mật khẩu" required 
                           class="w-1/2 bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition">
                    <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required 
                           class="w-1/2 bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition">
                </div>

                <div class="flex flex-row items-stretch gap-2 w-full pt-1">
                    <div class="bg-white text-gray-800 px-1 py-1.5 rounded font-mono font-bold text-[14px] tracking-wider whitespace-nowrap captcha-bg w-[110px] shrink-0 flex items-center justify-center border border-gray-300 select-none">
                        <?= $captcha_text ?>
                    </div>
                    <input type="text" name="captcha" placeholder="Mã bảo mật" required 
                           class="flex-1 min-w-0 bg-[#286ba6] text-white placeholder-white/80 px-3 py-1.5 rounded text-[13px] outline-none focus:bg-[#205b90] transition">
                </div>

                <button type="submit" class="w-full bg-[#00a950] hover:bg-[#008f44] text-[13px] font-bold py-2 rounded transition shadow-sm mt-2">
                    Đăng ký ngay
                </button>

                <div class="text-center pt-3 text-[12px] font-medium border-t border-white/10 mt-3">
                    <span>Đã có tài khoản?</span> 
                    <a href="login.php" class="text-[#6baae4] hover:text-white transition ml-1 underline underline-offset-2">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>