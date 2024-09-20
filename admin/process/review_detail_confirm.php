<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/ReviewController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$ReviewController = new ReviewController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prvId = $_POST["prv_id"];

    $locationSuccess = "refresh:1; url=../review_show";

    $updateReview = $ReviewController->updateReviewStatusShow($prvId);
    if ($updateReview) {
        $_SESSION["success"] = "แสดงรีวิวสินค้าสำเร็จ";
    }
    
    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}