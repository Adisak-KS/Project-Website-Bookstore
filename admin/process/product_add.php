<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductController.php');

$ProductController = new ProductController($conn);

if (isset($_POST['btn-add'])) {

    $prdName = $_POST['prd_name'];
    $prdISBN = $_POST['prd_isbn'];
    $prdCoin = $_POST['prd_coin'];
    $prdQuantity = $_POST['prd_quantity'];
    $prdNumberPages = $_POST['prd_number_pages'];
    $prdPrice = $_POST['prd_price'];
    $prdPercentDiscount = $_POST['prd_percent_discount'];
    $ptyId = $_POST['pty_id'];
    $pubId = $_POST['pub_id'];
    $authId = $_POST['auth_id'];
    $prdPreorder = $_POST['prd_preorder'];
    $prdStatus = $_POST['prd_status'];

    $locationError = "Location: ../product_show.php";
    $locationSuccess = "Location: ../product_show.php";

    echo "prdName : " . $prdName . "<br>";
    echo "prdISBN : " . $prdISBN . "<br>";
    echo "prdCoin : " . $prdCoin . "<br>";
    echo "prdQuantity : " . $prdQuantity . "<br>";
    echo "prdNumberPages : " . $prdNumberPages . "<br>";
    echo "prdPrice : " . $prdPrice . "<br>";
    echo "prdPercentDiscount : " . $prdPercentDiscount . "<br>";
    echo "ptyId : " . $ptyId . "<br>";
    echo "pubId : " . $pubId . "<br>";
    echo "authId : " . $authId . "<br>";
    echo "prdPreorder : " . $prdPreorder . "<br>";
    echo "prdStatus : " . $prdStatus . "<br>";

    // ตรวจสอบข้อมูลจาก Form
    validateFormAddProduct($prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus, $locationError);

    // ตรวจสอบ Name, ISBN ซ้ำ
    $check = $ProductController->checkProductName($prdName, $prdISBN);

    if ($check) {
        messageError("ชื่อสินค้า หรือ รหัส ISBN นี้มีอยู่แล้ว", $locationError);
    } else {

        $folderUploads = "../../uploads/img_product/";
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
        // echo "newImg : ".  $newImg . "<br>";
        // Copy default image to new file
        if (copy($defaultImagePath, $targetFilePath)) {

            // Insert Product
            $insertProduct = $ProductController->insertProduct($prdName, $newImg, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus);
            if ($insertProduct) {
                $_SESSION['success'] = "เพิ่มข้อมูลสินค้า สำเร็จ";
                header($locationSuccess);
            }
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }
} else {
  
    exit;
}
