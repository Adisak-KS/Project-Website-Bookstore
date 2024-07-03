<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/ContactController.php');
require_once(__DIR__ . '/../includes/functions.php');

$ContactController = new ContactController($conn);

if (isset($_POST["id"])) {
    $ctId = $_POST['id'];
 
    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../contact_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../contact_show";


    $deleteContact = $ContactController->deleteDetailContact($ctId);


    if ($deleteContact) {
        $_SESSION['success'] = "ลบข้อมูลช่องทางติดต่อ สำเร็จ";
        header( $deleteContact);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
