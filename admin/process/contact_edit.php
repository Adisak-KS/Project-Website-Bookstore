<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/ContactController.php');
require_once(__DIR__ . '/../includes/functions.php');

$ContactController = new ContactController($conn);

if (isset($_POST['btn-edit'])) {
    $ctId = $_POST['ct_id'];
    $ctDetail = $_POST['ct_detail'];
    $ctStatus = $_POST['ct_status'];


    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../contact_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../contact_show";

    valiDateFormContact($ctDetail, $ctStatus, $locationError);


    $updateContact = $ContactController->updateDetailContact($ctDetail, $ctStatus,  $ctId);




    if ($updateContact) {
        $_SESSION['success'] = "แก้ไขข้อมูลช่องทางติดต่อ สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
