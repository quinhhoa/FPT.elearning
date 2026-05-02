<?php
// File: logout.php
session_start();
session_unset();    // Xóa mọi biến session
session_destroy();  // Phá hủy session hiện tại
header("Location: login.php"); // Quay về trang đăng nhập
exit;
?>