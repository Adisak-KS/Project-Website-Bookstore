<?php
require_once('../db/connectdb.php');
require_once('../includes/functions.php');
require_once("../includes/salt.php");   // รหัส Salt 

if (isset($_POST['btn-upload-slip'])) {
    $memId = $_POST['mem_id'];
    $ordId = $_POST['ord_id'];
    $oslSlip = $_FILES['osl_slip']['name'];

    $originalId = $ordId;
    $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
    $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64

    $locationError = "Location: ../checkout_payment?id=$base64Encoded";
    $locationSuccess = "Location: ../account_order_history";

    if (empty($oslSlip)) {
        messageError("กรุณาระบุ สลิปหลักฐานการชำระเงิน", $locationError);
    } else {
        // มีรูปภาพมาด้วย
        $folderUploads = "../uploads/img_slip/";
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

        $fileExtension = strtolower(pathinfo($oslSlip, PATHINFO_EXTENSION));
        $fileSize = $_FILES["osl_slip"]["size"];

        if (!in_array($fileExtension, $allowedExtensions)) {
            messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
        } elseif ($fileSize > $maxFileSize) {
            messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
        } else {

            // Random File Name
            $newImg =  generateUniqueImg($fileExtension, $folderUploads);
            $targetFilePath = $folderUploads . $newImg;

            if (move_uploaded_file($_FILES["osl_slip"]["tmp_name"], $targetFilePath)) {

                $insertSlip = $OrderController->insertOrderSlip($ordId, $memId, $newImg);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }
    }

    if($insertSlip){
        $_SESSION['success'] = "ชำระเงินรายการสั่งซื้อที่ ". $ordId ." สำเร็จ";
        header($locationSuccess);
        exit;
    }
   
} else {
    header('Location: ../error_not_result');
    exit;
}
