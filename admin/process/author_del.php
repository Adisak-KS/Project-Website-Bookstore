<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/AuthorController.php');

$AuthorController = new AuthorController($conn);

if (isset($_POST["id"])) {
    $authId = $_POST["id"];
    $authImg = $_POST["img"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../author_del_form.php?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../author_show.php";

    $deleteAuthor = $AuthorController->deleteAuthor($authId);

    if ($deleteAuthor) {
        // ลบรูปเดิม
        if (!empty($authImg)) {
            $folderUploads = "../../uploads/img_author/";
            deleteImg($authImg , $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลผู้แต่ง สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result.php');
    exit;
}
