<?php
require_once('../db/connectdb.php');
require_once('../includes/functions.php');
require_once('../db/controller/ProductRequestController.php');

$ProductRequestController = new ProductRequestController($conn);

if (isset($_POST['btn-add'])) {
    $memId = $_POST['mem_id'];
    $prqTitle = $_POST['prq_title'];
    $prqPrdName = $_POST['prq_prd_name'];
    $prqPublisher = $_POST['prq_publisher'];
    $prqAuthor = $_POST['prq_author'];
    $prqPrdVolumeNumber = $_POST['prq_prd_volume_number'];
    $prqDetail = $_POST['prq_detail'];
    $prqImg = $_FILES['prq_img']['name'];

    $locationError = "Location: ../product_request_form";
    $locationSuccess = "Location: ../account_product_request";

    echo "mem id = " . $memId . "<br>";
    echo "prq_title = " . $prqTitle . "<br>";
    echo "prq_prd_name = " . $prqPrdName . "<br>";
    echo "prq Publisher = " . $prqPublisher . "<br>";
    echo "prq Author = " . $prqAuthor . "<br>";
    echo "prq Detail = " . $prqDetail . "<br>";
    echo "prq img = " . $prqImg . "<br>";


    valiDateFromProductRequest($memId, $prqTitle, $prqPrdName, $prqPublisher, $prqAuthor, $prqPrdVolumeNumber, $prqDetail, $locationError);

    $check = $ProductRequestController->checkProductRequestTitle($prqTitle, $memId);

    if ($check) {
        messageError("คุณมีหัวข้อการค้นหานี้แล้ว", $locationError);
    } else {

        if (empty($prqImg)) {
            $prqImg = NULL;
        } else {

            // มีรูปภาพมาด้วย
            $folderUploads = "../uploads/img_product_request/";
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

            $fileExtension = strtolower(pathinfo($prqImg, PATHINFO_EXTENSION));
            $fileSize = $_FILES["prq_img"]["size"];

            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {

                // Random File Name
                $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $newImg;

                if (move_uploaded_file($_FILES["prq_img"]["tmp_name"], $targetFilePath)) {

                    // เก็บชื่อรูปใหม่
                    $prqImg = $newImg;
                } else {
                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        }

        // เพิ่มข้อมูล
        $addProductRequest = $ProductRequestController->insertProductRequest($memId, $prqTitle, $prqPrdName, $prqPublisher, $prqAuthor, $prqPrdVolumeNumber, $prqDetail, $prqImg);
    }

    if ($addProductRequest) {
        $_SESSION['success'] = "ส่งรายการค้นหาสินค้าตามสั่งสำเร็จ";
        header($locationSuccess);
        exit;
    } else {
        $_SESSION['success'] = "เกิดข้อผิดพลาด";
        header($locationError);
        exit;
    }
} elseif (isset($_POST['btn-confirm-product-request'])) {
    $prqId = $_POST['prq_id'];
    $prpId = $_POST['prp_id'];

    $locationError = "Location: ../account_product_request";
    $locationSuccess = "Location: ../account_product_request";

    $updateProductRequestFalse = $ProductRequestController->updateProductRequestStatusTrue($prqId, $prpId);

    if ($updateProductRequestFalse) {
        $_SESSION['success'] = "ค้นหาสินค้าตามสั่ง สำเร็จ";
        header($locationSuccess);
        exit;
    }
} elseif (isset($_POST['btn-reset-product-request'])) {

    $prqId = $_POST['prq_id'];
    $prpId = $_POST['prp_id'];

    $locationError = "Location: ../account_product_request";
    $locationSuccess = "Location: ../account_product_request";

    $updateProductRequestFalse = $ProductRequestController->updateProductRequestStatusFalse($prqId, $prpId);

    if ($updateProductRequestFalse) {
        $_SESSION['success'] = "ส่งเรื่องค้นหาใหม่ สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
