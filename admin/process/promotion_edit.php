<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/PromotionController.php');
require_once(__DIR__ . '/../includes/functions.php');

$PromotionController = new PromotionController($conn);

if (isset($_POST['btn-edit'])) {
    $proId = $_POST['pro_id'];
    $proName = $_POST['pro_name'];
    $proPercentDiscount = $_POST['pro_percent_discount'];
    $proTimeStart = $_POST['pro_time_start'];
    $proTimeEnd = $_POST['pro_time_end'];
    $proDetail = $_POST['pro_detail'];
    $proStatus = $_POST['pro_status'];

    $proImg = $_POST['pro_img'];
    $proNewImg = $_FILES['pro_newImg']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../promotion_edit_form.php?id=$base64Encoded";
    $locationSuccess = "Location: ../promotion_show.php";

    validateFormPromotion($proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus, $locationError);

    $check = $PromotionController->checkPromotionName($proName, $proId);

    if ($check) {
        messageError("ชื่อโปรโมชั่นนี้ มีอยู่แล้ว", $locationError);
    } else {

        $updatePromotion = $PromotionController->updateDetailPromotion($proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus, $proId);

        if ($updatePromotion) {

            if (!empty($proNewImg)) {
                $folderUploads = "../../uploads/img_promotion/";
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


                $fileExtension = strtolower(pathinfo($proNewImg, PATHINFO_EXTENSION));
                $fileSize = $_FILES["pro_newImg"]["size"];


                // Validate file type and size
                if (!in_array($fileExtension, $allowedExtensions)) {
                    messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
                } elseif ($fileSize > $maxFileSize) {
                    messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
                } else {

                    // Random File Name
                    $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                    $targetFilePath = $folderUploads . $newImg;

                    if (move_uploaded_file($_FILES["pro_newImg"]["tmp_name"], $targetFilePath)) {
                        // ลบรูปเดิม
                        if (!empty($proImg)) {
                            deleteImg($proImg, $folderUploads);
                        }

                        $updateNewImg = $PromotionController->updateImgPromotion($newImg, $proId);
                    } else {
                        messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                    }
                }
            }
        }
    }

    if ($updatePromotion ||  $updateNewImg) {
        $_SESSION['success'] = "แก้ไขข้อมูลโปรโมชั่น สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
