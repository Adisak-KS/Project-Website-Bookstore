<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/ContactController.php');
require_once(__DIR__ . '/../includes/functions.php');

$ContactController = new ContactController($conn);

if (isset($_POST['btn-edit'])) {
    $ctId = $_POST['ct_id'];
    $ctStatus = $_POST['ct_status'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../contact_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../contact_show";

    // valiDateFormContact($ctDetail, $ctStatus, $locationError);

    if ($ctId == 1 || $ctId == 2 || $ctId == 3) {
        $ctDetail = $_POST['ct_detail'];
        $ctNameLink = $_POST['ct_name_link'];
    }elseif($ctId == 4){
        $ctDetail = $_POST['ct_email'];
    }elseif($ctId == 5){
        $ctDetail = $_POST['ct_phone_number'];
    }elseif($ctId == 6){
        $ctDetail = $_POST['ct_address'];
    }elseif($ctId == 7){
        $ctDetail = $_POST['ct_location'];
    }


    $updateContact = $ContactController->updateDetailContact($ctDetail, $ctNameLink, $ctStatus,  $ctId);


    if ($updateContact) {
        $_SESSION['success'] = "แก้ไขข้อมูลช่องทางติดต่อ สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
