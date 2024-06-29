<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/AuthorController.php');
require_once(__DIR__ . '/../includes/functions.php');

$AuthorController = new AuthorController($conn);

if (isset($_POST['btn-add'])) {
    $authName = $_POST['auth_name'];
    $authDetail = $_POST['auth_detail'];
    $authStatus = $_POST['auth_status'];

    $locationError = "Location: ../author_show.php";
    $locationSuccess = "Location: ../author_show.php";


    valiDateFormAuthor($authName, $authDetail,  $authStatus, $locationError);

    $check = $AuthorController->checkAuthorName($authName);
    if ($check) {
        messageError("ชื่อผู้แต่งนี้ มีอยู่แล้ว", $locationError);
    } else {

          // เงื่อนไขรูปภาพ default
          $folderUploads = "../../uploads/img_author/";
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

            // Insert Author
            $insertAuthor = $AuthorController->insertAuthor($newImg, $authName, $authDetail, $authStatus);
            $_SESSION['success'] = "เพิ่มข้อมูลผู้แต่ง สำเร็จ";
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }

    header($locationSuccess);
} else {
    header('Location: ../error_not_result.php');
    exit;
}
