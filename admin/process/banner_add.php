<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/BannerController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$BannerController = new BannerController($conn);

if (isset($_POST['btn-add'])) {
    $bnName = $_POST['bn_name'];
    $bnLink = $_POST['bn_link'];
    $bnImg = $_FILES['bn_img']['name'];
    $bnStatus = $_POST['bn_status'];

    echo "bnName : " . $bnName . "<br>";
    echo "bnLink : " . $bnLink . "<br>";
    echo "bnImg : " . $bnImg . "<br>";
    echo "bnStatus : " . $bnStatus . "<br>";

    $locationError = "Location: ../banner_show";
    $locationSuccess = "Location: ../banner_show";

    valiDateFormAddBanner($bnName, $bnLink, $bnImg, $bnStatus, $locationError);

   

    // เช็คชื่อซ้ำ
    $check = $BannerController->checkBannerName($bnName);

    if ($check) {
        messageError("ชื่อแบนเนอร์นี้ มีอยู่แล้ว", $locationError);
    } else {

        $folderUploads = "../../uploads/img_banner/";
        $fileExtension = pathinfo($bnImg, PATHINFO_EXTENSION);
        $fileSize = $_FILES["bn_img"]["size"];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        
        if ($fileSize > $maxFileSize) {
            messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
        }

        // ส่มชื่อรูปภาพใหม่
        $newImg = generateUniqueImg($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newImg;

        if (move_uploaded_file($_FILES["bn_img"]["tmp_name"], $targetFilePath)) {

            $InsertBanner = $BannerController->insertBanner($bnName, $bnLink,$newImg, $bnStatus);
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }


    if($InsertBanner){
        $_SESSION['success'] = "เพิ่มข้อมูลแบนเนอร์ สำเร็จ";
        header($locationSuccess);
        exit;
    }else{
        $_SESSION['error'] = "เพิ่มข้อมูลแบนเนอร์ ไม่สำเร็จ";
        header($locationError);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
