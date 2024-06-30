<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductController.php');
require_once(__DIR__ . '/../../includes/salt.php');

$ProductController = new ProductController($conn);

if (isset($_POST['btn-edit'])) {
    $prdId = $_POST['prd_id'];
    $prdName = $_POST['prd_name'];
    $prdISBN = $_POST['prd_isbn'];
    $prdCoin = $_POST['prd_coin'];
    $prdQuantity = $_POST['prd_quantity'];
    $prdNumberPages = $_POST['prd_number_pages'];
    $prdPrice = $_POST['prd_price'];
    $prdPercentDiscount = $_POST['prd_percent_discount'];
    $oldPtyId = $_POST['old_pty_id'];
    $ptyId = $_POST['pty_id'];
    $pubId = $_POST['pub_id'];
    $authId = $_POST['auth_id'];
    $prdPreorder = $_POST['prd_preorder'];
    $prdStatus = $_POST['prd_status'];
    $prdDetail = $_POST['prd_detail'];

    $prdImg1 = $_POST['prd_img1'];
    $prdNewImg1 = $_FILES['prd_newImg1']['name'];

    $prdImg2 = $_POST['prd_img2'];
    $prdNewImg2 = $_FILES['prd_newImg2']['name'];


    echo "prdId : " . $prdId . "<br>";
    echo "prdName : " . $prdName . "<br>";
    echo "prdISBN : " . $prdISBN . "<br>";
    echo "prdCoin : " . $prdCoin . "<br>";
    echo "prdQuqantity : " . $prdQuantity . "<br>";
    echo "prdPrice : " . $prdPrice . "<br>";
    echo "OldPtyId : " . $oldPtyId . "<br>";
    echo "ptyId : " . $ptyId . "<br>";
    echo "pubId : " . $pubId . "<br>";
    echo "authId : " . $authId . "<br>";
    echo "prdPreorder : " . $prdPreorder . "<br>";
    echo "prdStatus : " . $prdStatus . "<br>";
    echo "prdDetail : " . $prdDetail . "<br>";
    echo "<hr>";
    echo "prdImg1 : " . $prdImg1 . "<br>";
    echo "prdNewImg1 : " . $prdNewImg1 . "<br>";
    echo "<hr>";
    echo "prdImg2 : " . $prdImg2 . "<br>";
    echo "prdNewImg2 : " . $prdNewImg2 . "<br>";

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../product_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../product_show";

    if ($ptyId !== $oldPtyId) {
        echo "ไม่เท่ากัน";
    }

    validateFormUpdateProduct($prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdDetail, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus, $locationError);


    $check = $ProductController->checkProductName($prdName, $prdISBN, $prdId);
    if ($check) {
        messageError("ชื่อสินค้า หรือ รหัส ISBN นี้มีอยู่แล้ว", $locationError);
    } else {

        $updateDetailProduct = $ProductController->updateDetailProduct($prdId, $prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdDetail, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus);

        if ($updateDetailProduct && $ptyId !== $oldPtyId) {
            $updateProductViews = $ProductController->updateProductView($prdId, $ptyId);
        }


        $folderUploads = "../../uploads/img_product/";
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

        // หากมีการอัปโหลดรูปภาพหลักใหม่
        if (!empty($prdNewImg1)) {

            $fileExtension = strtolower(pathinfo($prdNewImg1, PATHINFO_EXTENSION));
            $fileSize = $_FILES["prd_newImg1"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {

                // Random File Name
                $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $newImg;

                if (move_uploaded_file($_FILES["prd_newImg1"]["tmp_name"], $targetFilePath)) {
                    // ลบรูปเดิม
                    if (!empty($prdImg1)) {
                        deleteImg($prdImg1, $folderUploads);
                    }
                    $updateNewImg1 = $ProductController->updateProductImg1($newImg, $prdId);
                } else {
                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        }

        // หากมีการอัปโหลดรูปภาพหลักใหม่
        if(!empty($prdNewImg2)){
            
            $fileExtension = strtolower(pathinfo($prdNewImg2, PATHINFO_EXTENSION));
            $fileSize = $_FILES["prd_newImg2"]["size"];

             // Validate file type and size
             if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {
                  // Random File Name
                  $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                  $targetFilePath = $folderUploads . $newImg;

                  if (move_uploaded_file($_FILES["prd_newImg2"]["tmp_name"], $targetFilePath)) {
                    // ลบรูปเดิม
                    if (!empty($prdImg2)) {
                        deleteImg($prdImg2, $folderUploads);
                    }
                    $updateNewImg2 = $ProductController->updateProductImg2($newImg, $prdId);
                } else {
                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        }
    }

    if ($updateDetailProduct || $updateProductViews || $updateNewImg1 || $updateNewImg2) {
        $_SESSION['success'] = "แก้ไขข้อมูลสินค้า สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result.php');
    exit;
}
