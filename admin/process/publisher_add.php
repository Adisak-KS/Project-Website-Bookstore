<?php

require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PublisherController.php');

$PublisherController = new PublisherController($conn);



if (isset($_POST['btn-add'])) {
    $pubName = $_POST['pub_name'];
    $pubDetail = $_POST['pub_detail'];
    $pubStatus = $_POST['pub_status'];

    $locationError = "Location: ../publisher_show";
    $locationSuccess = "Location: ../publisher_show";

    valiDateFormPublischer($pubName, $pubDetail,  $pubStatus, $locationError);

    $check = $PublisherController->checkPublisherName($pubName);
    if ($check) {
        messageError("ชื่อประเภทสินค้านี้ มีอยู่แล้ว", $locationError);
    } else {

        // เงื่อนไขรูปภาพ default
        $folderUploads = "../../uploads/img_publisher/";
        $imgDefault = "default.png";
        $defaultImagePath = $folderUploads . $imgDefault;
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);

        // // ตรวจสอบรูป Default
        checkDefaultProfileEmployees($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);

        // ส่มชื่อรูปภาพใหม่
        $newImg = generateUniqueImg($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newImg;

        // Copy default image to new file
        if (copy($defaultImagePath, $targetFilePath)) {

            // Insert Publisher
            $insertPublisher = $PublisherController->insertPublisher($newImg, $pubName, $pubDetail, $pubStatus);
            $_SESSION['success'] = "เพิ่มข้อมูลสำนักพิมพ์ สำเร็จ";
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }


    if ($insertPublisher) {
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result.php');
    exit;
}
