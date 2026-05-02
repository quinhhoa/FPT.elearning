<?php
session_start();
// KẾT NỐI CƠ SỞ DỮ LIỆU (Bạn nhớ đảm bảo đường dẫn file db.php này chính xác nhé)
require_once 'api/db.php'; 

$error_message = '';

// 1. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT "ĐĂNG NHẬP"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $user_captcha = trim($_POST['captcha'] ?? '');

    // Kiểm tra Mã bảo mật trước tiên
    if (!isset($_SESSION['captcha_result']) || (int)$user_captcha !== $_SESSION['captcha_result']) {
        $error_message = 'Mã bảo mật không chính xác!';
    } else {
        // Mã bảo mật đúng -> Tiến hành truy vấn CSDL
        $sql = "SELECT id, username, password, role, fullname, status FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Kiểm tra trạng thái tài khoản
            if ($user['status'] == 0) {
                $error_message = 'Tài khoản của bạn đã bị khóa!';
            } 
            // So sánh mật khẩu (Hỗ trợ cả mật khẩu mã hóa Bcrypt và mật khẩu thuần)
            else if (password_verify($password, $user['password']) || $password === $user['password']) {
                // Đăng nhập thành công -> Lưu Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                
                // Chuyển hướng về trang chủ
                header("Location: index.php");
                exit;
            } else {
                $error_message = 'Mật khẩu không chính xác!';
            }
        } else {
            $error_message = 'Tài khoản không tồn tại!';
        }
        $stmt->close();
    }
}

// 2. TẠO MÃ BẢO MẬT MỚI CHO LẦN TẢI TRANG NÀY
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha_result'] = $num1 + $num2; // Lưu kết quả vào Session để so sánh khi submit
$captcha_text = "$num1 + $num2 = ?";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELEARNING - Đăng nhập hệ thống</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <style>
        body { font-family: "Helvetica Neue", Roboto, Arial, sans-serif; }
        
        .glass-panel {
            background-color: #113352;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .captcha-bg {
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
        }
    </style>
</head>
<body class="bg-[#0b253a] min-h-screen flex items-center justify-center text-white relative overflow-hidden">

    <!-- Trả lại nền chấm bi cũ -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 70% 50%, #ffffff 2px, transparent 2px); background-size: 40px 40px;"></div>

    <div class="max-w-5xl w-full mx-auto px-6 flex flex-col md:flex-row items-center justify-between relative z-10 gap-10">
        
        <!-- CỘT TRÁI: THÔNG TIN GIỚI THIỆU -->
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

        <!-- CỘT PHẢI: FORM ĐĂNG NHẬP -->
        <div class="w-full md:w-[350px] glass-panel rounded-lg p-7 shadow-2xl">
            
            <!-- Logo FPT -->
            <div class="flex justify-center mb-5">
                <div class="flex skew-x-[-15deg]">
                    <span class="bg-[#175aa3] text-white font-bold text-3xl px-2.5 py-0.5">F</span>
                    <span class="bg-[#f26f21] text-white font-bold text-3xl px-2.5 py-0.5">P</span>
                    <span class="bg-[#00a950] text-white font-bold text-3xl px-2.5 py-0.5">T</span>
                </div>
            </div>

            <h3 class="text-center font-bold text-[15px] mb-5 tracking-wide">Đăng nhập tài khoản</h3>

            <!-- Khu vực hiển thị thông báo lỗi PHP -->
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-500/80 border border-red-500 text-white text-xs px-3 py-2 rounded mb-4 text-center">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-4">
                
                <div>
                    <label class="block text-[12px] mb-1.5 text-gray-200">Tên tài khoản</label>
                    <input type="text" name="username" placeholder="Tên tài khoản" required 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           class="w-full bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition">
                </div>

                <div>
                    <label class="block text-[12px] mb-1.5 text-gray-200">Mật khẩu</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Mật khẩu" required 
                               class="w-full bg-white text-gray-800 px-3 py-2 rounded text-[13px] outline-none focus:ring-2 focus:ring-[#3482C4] transition pr-8">
                        <button type="button" onclick="togglePassword()" class="absolute right-2.5 top-2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fa-regular fa-eye-slash text-[13px]" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- ĐÃ SỬA LỖI MÃ BẢO MẬT: Thêm whitespace-nowrap và w-[110px] để cấm xuống dòng -->
                <div class="flex flex-row items-stretch gap-2 w-full">
                    <div class="bg-white text-gray-800 px-1 py-2 rounded font-mono font-bold text-[14px] tracking-wider whitespace-nowrap captcha-bg w-[110px] shrink-0 flex items-center justify-center border border-gray-300 select-none">
                        <?= $captcha_text ?>
                    </div>
                    <input type="text" name="captcha" placeholder="Mã bảo mật" required 
                           class="flex-1 min-w-0 bg-[#286ba6] text-white placeholder-white/80 px-3 py-2 rounded text-[13px] outline-none focus:bg-[#205b90] transition">
                </div>

                <div class="flex items-center gap-2 pt-1 pb-1">
                    <input type="checkbox" id="remember" name="remember" class="w-3.5 h-3.5 cursor-pointer accent-[#3482C4]">
                    <label for="remember" class="text-[12px] cursor-pointer hover:text-gray-200 transition">Ghi nhớ tên đăng nhập</label>
                </div>

                <button type="submit" class="w-full bg-[#3482C4] hover:bg-[#2c77b7] text-[13px] font-bold py-2 rounded transition shadow-sm">
                    Đăng nhập
                </button>

                <div class="flex justify-between pt-3 text-[11px] font-medium border-t border-white/10 mt-4">
                    <a href="register.php" class="hover:text-[#6baae4] transition border-b border-transparent hover:border-[#6baae4]">Đăng ký tài khoản</a>
                    <a href="forgot_password.php" class="hover:text-[#6baae4] transition border-b border-transparent hover:border-[#6baae4]">Quên mật khẩu?</a>
                </div>
            </form>
            <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
    <div class="bg-[#00a950]/90 border border-[#00a950] text-white text-xs px-3 py-2 rounded mb-4 text-center shadow-md">
        <i class="fa-solid fa-check-circle mr-1"></i> Đăng ký thành công! Vui lòng đăng nhập.
    </div>
<?php endif; ?>
        </div>
    </div>

    <!-- Script điều khiển ẩn hiện mật khẩu -->
    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>