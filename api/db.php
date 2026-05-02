<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "fpt_elearning"; // Tên database 

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối CSDL thất bại"]));
}
$conn->set_charset("utf8");
?>