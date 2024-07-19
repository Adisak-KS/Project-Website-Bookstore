<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');


if (isset($_POST["id"])) {
    $Id = $_POST["id"];
    $profile = $_POST["profile"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../employee_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../employee_show";


    validateFormDeleteEmployees($Id, $locationError);

    $deleteEmployee = $BaseController->deleteEmployees($Id);
    if ($deleteEmployee) {
        // ลบรูปเดิม
        deleteProfileEmployees($profile);
        $_SESSION["success"] = "ลบข้อมูลทีมงานสำเร็จ";
    }

    header($locationSuccess);

} else {
     header('Location: ../error_not_result');
    exit;
}
