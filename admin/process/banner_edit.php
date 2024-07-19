<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/BannerController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$BannerController = new BannerController($conn);

if (isset($_POST['btn-edit'])) {
    $bnId = $_POST['bn_id'];
    $bnName = $_POST['bn_name'];
    $bnLink = $_POST['bn_link'];
    $bnImg = $_POST['bn_img'];
    $bnNewImg = $_FILES['bn_newImg']['name'];
    $bnStatus = $_POST['bn_status'];

    echo "bnId : " . $bnId . "<br>";
    echo "bnName : " . $bnName . "<br>";
    echo "bnLink : " . $bnLink . "<br>";
    echo "bnImg : " . $bnImg . "<br>";
    echo "bnNewImg : " . $bnNewImg . "<br>";
    echo "bnStatus : " . $bnStatus . "<br>";

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../banner_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../banner_show";

    valiDateFormUpdateBanner($bnName, $bnLink, $bnStatus, $locationError);


    // เช็คชื่อซ้ำ
    $check = $BannerController->checkBannerName($bnName, $bnId);

    if ($check) {
        messageError("ชื่อแบนเนอร์นี้ มีอยู่แล้ว", $locationError);
    } else {

        $updateBanner = $BannerController->updateDetailBanner($bnName, $bnLink, $bnStatus, $bnId);


        if ($updateBanner){
            if (!empty($bnNewImg)) {
                $folderUploads = "../../uploads/img_banner/";
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


                $fileExtension = strtolower(pathinfo($bnNewImg, PATHINFO_EXTENSION));
                $fileSize = $_FILES["bn_newImg"]["size"];


                // Validate file type and size
                if (!in_array($fileExtension, $allowedExtensions)) {
                    messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
                } elseif ($fileSize > $maxFileSize) {
                    messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
                } else {

                    // Random File Name
                    $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                    $targetFilePath = $folderUploads . $newImg;

                    if (move_uploaded_file($_FILES["bn_newImg"]["tmp_name"], $targetFilePath)) {
                        // ลบรูปเดิม
                        if (!empty($bnImg)) {
                            deleteImg($bnImg, $folderUploads);
                        }

                        $updateNewImg = $BannerController->updateImgBanner($newImg, $bnId);
                    } else {
                        messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                    }
                }
            }
        }
    }

    if ($updateBanner ||  $updateNewImg) {
        $_SESSION['success'] = "แก้ไขข้อมูลแบนเนอร์ สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
