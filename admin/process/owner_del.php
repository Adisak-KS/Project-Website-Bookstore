<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');


if (isset($_POST["id"])) {
    $Id = $_POST["id"];
    $profile = $_POST["profile"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../owner_del_form.php?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../owner_show.php";

    $deleteEmployee = $BaseController->deleteEmployees($Id);

    if ($deleteEmployee) {
        // ลบรูปเดิม
        deleteProfileEmployees($profile);
        $_SESSION["success"] = "ลบข้อมูลเจ้าของร้าน / ผู้บริหาร สำเร็จ";
    }

    header($locationSuccess);
    exit;

} else {
     header('Location: ../error_not_result.php');
    exit;
}
