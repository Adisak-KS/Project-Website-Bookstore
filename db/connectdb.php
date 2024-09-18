<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookshop";

$dsn = "mysql:host=$servername;dbname=$dbname";

try {
    $conn = new PDO($dsn, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";

    session_start();

    // เรียกใช้ Controller 
    require_once("controller/BaseController.php");
    require_once("controller/OrderController.php");

    // สร้าง object ของคลาส Controller ด้วย connection object
    $BaseController = new BaseController($conn);
    $BaseController->insertEmployeesTypeDefault(); // ตรวจสอบและเพิ่มประภทสิทธิ์ (Default)
    $BaseController->insertSuperAdminDefault();   // Super Admin

    
    $OrderController = new OrderController($conn);
    $OrderController->cancelOrdersOlderThanThreeDays(); // ยกเลิก order ที่เกิน 3 วัน
    $OrderController->confirmOrdersOlderThanFourteenDays(); // ยืนยัน order เมื่อเกิน 14 วัน
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
