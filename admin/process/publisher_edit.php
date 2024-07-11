<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/PublisherController.php');
require_once(__DIR__ . '/../includes/functions.php');

$PublisherController = new PublisherController($conn);

if (isset($_POST['btn-edit'])) {
    $pubId = $_POST['pub_id'];
    $pubName = $_POST['pub_name'];
    $pubDetail = $_POST['pub_detail'];
    $pubStatus = $_POST['pub_status'];

    $pubImg = $_POST['pub_img'];
    $pubNewImg = $_FILES['pub_newImg']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../publisher_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../publishershow";

    valiDateFormPublischer($pubName, $pubDetail, $pubStatus, $locationError);

    $check = $PublisherController->checkPublisherName($pubName, $pubId);

    if ($check) {
        messageError("ชื่อสำนักพิมพ์นี้ มีอยู่แล้ว", $locationError);
    } else {

        $updatePublisher = $PublisherController->updateDetailPublisher($pubName, $pubDetail, $pubStatus, $pubId);

        if ($updatePublisher) {

            // เปลี่ยนรูปใหม่
            if (!empty($pubNewImg)) {

                $folderUploads = "../../uploads/img_publisher/";
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


                $fileExtension = strtolower(pathinfo($pubNewImg, PATHINFO_EXTENSION));
                $fileSize = $_FILES["pub_newImg"]["size"];


                // Validate file type and size
                if (!in_array($fileExtension, $allowedExtensions)) {
                    messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
                } elseif ($fileSize > $maxFileSize) {
                    messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
                } else {

                    // Random File Name
                    $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                    $targetFilePath = $folderUploads . $newImg;

                    if (move_uploaded_file($_FILES["pub_newImg"]["tmp_name"], $targetFilePath)) {
                        // ลบรูปเดิม
                        if (!empty($pubImg)) {
                            deleteImg($pubImg, $folderUploads);
                        }

                        $updateNewImg = $PublisherController->updateNewImgPublisher($newImg, $pubId);
                    } else {
                        messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                    }
                }
            }
        }
    }

    if ($updatePublisher || $updateNewImg) {
        $_SESSION['success'] = "แก้ไขสำนักพิมพ์สำเร็จ สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
