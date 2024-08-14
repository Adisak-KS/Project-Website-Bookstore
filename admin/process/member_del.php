<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/MemberController.php');

$MemberController = new MemberController($conn);

if (isset($_POST["id"])) {
    $Id = $_POST["id"];
    $profile = $_POST["profile"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../member_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../member_show";

    $folderUploads = "../../uploads/img_member/";

    $deleteMember = $MemberController->deleteMember($Id);

    if ($deleteMember) {
        // ลบรูปเดิม
        deleteImg($profile, $folderUploads);

        $_SESSION["success"] = "ลบข้อมูลสมาชิก สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
