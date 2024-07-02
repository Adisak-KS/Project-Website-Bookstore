<?php

require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PaymentController.php');

$PaymentController = new PaymentController($conn);

if (isset($_POST['btn-add'])) {
    $pmtBank = $_POST['pmt_bank'];
    $pmtName = $_POST['pmt_name'];
    $pmtNumber = $_POST['pmt_number'];
    $pmtDetail = $_POST['pmt_detail'];
    $pmtStatus = $_POST['pmt_status'];

    $locationError = "Location: ../payment_show";
    $locationSuccess = "Location: ../payment_show";


    validateFormAddPayment($pmtBank, $pmtName,$pmtNumber, $pmtDetail, $pmtStatus, $locationError);

    $check = $PaymentController->checkPaymentNumber($pmtNumber);

    if($check){
        messageError("หมายเลขบัญชีนี้ มีอยู่แล้ว", $locationError);
    }else{
        
        $folderUploads = "../../uploads/img_payment/";
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

            // Insert Payment
            $insertPayment = $PaymentController->insertPayment($pmtBank, $newImg, $pmtName, $pmtNumber, $pmtDetail, $pmtStatus);
            
            if ($insertPayment) {
                $_SESSION['success'] = "เพิ่มข้อมูลช่องทางจัดส่ง สำเร็จ";
                header($locationSuccess);
            }
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }
}else{
  
    exit;
}