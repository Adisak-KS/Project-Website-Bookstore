<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PublisherController.php');

$PublisherController = new PublisherController($conn);

if (isset($_POST["id"])) {
    $pubId = $_POST["id"];
    $pubImg = $_POST["img"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../publisher_del_form.php?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../publisher_show.php";

    $deletePublisher = $PublisherController->deletePublisher($pubId);

    if ($deletePublisher) {
        // ลบรูปเดิม
        if (!empty($pubImg)) {
            $folderUploads = "../../uploads/img_publisher/";
            deleteImg($pubImg , $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลสำนักพิมพ์ สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result.php');
    exit;
}
