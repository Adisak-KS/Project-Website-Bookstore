<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');


if (isset($_POST["id"])) {
    $Id = $_POST["id"];
    $profile = $_POST["profile"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../admin_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../admin_show";

    $folderUploads = "../../uploads/img_employees/";


    validateFormDeleteEmployees($Id, $locationError);

    $deleteEmployee = $BaseController->deleteEmployees($Id);
    if ($deleteEmployee) {

        // ลบรูปเดิม
        deleteImg($profile, $folderUploads);
        
        $_SESSION["success"] = "ลบข้อมูลผู้ดูแลระบบสำเร็จ";
    }

    header($locationSuccess);
} else {
    header('Location: ../error_not_result');
    exit;
}
