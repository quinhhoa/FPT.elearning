<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$unit_filter = isset($_GET['unit']) ? $conn->real_escape_string($_GET['unit']) : '';
$view = isset($_GET['view']) ? $_GET['view'] : 'student'; 
$fullname = $_SESSION['fullname'] ?? 'Học viên'; 

$conditions = [];

// 1. PHÂN LUỒNG QUYỀN
if ($view === 'admin') {
    $conditions[] = "status != 'Chưa gửi'";
} else {
    $fullname_safe = $conn->real_escape_string($fullname);
    $conditions[] = "proposer = '$fullname_safe'";
}

// 2. CÁC BỘ LỌC TÌM KIẾM
if (!empty($search)) {
    // Tìm tương đối trong Tên, Mục tiêu hoặc Năng lực
    $conditions[] = "(name LIKE '%$search%' OR target LIKE '%$search%' OR competency LIKE '%$search%')";
}
if (!empty($status_filter)) {
    // Lọc tuyệt đối theo Trạng thái
    $conditions[] = "status = '$status_filter'";
}
if (!empty($unit_filter)) {
    // Lọc tương đối theo Đơn vị
    $conditions[] = "unit LIKE '%$unit_filter%'";
}

// 3. GHÉP LỆNH SQL
$sql = "SELECT * FROM training_needs";

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
$needs = [];

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['duration'] = number_format((float)$row['duration'], 2, ',', '.');
            $needs[] = $row;
        }
    }
    echo json_encode($needs);
} else {
    echo json_encode(["error" => true, "message" => "Lỗi CSDL: " . $conn->error]);
}

$conn->close();
?>