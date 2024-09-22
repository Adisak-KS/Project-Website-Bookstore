<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductRequestController.php');
require_once(__DIR__ . '/../../includes/salt.php');

$ProductRequestController = new ProductRequestController($conn);

if (isset($_POST['btn-add'])) {
    $prqId = $_POST['prq_id'];
    $empId = $_POST['emp_id'];
    $prpPrdName = $_POST['prp_prd_name'];
    $prpPublisher = $_POST['prp_publisher'];
    $prpAuthor = $_POST['prp_author'];
    $prpPrdVolumeNumber = $_POST['prp_prd_volume_number'];
    $prpDetail = $_POST['prp_detail'];
    $prpImg = $_FILES['prp_img']['name'];

    $base64Encoded =  $_SESSION["base64Encoded"];
    $locationError = "Location: ../product_request_detail?id=$base64Encoded";
    $locationSuccess = "Location: ../product_request_show";



    valiDateFromProductResponse($prqId, $empId, $prpPrdName, $prpPublisher, $prpAuthor, $prpPrdVolumeNumber, $prpDetail, $prpImg, $locationError);


    $folderUploads = "../../uploads/img_product_request/";
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

    $fileExtension = strtolower(pathinfo($prpImg, PATHINFO_EXTENSION));
    $fileSize = $_FILES["prp_img"]["size"];

    if (!in_array($fileExtension, $allowedExtensions)) {
        messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
    } elseif ($fileSize > $maxFileSize) {
        messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
    } else {

        // Random File Name
        $newImg =  generateUniqueImg($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newImg;

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES["prp_img"]["tmp_name"], $targetFilePath)) {

            // เพิ่มการตอบกลับ
            $addProductResponse = $ProductRequestController->insertProductResponse($prqId, $empId, $prpPrdName, $prpPublisher, $prpAuthor, $prpPrdVolumeNumber, $prpDetail, $newImg);

        } else {

            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }

    if($addProductResponse){
        $_SESSION['success'] = "เพิ่มการตอบกลับ สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
