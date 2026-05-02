<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    $name = trim($_POST['name'] ?? '');
    $target = trim($_POST['target'] ?? ''); // Thêm biến lấy Mục tiêu đào tạo
    $note = trim($_POST['note'] ?? '');
    $unit = trim($_POST['unit'] ?? '');
    $teacher = trim($_POST['teacher'] ?? '');
    $competency = trim($_POST['competency'] ?? '');
    $proposer = $_SESSION['fullname'] ?? 'Học viên';
    
    $start = $_POST['start_date'] ?? '';
    $end = $_POST['end_date'] ?? '';
    $time = "";
    if(!empty($start) && !empty($end)) {
        $time = date('d/m/Y', strtotime($start)) . ' - ' . date('d/m/Y', strtotime($end));
    }

    if ($id > 0) {
        // Có $target, tổng cộng 8 tham số (sssssssi)
        $sql = "UPDATE training_needs SET name=?, target=?, note=?, unit=?, teacher=?, competency=?, time=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $name, $target, $note, $unit, $teacher, $competency, $time, $id);
    } else {
        // Có $target, tổng cộng 8 tham số (ssssssss)
        $sql = "INSERT INTO training_needs (name, target, note, unit, teacher, competency, time, proposer, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Chưa gửi')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $target, $note, $unit, $teacher, $competency, $time, $proposer);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Lưu thành công!'); window.location.href = '../training_needs.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>