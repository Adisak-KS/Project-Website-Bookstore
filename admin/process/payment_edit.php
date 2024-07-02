<?php

require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PaymentController.php');

$PaymentController = new PaymentController($conn);

if (isset($_POST['btn-edit'])) {
    $pmtId = $_POST['pmt_id'];
    $pmtBank = $_POST['pmt_bank'];
    $pmtName = $_POST['pmt_name'];
    $pmtNumber = $_POST['pmt_number'];
    $pmtDetail = $_POST['pmt_detail'];

    $pmtOldStatus = $_POST['pmt_old_status'];
    $pmtStatus = $_POST['pmt_status'];

    $pmtBankLogo = $_POST['pmt_bank_logo'];
    $pmtBankNewLogo = $_FILES['pmt_bank_new_logo']['name'];

    $pmtQrcode = $_POST['pmt_qrcode'];
    $pmtNewQrcode = $_FILES['pmt_new_qrcode']['name'];


    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../payment_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../payment_show.php";

    echo "id : " . $pmtId . "<br>";
    echo "pmtBank : " . $pmtBank . "<br>";
    echo "pmtName : " . $pmtName . "<br>";
    echo "pmtNumber : " . $pmtNumber . "<br>";
    echo "pmtdetail : " . $pmtDetail . "<br>";
    echo "pmtStatus : " . $pmtStatus . "<br>";
    echo "<hr>";

    echo "pmtBankLogo : " . $pmtBankLogo . "<br>";
    echo "pmtBankNewLogo : " . $pmtBankNewLogo . "<br>";
    echo "pmtQrcode : " . $pmtQrcode . "<br>";
    echo "pmtNewQrcode : " . $pmtNewQrcode . "<br>";


    $check = $PaymentController->checkPaymentNumber($pmtNumber, $pmtId);

    if ($check) {
        messageError("หมายเลขบัญชีนี้ มีอยู่แล้ว", $locationError);
    } else {
        // Update Detail
        $updatePaymentDetail = $PaymentController->updatePaymentDetail($pmtBank, $pmtName, $pmtNumber, $pmtDetail, $pmtId);

        $folderUploads = "../../uploads/img_payment/";
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

        if (!empty($pmtBankNewLogo)) {

            $fileExtension = strtolower(pathinfo($pmtBankNewLogo, PATHINFO_EXTENSION));
            $fileSize = $_FILES["pmt_bank_new_logo"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {

                // Random File Name
                $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $newImg;

                // Move uploaded file to target directory
                if (move_uploaded_file($_FILES["pmt_bank_new_logo"]["tmp_name"], $targetFilePath)) {

                    // ลบรูปเดิม
                    if (!empty($pmtBankLogo)) {
                        deleteImg($pmtBankLogo, $folderUploads);
                    }

                    // Update Logo
                    $updatePaymentNewLogo = $PaymentController->updatePaymentNewLogo($newImg, $pmtId);
                } else {

                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        }

        if (!empty($pmtNewQrcode)) {

            $fileExtension = strtolower(pathinfo($pmtNewQrcode, PATHINFO_EXTENSION));
            $fileSize = $_FILES["pmt_new_qrcode"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {

                // Random File Name
                $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $newImg;

                // Move uploaded file to target directory
                if (move_uploaded_file($_FILES["pmt_new_qrcode"]["tmp_name"], $targetFilePath)) {

                    // ลบรูปเดิม
                    if (!empty($pmtQrcode)) {
                        deleteImg($pmtQrcode, $folderUploads);
                    }

                    // Update Qrcode
                    $updatePaymentNewQrcode = $PaymentController->updatePaymentNewQrcode($newImg, $pmtId);
                } else {

                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        }

        if ($pmtStatus !== $pmtOldStatus) {
            $updatePaymentStatus = $PaymentController->updatePaymentStatus($pmtStatus, $pmtId);
        }


        if ($updatePaymentDetail || $updatePaymentNewLogo || $updatePaymentNewQrcode || $updatePaymentStatus) {
            $_SESSION['success'] = "แก้ไขข้อมูลช่องทางชำระเงิน สำเร็จ";
            header($locationSuccess);
            exit;
        }
    }
} else {
  
    exit;
}
