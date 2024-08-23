<?php
require_once('../db/connectdb.php');
require_once('../db/controller/ProductRequestController.php');
require_once('../includes/functions.php');


$ProductRequestController = new ProductRequestController($conn);

if (isset($_POST["prqId"])) {
    $prqId = $_POST["prqId"];
    $prqImg = $_POST['img'];

    $locationError = "refresh:1; url=../account_product_request";
    $locationSuccess = "refresh:1; url=../account_product_request";

    // ตรวจสอบว่ามีรูปตอบกลับอะไรบ้าง
    $imgResponse = $ProductRequestController->getProductResponse($prqId);
    // ลบข้อมูลรายการตามสั่ง
    $deleteProductRequest = $ProductRequestController->deleteProductRequest($prqId);

    if ($deleteProductRequest) {

        $folderUploads = "../uploads/img_product_request/";
        // ลบรูปใน Product Request
        deleteImg($prqImg, $folderUploads);

        if ($imgResponse) {
            // ลบรูปใน Product ReSponse
            foreach ($imgResponse as $row) {
                $prpImg = $row['prp_img'];
                if (!empty($prpImg)) {
                    deleteImg($prpImg, $folderUploads);
                }
            }
        }

        $_SESSION["success"] = "ลบข้อมูลรายการค้นหาสินค้าตามสั่ง สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
