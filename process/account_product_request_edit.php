<?php
require_once('../db/connectdb.php');
require_once('../db/controller/ProductRequestController.php');
require_once('../includes/functions.php');


$ProductRequestController = new ProductRequestController($conn);

if (isset($_POST['btn-edit'])) {
    $prqId = $_POST['prq_id'];
    $memId = $_POST['mem_id'];
    $prqTitle = $_POST['prq_title'];
    $prqPrdName = $_POST['prq_prd_name'];
    $prqPublisher = $_POST['prq_publisher'];
    $prqAuthor = $_POST['prq_author'];
    $prqPrdVolumeNumber = $_POST['prq_prd_volume_number'];
    $prqDetail = $_POST['prq_detail'];
    $prqOldImg = $_POST['prq_oldImg'];
    $prqNewImg = $_FILES['prq_newImg']['name'];

    $base64Encoded =  $_SESSION["base64Encoded"];
    $locationError = "Location: ../account_product_request_detail?id=$base64Encoded";
    $locationSuccess = "Location: ../account_product_request_detail?id=$base64Encoded";

    echo "prq id = " . $prqId . "<br>";
    echo "mem id = " . $memId . "<br>";
    echo "prq_title = " . $prqTitle . "<br>";
    echo "prq_prd_name = " . $prqPrdName . "<br>";
    echo "prq Publisher = " . $prqPublisher . "<br>";
    echo "prq Author = " . $prqAuthor . "<br>";
    echo "prq_prd_volume_number = " . $prqPrdVolumeNumber . "<br>";
    echo "prq Detail = " . $prqDetail . "<br>";
    echo "prq old img = " . $prqOldImg . "<br>";
    echo "prq New img = " . $prqNewImg . "<br>";

    valiDateFromProductRequest($memId, $prqTitle, $prqPrdName, $prqPublisher, $prqAuthor, $prqPrdVolumeNumber, $prqDetail, $locationError);

    $check = $ProductRequestController->checkProductRequestEditTitle($prqId, $prqTitle, $memId);

    if ($check) {
        echo "ไม่ได้";
        messageError("หัวเรื่องการค้นหานี้คุณมีอยู่แล้ว", $locationError);
    } else {
        echo "ได้";
        $updateProductRequestDetail = $ProductRequestController->updateProductRequest($prqTitle, $prqPrdName, $prqPublisher, $prqAuthor, $prqPrdVolumeNumber, $prqDetail, $prqId);


        if (!empty($prqNewImg)) {
            $folderUploads = "../uploads/img_product_request/";
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

            $fileExtension = strtolower(pathinfo($prqNewImg, PATHINFO_EXTENSION));
            $fileSize = $_FILES["prq_newImg"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            }

            // ส่มชื่อรูปภาพใหม่
            $prqNewImg = generateUniqueImg($fileExtension, $folderUploads);
            $targetFilePath = $folderUploads . $prqNewImg;

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["prq_newImg"]["tmp_name"], $targetFilePath)) {

                // ลบรูปเดิม
                deleteImg($prqOldImg, $folderUploads);

                // Update New Img
                $updateNewImg = $ProductRequestController->updateNewImgProductRequest($prqId, $prqNewImg);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }
    }

    if ($updateProductRequestDetail || $updateNewImg) {
        $_SESSION['success'] = "แก้ไขรายการค้นหาสินค้าตามสั่ง สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
