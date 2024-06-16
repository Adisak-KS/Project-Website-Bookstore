<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/AuthorController.php');
require_once(__DIR__ . '/../includes/functions.php');

$AuthorController = new AuthorController($conn);

if (isset($_POST['btn-edit'])) {
    $authId = $_POST['auth_id'];
    $authName = $_POST['auth_name'];
    $authDetail = $_POST['auth_detail'];
    $authStatus = $_POST['auth_status'];

    $authImg = $_POST['auth_img'];
    $authNewImg = $_FILES['auth_newImg']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../author_edit_form.php?id=$base64Encoded";
    $locationSuccess = "Location: ../author_show.php";

    valiDateFormAuthor($authName, $authDetail,  $authStatus, $locationError);

    $check = $AuthorController->checkAuthorName($authName, $authId);
    if ($check) {
        messageError("ชื่อผู้แต่งนี้ มีอยู่แล้ว", $locationError);
    } else {

        $updateAuthor = $AuthorController->updateDetailAuthor($authName, $authDetail, $authStatus, $authId);

        if ($updateAuthor) {

            if (!empty($authNewImg)) {

                $folderUploads = "../../uploads/img_author/";
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


                $fileExtension = strtolower(pathinfo($authNewImg, PATHINFO_EXTENSION));
                $fileSize = $_FILES["auth_newImg"]["size"];


                // Validate file type and size
                if (!in_array($fileExtension, $allowedExtensions)) {
                    messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
                } elseif ($fileSize > $maxFileSize) {
                    messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
                } else {


                    // Random File Name
                    $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                    $targetFilePath = $folderUploads . $newImg;


                    if (move_uploaded_file($_FILES["auth_newImg"]["tmp_name"], $targetFilePath)) {
                        // ลบรูปเดิม
                        if (!empty($authImg)) {
                            deleteImg($authImg, $folderUploads);
                        }

                        $updateNewImg = $AuthorController->updateImgAuthor($newImg, $authId);
                    } else {
                        messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                    }
                }
            }
        }
    }
    

    if ($updateAuthor || $updateNewImg) {
        $_SESSION['success'] = "แก้ไขข้อมูลผู้แต่ง สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result.php');
    exit;
}
