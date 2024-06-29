<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PromotionController.php');

$PromotionController = new PromotionController($conn);

if (isset($_POST['btn-add'])) {
    $proName = $_POST['pro_name'];
    $proPercentDiscount = $_POST['pro_percent_discount'];
    $proTimeStart = $_POST['pro_time_start'];
    $proTimeEnd = $_POST['pro_time_end'];
    $proDetail = $_POST['pro_detail'];
    $proStatus = $_POST['pro_status'];
    
    $locationError = "Location: ../promotion_show";
    $locationSuccess = "Location: ../promotion_show";

    validateFormPromotion($proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus, $locationError);

    $check = $PromotionController->checkPromotionName($proName);

    if($check){
        messageError("ชื่อโปรโมชั่นนี้ มีอยู่แล้ว", $locationError);
    }else{

          // เงื่อนไขรูปภาพ default
          $folderUploads = "../../uploads/img_promotion/";
          $imgDefault = "default.png";
          $defaultImagePath = $folderUploads . $imgDefault;
          $allowedExtensions = ['png', 'jpg', 'jpeg'];
          $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
          $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);
  
          // // ตรวจสอบรูป Default
          checkDefaultImg($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);
  
          // ส่มชื่อรูปภาพใหม่
          $newImg = generateUniqueImg($fileExtension, $folderUploads);
          $targetFilePath = $folderUploads . $newImg;

          // Copy default image to new file
        if (copy($defaultImagePath, $targetFilePath)) {

            // Insert Promotion
            $insertPromotion = $PromotionController->insertPromotion($newImg, $proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus);
            $_SESSION['success'] = "เพิ่มข้อมูลสำนักพิมพ์ สำเร็จ";
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }

    header($locationSuccess);
} else {
    header('Location: ../error_not_result.php');
    exit;
}
